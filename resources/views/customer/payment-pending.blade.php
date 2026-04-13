@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-20 px-4">
        <div class="max-w-md w-full text-center">
            <div
                class="bg-white p-10 rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-yellow-50 rounded-full opacity-50"></div>

                <div
                    class="relative z-10 w-24 h-24 bg-yellow-400 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-yellow-100 rotate-3 group hover:rotate-0 transition-transform duration-500">
                    <i class="fa-solid fa-hourglass text-4xl text-white"></i>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Pembayaran Sedang Diproses</h1>
                <p class="text-gray-500 leading-relaxed mb-8">
                    Pembayaran untuk pesanan Anda belum selesai. Silakan tunggu beberapa saat dan cek kembali status pesanan
                    Anda.
                </p>

                <div class="bg-gray-50 rounded-3xl p-6 mb-8 border border-gray-100 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-medium">ID Transaksi</span>
                        <span class="text-gray-900 font-bold uppercase">#{{ $order_number }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-medium">Metode Pembayaran</span>
                        <span class="text-gray-900 font-bold uppercase">{{ $payment_method ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-medium">Biaya Pengiriman</span>
                        <span class="text-gray-900 font-bold">Rp{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400 font-medium">Biaya Admin</span>
                        <span class="text-gray-900 font-bold">Rp{{ number_format($order->admin_fee ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                        <span class="text-gray-900 font-bold">Total Bayar</span>
                        <span
                            class="text-indigo-600 font-extrabold text-lg">Rp{{ number_format($order->total_price ?? $total ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-indigo-50 rounded-3xl mb-8 border border-indigo-100 p-6 text-left space-y-3 shadow-sm">
                    <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500 text-left">Estimasi Sampai</p>
                    @if(optional($deliveryEstimate)->hasValue())
                        <p class="text-xl font-black text-gray-900">{{ $deliveryEstimate->formattedDuration() }}</p>
                        <p class="text-sm text-gray-500">
                            Jarak sekitar {{ $deliveryEstimate->formattedDistance() }} dari {{ config('store.address') }}
                        </p>
                        @if($eta = $deliveryEstimate->arrivalAt())
                            <p class="text-xs text-gray-400">Perkiraan tiba {{ $eta->format('d F Y, H:i') }}</p>
                        @endif
                    @elseif($shippingMeta['distance'] ?? null)
                        <p class="text-sm text-gray-500">
                            Jarak sekitar {{ number_format($shippingMeta['distance'], 1, ',', '.') }} km
                        </p>
                    @else
                        <p class="text-sm text-gray-500">Estimasi belum tersedia</p>
                    @endif
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" onclick="retryPayment('{{ $order_number }}')"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300">
                        Lanjutkan Pembayaran
                    </button>
                    <a href="/orders"
                        class="w-full bg-white text-gray-700 py-4 rounded-2xl font-bold border border-gray-200 hover:bg-gray-50 transition-all duration-300">Cek
                        Status Pesanan</a>
                    <a href="/"
                        class="w-full bg-white text-gray-500 py-4 rounded-2xl font-bold border border-gray-200 hover:bg-gray-50 transition-all duration-300">Belanja
                        Lagi</a>
                </div>
            </div>

            <p class="mt-8 text-sm text-gray-400">
                Butuh bantuan? <a href="#" class="text-indigo-600 font-bold hover:underline">Hubungi Bantuan</a>
            </p>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        function retryPayment(orderId) {
            fetch("{{ route('payment.retry', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    window.snap.pay(data.snap_token, {
                        onSuccess: function () { window.location.href = "{{ route('payment.success', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onPending: function () { window.location.href = "{{ route('payment.unfinish', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onError: function () { window.location.href = "{{ route('payment.failure', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); },
                        onClose: function () { window.location.href = "{{ route('payment.unfinish', ['orderId' => 'ORDER_ID']) }}".replace('ORDER_ID', orderId); }
                    });
                })
                .catch(() => alert('Gagal melanjutkan pembayaran.'));
        }
    </script>
@endsection
