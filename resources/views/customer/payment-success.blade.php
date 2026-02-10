@extends('customer.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full text-center">
        {{-- Kartu Utama --}}
        <div class="bg-white p-10 rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50 relative overflow-hidden">
            
            {{-- Dekorasi Latar Belakang --}}
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-green-50 rounded-full opacity-50"></div>

            {{-- Ikon Centang Animasi --}}
            <div class="relative z-10 w-24 h-24 bg-green-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-green-100 rotate-3 group hover:rotate-0 transition-transform duration-500">
                <i class="fa-solid fa-check text-4xl text-white"></i>
            </div>

            {{-- Teks Informasi --}}
            <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Pembayaran Berhasil!</h1>
            <p class="text-gray-500 leading-relaxed mb-8">
                Terima kasih, pesanan Anda telah kami terima. Kami akan segera memproses buku favorit Anda untuk segera dikirim ke alamat tujuan.
            </p>

            {{-- Detail Singkat Transaksi --}}
            <div class="bg-gray-50 rounded-3xl p-6 mb-8 border border-gray-100 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">ID Transaksi</span>
                    <span class="text-gray-900 font-bold uppercase">#LIB-9928172</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-400 font-medium">Metode Pembayaran</span>
                    <span class="text-gray-900 font-bold uppercase">Transfer Bank</span>
                </div>
                <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                    <span class="text-gray-900 font-bold">Total Bayar</span>
                    <span class="text-indigo-600 font-extrabold text-lg">Rp{{ number_format($total ?? 165000, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tombol Navigasi --}}
            <div class="flex flex-col gap-3">
                <a href="/orders" 
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300">
                    Cek Status Pesanan
                </a>
                <a href="/" 
                    class="w-full bg-white text-gray-500 py-4 rounded-2xl font-bold border border-gray-200 hover:bg-gray-50 transition-all duration-300">
                    Belanja Lagi
                </a>
            </div>
        </div>

        {{-- Bantuan --}}
        <p class="mt-8 text-sm text-gray-400">
            Punya kendala dengan pesanan Anda? 
            <a href="#" class="text-indigo-600 font-bold hover:underline">Hubungi Bantuan</a>
        </p>
    </div>
</div>
@endsection