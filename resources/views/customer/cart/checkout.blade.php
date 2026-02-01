@extends('customer.layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-8">
        <h2 class="text-2xl font-bold mb-6">Konfirmasi Pembayaran</h2>

        <div class="bg-white p-8 rounded-2xl shadow-sm border">
            <div class="mb-6">
                <label class="block font-medium">Nama Lengkap</label>
                <input type="text" id="name" class="w-full border p-3 rounded-xl mt-1">
            </div>
            <div class="mb-6">
                <label class="block font-medium">Email</label>
                <input type="email" id="email" class="w-full border p-3 rounded-xl mt-1">
            </div>

            <button id="pay-button" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold">
                Bayar Sekarang
            </button>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Ambil token dari controller lewat AJAX
            // Di checkout.blade.php
            fetch("{{ route('payment.create') }}", { // Gunakan helper route agar otomatis
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}", // WAJIB untuk rute web
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    // Jika kamu tidak menggunakan session untuk item_id, kirim di sini:
                    // item_id: ...
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function (result) { window.location.href = '/success'; },
                            onPending: function (result) { alert("Menunggu pembayaran..."); },
                            onError: function (result) { alert("Pembayaran gagal!"); }
                        });
                    }
                });
        });
    </script>
@endsection
