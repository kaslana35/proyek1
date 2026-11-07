@extends('layouts.app')
@section('title', 'Detail & Sewa Laptop')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        @if($laptop->image)
                            <img src="{{ asset('storage/' . $laptop->image) }}" class="img-fluid rounded" style="height: 250px; object-fit: cover; width: 100%;">
                        @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 250px;">Tidak Ada Foto</div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h3>{{ $laptop->merk }} - {{ $laptop->tipe }}</h3>
                        <p class="text-success fw-bold fs-5">Rp {{ number_format($laptop->harga_sewa_per_hari, 2) }} / hari</p>
                        <p class="badge bg-success">Status: Ready</p>
                        
                        <hr>

                        <form action="{{ route('laptops.sewa', $laptop->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai:</label>
                                <input type="date" name="tanggal_mulai" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai:</label>
                                <input type="date" name="tanggal_selesai" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ajukan Sewa</button>
                        </form>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('laptops.index') }}" class="btn btn-secondary btn-sm">Kembali ke Katalog</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
