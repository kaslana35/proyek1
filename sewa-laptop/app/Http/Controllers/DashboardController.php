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
            'pendapatan'     => Transaction::where('status', 'approved')->sum('total_biaya'),
        ];
        
        $pendapatanPerBulan = Transaction::selectRaw('SUM(total_biaya) as total, MONTHNAME(created_at) as bulan')
            ->groupBy('bulan')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return view('dashboard', compact('stats', 'pendapatanPerBulan'));
    }
}
