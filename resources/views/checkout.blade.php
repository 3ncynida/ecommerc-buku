<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script type="text/javascript"
      src="https://app.sandbox.midtrans.com/snap/snap.js"
      data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="text-muted">Item ID: #{{ $item->id }}</p>
                        <h3 class="text-primary mb-4">Rp {{ number_format($item->price, 0, ',', '.') }}</h3>
                        
                        <button class="btn btn-primary w-100" id="pay-button">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script type="text/javascript">
        $('#pay-button').click(function (event) {
            event.preventDefault();

            // Panggil backend untuk buat transaksi & dapat Snap Token
            $.post("{{ url('/api/payment/create') }}", {
                _token: '{{ csrf_token() }}',
                item_id: "{{ $item->id }}",
                amount: "{{ $item->price }}"
            }, function (data) {
                // Munculkan popup Snap Midtrans
                window.snap.pay(data.snap_token, {
                    onSuccess: function (result) {
                        alert("Pembayaran berhasil!");
                        console.log(result);
                        location.reload();
                    },
                    onPending: function (result) {
                        alert("Menunggu pembayaran Anda...");
                        console.log(result);
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                        console.log(result);
                    },
                    onClose: function () {
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                    }
                });
            });
        });
    </script>
</body>
</html>