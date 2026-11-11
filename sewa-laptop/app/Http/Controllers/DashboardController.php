<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_laptop'   => Laptop::count(),
            'pending_trx'    => Transaction::where('status', 'pending')->count(),
            'maintenance'    => Laptop::sum('maintenance_stok'),
            'pendapatan'     => Transaction::whereIn('status', ['approved', 'returned'])->sum('total_biaya'),
        ];

        $lowStockLaptops = Laptop::where('stok', '<=', 1)->get();
        $recentInspections = \App\Models\UnitInspection::with('laptop')->latest()->take(5)->get();
        
        $pendapatanPerBulan = Transaction::selectRaw('SUM(total_biaya) as total, MONTHNAME(created_at) as bulan')
            ->whereIn('status', ['approved', 'returned'])
            ->groupBy('bulan')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return view('dashboard', compact('stats', 'pendapatanPerBulan', 'lowStockLaptops', 'recentInspections'));
    }
}
