@extends('customer.layouts.app')

@section('content')
    @php
        $failureReason = $payment->raw_response['status_message']
            ?? $payment->raw_response['transaction_status']
            ?? 'Pembayaran ditolak, dibatalkan, atau kedaluwarsa.';
    @endphp
    <div class="bg-gray-50 min-h-screen flex items-center justify-center py-20 px-4">
        <div class="max-w-md w-full text-center">
            <div
                class="bg-white p-10 rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-50 relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-red-50 rounded-full opacity-50"></div>
                <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-pink-50 rounded-full opacity-50"></div>

                <div
                    class="relative z-10 w-24 h-24 bg-red-500 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg shadow-red-100 rotate-3 group hover:rotate-0 transition-transform duration-500">
                    <i class="fa-solid fa-xmark text-4xl text-white"></i>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 mb-3">Pembayaran Gagal</h1>
                <p class="text-gray-500 leading-relaxed mb-8">
                    Maaf, pembayaran untuk pesanan ini gagal atau dibatalkan. Anda dapat mencoba melakukan pembayaran
                    kembali atau hubungi dukungan.
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
                    <div class="border-t border-dashed border-gray-200 pt-3 flex justify-between items-center">
                        <span class="text-gray-900 font-bold">Total Bayar</span>
                        <span
                            class="text-indigo-600 font-extrabold text-lg">Rp{{ number_format($total ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-dashed border-gray-200 pt-3 text-left">
                        <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Alasan Gagal</div>
                        <div class="text-sm text-rose-600 font-medium">{{ $failureReason }}</div>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="button" onclick="retryPayment('{{ $order_number }}')"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-100 transition-all duration-300">
                        Coba Bayar Lagi
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
                Ingin bantuan lebih lanjut? <a href="#" class="text-indigo-600 font-bold hover:underline">Hubungi
                    Bantuan</a>
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
                .catch(() => alert('Gagal memulai pembayaran ulang.'));
        }
    </script>
@endsection
