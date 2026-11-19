<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class CatalogController extends Controller {
    public function index(Request $request) {
        // Cache ready laptops for 60 seconds (invalidated on stock change)
        $cached = Cache::get('catalog.ready_laptops');
        if ($cached === null) {
            $cached = Laptop::where('stok', '>', 0)->where('status', 'ready')->get()->toArray();
            Cache::put('catalog.ready_laptops', $cached, 60);
        }

        $readyLaptops = Laptop::hydrate($cached);

        $filtered = $readyLaptops;

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $filtered = $filtered->filter(function ($laptop) use ($search) {
                return str_contains(strtolower($laptop->merk), $search)
                    || str_contains(strtolower($laptop->tipe), $search);
            });
        }

        if ($request->sort === 'cheap') {
            $filtered = $filtered->sortBy('harga_sewa_per_hari')->values();
        } elseif ($request->sort === 'expensive') {
            $filtered = $filtered->sortByDesc('harga_sewa_per_hari')->values();
        } else {
            $filtered = $filtered->values();
        }

        $page = (int) $request->get('page', 1);
        $perPage = 6;
        $paginated = new LengthAwarePaginator(
            $filtered->forPage($page, $perPage)->values(),
            $filtered->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('laptops.index', ['laptops' => $paginated]);
    }

    public function show(Laptop $laptop) {
        return view('laptops.show', compact('laptop'));
    }

    public function storeSewa(Request $request, Laptop $laptop) {
        // Guest: simpan data sewa di session, redirect ke login
        if (!auth()->check()) {
            session()->put('pending_sewa', [
                'laptop_id' => $laptop->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);
            return redirect()->route('login')->with('info', 'Silakan login untuk melanjutkan sewa.');
        }

        if (!auth()->user()->hasRole('user')) {
            abort(403, 'Hanya user yang dapat menyewa.');
        }

        $request->validate([
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'terms' => 'required|accepted',
        ]);

        // Idempotency: prevent double submit within 60 seconds
        $recentDuplicate = Transaction::where('user_id', auth()->id())
            ->where('laptop_id', $laptop->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subSeconds(60))
            ->exists();

        if ($recentDuplicate) {
            return back()->withErrors(['error' => 'Permintaan sewa sedang diproses. Mohon tunggu sebentar.']);
        }

        return $this->executeSewa(auth()->user(), $laptop, $request->tanggal_mulai, $request->tanggal_selesai);
    }

    public function processPendingSewa(Request $request)
    {
        $pending = session()->pull('pending_sewa');

        if (!$pending || !isset($pending['laptop_id'])) {
            return redirect()->route('laptops.index')->with('error', 'Tidak ada data sewa pending.');
        }

        $laptop = Laptop::find($pending['laptop_id']);
        if (!$laptop) {
            return redirect()->route('laptops.index')->with('error', 'Laptop tidak ditemukan.');
        }

        $user = auth()->user();
        if (!$user->hasRole('user')) {
            return redirect()->route('laptops.index')->with('error', 'Hanya user yang dapat menyewa.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($pending, [
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        if ($validator->fails()) {
            session()->put('pending_sewa', $pending);
            return redirect()->route('laptops.show', $laptop->id)->withErrors($validator)->with('error', 'Data sewa tidak valid. Silakan coba lagi.');
        }

        $recentDuplicate = Transaction::where('user_id', $user->id)
            ->where('laptop_id', $laptop->id)
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subSeconds(60))
            ->exists();

        if ($recentDuplicate) {
            return redirect()->route('transactions.index')->with('success', 'Permintaan sewa sedang diproses.');
        }

        return $this->executeSewa($user, $laptop, $pending['tanggal_mulai'], $pending['tanggal_selesai']);
    }

    protected function executeSewa($user, Laptop $laptop, $tanggal_mulai, $tanggal_selesai)
    {
        DB::beginTransaction();
        try {
            $laptop = Laptop::lockForUpdate()->find($laptop->id);
            if (!$laptop || $laptop->stok < 1 || $laptop->status !== 'ready') {
                DB::rollBack();
                return redirect()->route('laptops.show', $laptop->id ?? $laptop)->withErrors(['stok' => 'Maaf, stok ready laptop ini sedang habis atau tidak tersedia.']);
            }

            $diff = (new \DateTime($tanggal_mulai))->diff(new \DateTime($tanggal_selesai))->days;

            Transaction::create([
                'laptop_id' => $laptop->id,
                'user_id' => $user->id,
                'nama_peminjam' => $user->name,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'total_biaya' => $diff * $laptop->harga_sewa_per_hari,
                'status' => 'pending'
            ]);

            $laptop->decrement('stok');
            if ($laptop->stok <= 0) {
                $laptop->update(['status' => 'empty']);
            }

            // Invalidate catalog cache
            Cache::forget('catalog.ready_laptops');

            DB::commit();
            return redirect()->route('transactions.index')->with('success', 'Berhasil! Pesanan sewa Anda telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memproses penyewaan.']);
        }
    }
}