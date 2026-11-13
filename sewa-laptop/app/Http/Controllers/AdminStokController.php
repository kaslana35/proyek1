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
            'qty' => 'required|integer|min:1',
            'to'   => 'required|in:ready,maintenance,not_ready',
            'from'   => 'required|in:ready,maintenance,not_ready',
            'catatan' => 'required|string|max:255'
        ]);

        if ($request->from === $request->to) {
            return back()->withErrors(['error' => 'Sumber dan target tidak boleh sama.']);
        }

        DB::beginTransaction();
        try {
            $sources = ['ready' => 'stok', 'maintenance' => 'maintenance_stok', 'not_ready' => 'not_ready_stok'];
            
            $srcCol = $sources[$request->from];
            $tgtCol = $sources[$request->to];

            if ($laptop->$srcCol < $request->qty) {
                throw new \Exception("Stok di " . ucfirst($request->from) . " tidak mencukupi.");
            }

            $laptop->decrement($srcCol, $request->qty);
            $laptop->increment($tgtCol, $request->qty);
            
            UnitInspection::create([
                'laptop_id' => $laptop->id,
                'status_sebelum' => $request->from,
                'status_sesudah' => $request->to,
                'jumlah' => $request->qty,
                'catatan' => $request->catatan
            ]);

            DB::commit();
            return back()->with('success', 'Stok berhasil dipindahkan & dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
