@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-600 text-white p-6 rounded-lg shadow-md">
        <h5 class="opacity-80">Total Produk</h5>
        <h3 class="text-3xl font-bold">{{ $stats['total_laptop'] }}</h3>
    </div>
    <div class="bg-green-600 text-white p-6 rounded-lg shadow-md">
        <h5 class="opacity-80">Stok Ready</h5>
        <h3 class="text-3xl font-bold">{{ $stats['ready'] }}</h3>
    </div>
    <div class="bg-yellow-500 text-gray-900 p-6 rounded-lg shadow-md">
        <h5 class="opacity-80">Maintenance</h5>
        <h3 class="text-3xl font-bold">{{ $stats['maintenance'] }}</h3>
    </div>
    <div class="bg-indigo-600 text-white p-6 rounded-lg shadow-md">
        <h5 class="opacity-80">Pendapatan</h5>
        <h3 class="text-2xl font-bold">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-4 rounded-lg shadow h-[400px]">
        <h5 class="text-center font-semibold mb-2">Tren Pendapatan</h5>
        <canvas id="pendapatanChart"></canvas>
    </div>
    <div class="bg-white p-4 rounded-lg shadow h-[400px]">
        <h5 class="text-center font-semibold mb-2">Distribusi Status Produk</h5>
        <canvas id="statusChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartOptions = { responsive: true, maintainAspectRatio: false };

    new Chart(document.getElementById('pendapatanChart'), {
        type: 'line',
        data: {
            labels: {!! $pendapatanPerBulan->pluck('bulan') !!},
            datasets: [{ 
                label: 'Pendapatan (Rp)', 
                data: {!! $pendapatanPerBulan->pluck('total') !!}, 
                borderColor: '#2563eb', 
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: ['Ready', 'Maintenance', 'Not Ready'],
            datasets: [{ 
                label: 'Jumlah Produk',
                data: [{{ $stats['ready'] }}, {{ $stats['maintenance'] }}, {{ $stats['not_ready'] }}], 
                backgroundColor: ['#16a34a', '#eab308', '#dc2626'] 
            }]
        },
        options: {
            ...chartOptions,
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endsection
