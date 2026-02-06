@extends('customer.layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto py-12 px-6">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- LEFT: Address + Order Items (col-span 2) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Address Card -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Alamat Pengiriman</h3>
                        @if(auth()->user()->addresses->count() > 0)
                            {{-- Tombol untuk membuka pilihan alamat --}}
                            <button type="button"
                                onclick="document.getElementById('modalPilihAlamat').classList.remove('hidden')"
                                class="text-blue-600 text-sm font-medium hover:underline">
                                Pilih Alamat Lain
                            </button>
                        @endif
                    </div>

                    @php
                        // Menggunakan is_default sesuai skema kamu
                        $address = auth()->user()->addresses()->where('is_default', true)->first() ?? auth()->user()->addresses()->first();
                    @endphp

                    @if($address)
                        <div id="selected-address-display" class="border rounded-xl p-4 bg-gray-50/50">
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="text-xs font-bold uppercase px-2 py-0.5 bg-gray-200 text-gray-600 rounded">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span
                                        class="text-xs font-bold uppercase px-2 py-0.5 bg-green-100 text-green-600 rounded">Utama</span>
                                @endif
                            </div>

                            <div class="font-bold text-gray-900">{{ $address->recipient_name }}</div>
                            <div class="text-sm text-gray-600 mt-1">{{ $address->phone_number }}</div>
                            <div class="text-sm text-gray-500 mt-2">
                                {{ $address->full_address }}<br>
                                {{-- Asumsi kamu sudah membuat relasi di model Address ke tabel Laravolt --}}
                                {{ $address->district->name ?? '' }}, {{ $address->city->name ?? '' }},
                                {{ $address->province->name ?? '' }}, {{ $address->postal_code }}
                            </div>

                            <input type="hidden" name="address_id" id="input_address_id" value="{{ $address->id }}">
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-sm text-gray-500 mb-4">Belum ada alamat yang terdaftar.</div>
                            <a href="/profile"
                                class="inline-block bg-blue-600 text-white rounded-full px-6 py-2 text-sm font-medium">
                                + Tambah Alamat Baru
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Order Items -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <h3 class="font-semibold text-lg mb-4">Pesanan</h3>

                    @php
                        $cart = session('cart', []);
                        $total = 0;
                    @endphp

                    @forelse($cart as $id => $item)
                        @php $lineTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                        $total += $lineTotal; @endphp
                        <div class="flex items-center gap-4 py-4 border-b last:border-b-0">
                            <img src="{{ $item['image'] ?? asset('storage/placeholder.png') }}"
                                alt="{{ $item['name'] ?? 'Item' }}" class="w-16 h-20 object-cover rounded-md">
                            <div class="flex-1">
                                <div class="font-medium">{{ $item['name'] ?? 'Produk' }}</div>
                                <div class="text-sm text-gray-500">{{ $item['quantity'] ?? 1 }} barang</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">Rp{{ number_format($lineTotal, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500">Keranjang kosong.</div>
                    @endforelse

                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600">Total Pesanan</div>
                        <div class="font-bold">Rp{{ number_format($total, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Payment + Summary -->
            <div class="space-y-6">

                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <h3 class="font-semibold text-lg mb-3">Voucher</h3>
                    <div class="flex gap-3">
                        <input id="voucher" type="text" placeholder="Gunakan Voucher" class="flex-1 border rounded-xl p-3">
                        <button id="apply-voucher" class="bg-indigo-600 text-white px-4 rounded-xl">Gunakan</button>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border">
                    <h3 class="font-semibold text-lg mb-3">Ringkasan Belanja</h3>
                    <div class="text-sm text-gray-500 mb-3">Detail biaya pesanan</div>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <div>Total Harga ({{ count($cart) }} Barang)</div>
                            <div>Rp{{ number_format($total, 0, ',', '.') }}</div>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <div>Total Biaya Pengiriman</div>
                            <div>Rp0</div>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <div>Diskon Belanja</div>
                            <div>-Rp0</div>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <div>Voucher</div>
                            <div>-Rp0</div>
                        </div>
                        <div class="flex justify-between text-red-500">
                            <div>Voucher Ongkir</div>
                            <div>-Rp0</div>
                        </div>
                    </div>

                    <div class="mt-4 border-t pt-4 flex justify-between items-center">
                        <div class="text-lg font-semibold">Total Belanja</div>
                        <div class="text-xl font-bold">Rp{{ number_format($total, 0, ',', '.') }}</div>
                    </div>

                    <button id="pay-button"
                        class="mt-4 w-full bg-indigo-600 text-white py-3 rounded-xl font-bold disabled:opacity-50" {{ $total <= 0 ? 'disabled' : '' }}>
                        Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    <script>
        (function () {
            const payButton = document.getElementById('pay-button');
            payButton && payButton.addEventListener('click', function (e) {
                e.preventDefault();

                const name = '{{ auth()->check() ? auth()->user()->name : "" }}';
                const email = '{{ auth()->check() ? auth()->user()->email : "" }}';

                fetch("{{ route('payment.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }
                        if (data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function (result) { window.location.href = "{{ route('payment.success') }}"; },
                                onPending: function (result) { alert('Menunggu pembayaran...'); },
                                onError: function (result) { alert('Pembayaran gagal'); }
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan. Cek console.');
                    });
            });
        })();
    </script>
    <div id="modalPilihAlamat" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[80vh] overflow-hidden flex flex-col shadow-xl">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-bold">Pilih Alamat Pengiriman</h3>
            <button onclick="document.getElementById('modalPilihAlamat').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto space-y-4">
            @foreach(auth()->user()->addresses as $addr)
                <div onclick="selectAddress({{ json_encode($addr) }}, '{{ $addr->district->name ?? '' }}, {{ $addr->city->name ?? '' }}')" 
                     class="border-2 rounded-xl p-4 cursor-pointer hover:border-blue-500 transition-all {{ $addr->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-100' }}">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-bold uppercase px-2 py-0.5 bg-gray-200 rounded">{{ $addr->label }}</span>
                    </div>
                    <div class="font-bold">{{ $addr->recipient_name }}</div>
                    <div class="text-sm text-gray-600">{{ $addr->phone_number }}</div>
                    <div class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $addr->full_address }}</div>
                </div>
            @endforeach
        </div>

        <div class="p-6 border-t">
            <a href="/profile" class="block text-center w-full py-3 border-2 border-dashed border-gray-300 rounded-xl text-gray-500 hover:bg-gray-50 font-medium">
                + Tambah Alamat Baru
            </a>
        </div>
    </div>
</div>

<script>
function selectAddress(addr, regionInfo) {
    // Update display di halaman checkout
    const display = document.getElementById('selected-address-display');
    display.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-bold uppercase px-2 py-0.5 bg-gray-200 text-gray-600 rounded">${addr.label}</span>
        </div>
        <div class="font-bold text-gray-900">${addr.recipient_name}</div>
        <div class="text-sm text-gray-600 mt-1">${addr.phone_number}</div>
        <div class="text-sm text-gray-500 mt-2">${addr.full_address}<br>${regionInfo}, ${addr.postal_code}</div>
        <input type="hidden" name="address_id" value="${addr.id}">
    `;
    
    // Update hidden input
    document.getElementById('input_address_id').value = addr.id;
    
    // Tutup modal
    document.getElementById('modalPilihAlamat').classList.add('hidden');
}
</script>
@endsection