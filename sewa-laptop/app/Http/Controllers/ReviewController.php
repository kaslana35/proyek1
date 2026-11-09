<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'laptop_id' => 'required|exists:laptops,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:500',
        ]);

        // Validasi apakah user benar-benar pernah menyewa laptop ini dan statusnya disetujui
        $hasRented = Transaction::where('user_id', auth()->id())
            ->where('laptop_id', $request->laptop_id)
            ->whereIn('status', ['approved', 'active', 'returned'])
            ->exists();

        if (! $hasRented) {
            return back()->withErrors(['error' => 'Anda hanya dapat mengulas laptop yang pernah Anda sewa.']);
        }

        // Cek apakah sudah pernah review sebelumnya
        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'laptop_id' => $request->laptop_id,
            ],
            [
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return back()->with('success', 'Ulasan berhasil dikirimkan!');
    }
}
