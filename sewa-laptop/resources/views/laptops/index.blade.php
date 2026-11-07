@extends('layouts.app')
@section('title', 'Katalog Laptop')
@section('content')
<h1 class="mb-4">Katalog Laptop Tersedia</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @foreach($laptops as $laptop)
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
            @if($laptop->image)
                <img src="{{ asset('storage/' . $laptop->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $laptop->merk }}">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">Tidak Ada Foto</div>
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $laptop->merk }} - {{ $laptop->tipe }}</h5>
                <p class="card-text text-success fw-bold">Rp {{ number_format($laptop->harga_sewa_per_hari, 2) }} / hari</p>
                <a href="{{ route('laptops.show', $laptop->id) }}" class="btn btn-primary mt-auto">Detail & Sewa</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{ $laptops->links() }}
@endsection
