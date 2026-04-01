<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = auth()->id();

        // Verifikasi pengguna telah membeli buku (pembayaran sukses, pesanan tidak batal/gagal)
        $hasPurchased = $item->wasPurchasedBy($userId);

        if (!$hasPurchased) {
            return back()->with('error', 'Anda harus memiliki riwayat pembelian sukses untuk buku ini sebelum dapat memberikan ulasan.');
        }

        // Simpan ulasan. Jika sudah ada ulasan dari user untuk item ini, otomatis ditimpa (update)
        Review::updateOrCreate(
            ['user_id' => $userId, 'item_id' => $item->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Ulasan Anda berhasil disimpan. Terima Kasih!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Tindakan tidak diizinkan.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan Anda berhasil dihapus.');
    }
}
