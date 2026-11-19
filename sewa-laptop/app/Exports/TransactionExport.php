<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TransactionExport implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return Transaction::with(['laptop', 'user']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Laptop',
            'Penyewa',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Total Biaya',
            'Status',
            'Dibuat',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->laptop ? $transaction->laptop->merk . ' ' . $transaction->laptop->tipe : '-',
            $transaction->user ? $transaction->user->name : $transaction->nama_peminjam,
            $transaction->tanggal_mulai,
            $transaction->tanggal_selesai,
            $transaction->total_biaya,
            $transaction->status,
            $transaction->created_at,
        ];
    }
}