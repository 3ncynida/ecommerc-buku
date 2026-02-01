@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-8">
            <h1 class="text-3xl font-bold mb-10">Keranjang Belanja</h1>

            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Produk</th>
                                    <th class="px-6 py-4">Harga</th>
                                    <th class="px-6 py-4">Jumlah</th>
                                    <th class="px-6 py-4">Subtotal</th>
                                    <th class="px-6 py-4"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($cart as $id => $details)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-6 flex items-center space-x-4">
                                            <img src="{{ asset('storage/' . $details['image']) }}"
                                                class="w-16 h-20 object-cover rounded-md shadow-sm">
                                            <span class="font-bold text-gray-800">{{ $details['name'] }}</span>
                                        </td>
                                        <td class="px-6 py-6 text-gray-600">Rp
                                            {{ number_format($details['price'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-6">
                                            <span
                                                class="bg-gray-100 px-3 py-1 rounded-lg text-sm">{{ $details['quantity'] }}</span>
                                        </td>
                                        <td class="px-6 py-6 font-bold text-indigo-600">Rp
                                            {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                        <td class="px-6 py-6">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button class="text-red-400 hover:text-red-600"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-20 text-center text-gray-500 italic">Keranjang Anda masih
                                            kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:w-1/3">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-bold mb-6">Ringkasan Pesanan</h3>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Biaya Pengiriman</span>
                                <span class="text-green-500 font-medium">Gratis</span>
                            </div>
                            <div class="border-t pt-4 flex justify-between text-xl font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('cart.checkout') }}"
                            class="block w-full text-center bg-indigo-600 text-white py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
                            Lanjut ke Pembayaran
                        </a>
                        <a href="/" class="block text-center mt-4 text-sm text-gray-400 hover:text-indigo-600">Lanjut
                            Belanja</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection