@extends('customer.layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Data Pengiriman</h1>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
            <form action="{{ route('cart.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: John Doe" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-700">Nomor WhatsApp</label>
                        <input type="number" name="phone" required placeholder="08123456789" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="font-semibold text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="3" required placeholder="Jl. Merdeka No. 123, Jakarta" 
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"></textarea>
                    </div>
                </div>

                <div class="mt-8 border-t pt-8">
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-gray-500 italic">Total yang akan dibayarkan:</span>
                        <span class="text-2xl font-bold text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <button type="submit" class="w-full bg-green-500 text-white py-4 rounded-xl font-bold text-lg hover:bg-green-600 transition flex items-center justify-center shadow-lg shadow-green-100">
                        <i class="fa-brands fa-whatsapp text-2xl mr-3"></i> Pesan via WhatsApp
                    </button>
                    <p class="text-center text-gray-400 text-xs mt-4 italic">
                        Klik tombol di atas untuk mengirim rincian pesanan ke admin kami.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection