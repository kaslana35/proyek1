<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Transaction;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_laptop' => Laptop::count(),
            'ready'        => Laptop::where('status', 'ready')->count(),
            'maintenance'  => Laptop::where('status', 'maintenance')->count(),
            'not_ready'    => Laptop::where('status', 'not_ready')->count(),
            'pendapatan'   => Transaction::sum('total_biaya'),
        ];
        
        $pendapatanPerBulan = Transaction::selectRaw('SUM(total_biaya) as total, MONTHNAME(created_at) as bulan')
            ->groupBy('bulan')
            ->orderByRaw('MIN(created_at)')
            ->get();

        return view('dashboard', compact('stats', 'pendapatanPerBulan'));
    }
}
