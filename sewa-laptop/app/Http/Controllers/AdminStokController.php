<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\UnitInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStokController extends Controller {
    public function manageStok() {
        $laptops = Laptop::paginate(10);
        $summary = [
            'ready' => Laptop::sum('stok'),
            'maintenance' => Laptop::sum('maintenance_stok'),
            'not_ready' => Laptop::sum('not_ready_stok'),
        ];
        return view('admin.stok.manage', compact('laptops', 'summary'));
    }

    public function transferStok(Request $request, Laptop $laptop) {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'target' => 'required|in:ready,maintenance,not_ready',
            'from'   => 'required|in:ready,maintenance,not_ready',
            'catatan' => 'required|string|max:255'
        ]);

        if ($request->from === $request->target) {
            return back()->withErrors(['error' => 'Sumber dan target tidak boleh sama.']);
        }

        DB::beginTransaction();
        try {
            $sources = ['ready' => 'stok', 'maintenance' => 'maintenance_stok', 'not_ready' => 'not_ready_stok'];
            
            $srcCol = $sources[$request->from];
            $tgtCol = $sources[$request->target];

            $laptop = Laptop::lockForUpdate()->findOrFail($laptop->id);

            if ($laptop->$srcCol < $request->jumlah) {
                throw new \Exception("Stok di " . ucfirst($request->from) . " tidak mencukupi.");
            }

            $laptop->{$srcCol} -= $request->jumlah;
            $laptop->{$tgtCol} += $request->jumlah;

            // Sinkronkan status ketersediaan berdasarkan stok ready
            $laptop->status = ($laptop->stok > 0) ? 'ready' : 'empty';
            $laptop->save();
            
            UnitInspection::create([
                'laptop_id' => $laptop->id,
                'status_sebelum' => $request->from,
                'status_sesudah' => $request->target,
                'jumlah' => $request->jumlah,
                'catatan' => $request->catatan
            ]);

            // Invalidate catalog cache
            \Illuminate\Support\Facades\Cache::forget('catalog.ready_laptops');

            \App\Support\SecurityLogger::log('stock_transfer', [
                'laptop_id' => $laptop->id,
                'from' => $request->from,
                'to' => $request->target,
                'jumlah' => $request->jumlah,
            ]);

            DB::commit();
            return back()->with('success', 'Stok berhasil dipindahkan & dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
