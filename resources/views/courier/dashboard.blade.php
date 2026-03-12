@extends('layouts.courier')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Str;

        $courierActionLabels = [
            'diproses_kurir' => 'Kirim Paket',
            'dikirim' => 'Konfirmasi Sampai Tujuan',
            'sampai' => 'Tandai Selesai',
        ];

        $statusLabels = [
            'menunggu_kurir' => 'Menunggu Kurir',
            'diproses_kurir' => 'Diproses Kurir',
            'dikirim' => 'Dalam Pengiriman',
            'sampai' => 'Sampai Tujuan',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal Pengiriman',
        ];

        $statusCounts = [
            'menunggu_kurir' => $availableOrders->count(),
            'diproses_kurir' => $myTasks->where('item_status', 'diproses_kurir')->count(),
            'dikirim' => $myTasks->where('item_status', 'dikirim')->count(),
            'sampai' => $myTasks->where('item_status', 'sampai')->count(),
            'selesai' => $myTasks->where('item_status', 'selesai')->count(),
            'gagal' => $myTasks->where('item_status', 'gagal')->count(),
        ];

        $initialTab = in_array(request('tab'), ['available', 'tasks']) ? request('tab') : 'available';
    @endphp
    <div class="space-y-6">
        <div class="space-y-2">
            <p class="text-xs text-gray-500 uppercase tracking-[0.4em]">Operasional Ekspedisi</p>
            <h1 class="text-3xl font-black text-gray-900">Dashboard Kurir</h1>
            <p class="text-sm text-gray-500 max-w-2xl">Kelola pesanan siap antar dan update status secara real-time langsung dari perangkat mobile Anda.</p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-6">
            @foreach ($statusCounts as $statusKey => $count)
                <div class="rounded-2xl border px-5 py-4 text-sm font-bold uppercase tracking-[0.3em]
                    {{ $statusKey === 'gagal' ? 'bg-rose-50 border-rose-100 text-rose-700' : 'bg-white border-indigo-50 text-gray-600' }}">
                    <p class="text-xs tracking-[0.35em]">{{ $statusLabels[$statusKey] }}</p>
                    <p class="text-3xl font-black text-gray-900 mt-2">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        @if(session('status'))
            <div class="rounded-[30px] border border-indigo-200 bg-indigo-50/80 px-4 py-3 text-sm font-semibold text-indigo-700 shadow-inner">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-[30px] border border-rose-200 bg-rose-50/80 px-4 py-3 text-sm font-semibold text-rose-700 shadow-inner">
                {{ session('error') }}
            </div>
        @endif

        <div x-data="{ tab: '{{ $initialTab }}' }" class="bg-white/80 border border-indigo-100 rounded-[30px] shadow-lg p-4 space-y-4">
            <div class="flex gap-2 overflow-x-auto pb-2">
                <button
                    @click="tab = 'available'"
                    :class="tab === 'available' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-600'"
                    class="flex-1 rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none">
                    <i class="fa-solid fa-inbox mr-2"></i>
                    Tersedia
                </button>
                <button
                    @click="tab = 'tasks'"
                    :class="tab === 'tasks' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-600'"
                    class="flex-1 rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none">
                    <i class="fa-solid fa-truck-fast mr-2"></i>
                    Tugas Saya
                </button>
            </div>

            <div x-show="tab === 'available'" x-cloak class="space-y-4">
                @if($availableOrders->isEmpty())
                    <div class="rounded-[30px] border border-dashed border-indigo-200 p-6 text-center text-sm font-semibold text-gray-500">
                        Tidak ada pesanan yang siap diklaim. Periksa kembali nanti.
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($availableOrders as $order)
                            @php
                                $addressParts = collect([
                                    $order->shippingAddress->full_address ?? null,
                                    $order->shippingAddress->district->name ?? null,
                                    $order->shippingAddress->city->name ?? null,
                                    $order->shippingAddress->province->name ?? null,
                                    $order->shippingAddress->postal_code ?? null,
                                ])->filter()->toArray();
                                $fullAddress = implode(', ', $addressParts);
                                $phone = preg_replace('/[^0-9]/', '', $order->shippingAddress->phone_number ?? '');
                            @endphp
                            <div class="rounded-[30px] bg-white border border-indigo-100 shadow-sm p-5 space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase text-gray-400 tracking-[0.3em]">Order {{ $order->order_number }}</p>
                                        <h2 class="text-2xl font-black text-gray-900">{{ $order->user->name }}</h2>
                                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                                    </div>
                                    <span class="px-4 py-1 rounded-full text-[11px] font-black uppercase tracking-[0.3em] text-indigo-600 border border-indigo-100 bg-indigo-50">
                                        {{ str_replace('_', ' ', strtoupper($order->item_status)) }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-semibold text-gray-800">Alamat Lengkap</p>
                                    <p>{{ $fullAddress ?: 'Alamat belum tersedia' }}</p>
                                    <p class="text-gray-400 text-xs">{{ $order->shippingAddress->recipient_name ?? 'Tidak diketahui' }} · {{ $order->shippingAddress->phone_number ?? '-' }}</p>
                                </div>

                                <div class="grid gap-3 md:grid-cols-3 text-sm text-gray-600">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Item</p>
                                        <p class="font-semibold text-gray-800">{{ $order->item->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-400">{{ $order->quantity }} barang</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Total</p>
                                        <p class="font-semibold text-gray-800">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Tujuan</p>
                                        <p>{{ $order->shippingAddress->city->name ?? $order->shippingAddress->province->name ?? '-' }}</p>
                                    </div>
                                </div>

                                <div class="grid gap-4 md:grid-cols-3 text-sm text-gray-600 mt-6">
                                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/80 p-4 space-y-2">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-indigo-500">Status Barang</p>
                                        <p class="text-lg font-black text-gray-900">
                                            {{ $statusLabels[$order->item_status] ?? 'Menunggu Kurir' }}
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($statusLabels as $statusKey => $statusLabel)
                                                <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em]
                                                    {{ $order->item_status === $statusKey ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white text-gray-500 border-gray-100' }}">
                                                    {{ Str::upper($statusLabel) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="rounded-2xl border border-gray-100 p-4 space-y-2">
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Catatan & Gagal</p>
                                        <p class="text-sm text-gray-700">{{ $order->note ?? 'Tidak ada catatan tambahan.' }}</p>
                                        @if ($order->item_status === 'gagal')
                                            <div class="text-xs font-bold uppercase tracking-[0.35em] text-rose-600 bg-rose-50 border border-rose-100 rounded-2xl px-3 py-2">
                                                Gagal: {{ Str::limit($order->note ?? 'Kendala tidak dijelaskan', 60) }}
                                            </div>
                                        @else
                                            <div class="text-xs font-bold uppercase tracking-[0.35em] text-emerald-600 bg-emerald-50 border border-emerald-100 rounded-2xl px-3 py-2">
                                                Tidak ada kendala, lanjutkan pengiriman.
                                            </div>
                                        @endif
                                    </div>
                                    <div class="rounded-2xl border border-gray-100 p-4 space-y-2">
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Gambar Produk</p>
                                        <div class="h-28 rounded-2xl border border-gray-200 bg-gray-50 overflow-hidden">
                                            @if ($order->item && $order->item->image)
                                                <img src="{{ asset('storage/' . $order->item->image) }}"
                                                     alt="{{ $order->item->name }}"
                                                     class="w-full h-full object-cover object-center">
                                            @else
                                                <div class="flex h-full items-center justify-center text-[11px] text-gray-400 uppercase tracking-[0.3em]">
                                                    Gambar belum tersedia
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="https://wa.me/{{ $phone }}?text={{ urlencode('Halo, saya kurir Libris. Saya ingin mengonfirmasi pesanan ' . $order->order_number) }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-green-100 bg-green-50 text-green-700 text-sm font-semibold hover:bg-green-100 transition">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        Kirim WhatsApp
                                    </a>
                                    <form action="{{ route('courier.orders.claim', $order) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full px-4 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                            <i class="fa-solid fa-handshake-angle mr-2"></i>
                                            Klaim & Proses
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="tab === 'tasks'" x-cloak class="space-y-4">
                @if($myTasks->isEmpty())
                    <div class="rounded-[30px] border border-dashed border-indigo-200 p-6 text-center text-sm font-semibold text-gray-500">
                        Belum ada penugasan aktif. Silakan klaim pesanan pertama yang muncul di tab “Tersedia”.
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($myTasks as $task)
                            @php
                                $statusBadge = ucfirst(str_replace('_', ' ', $task->item_status));
                                $nextStatus = $courierActionLabels[$task->item_status] ?? null;
                                $phone = preg_replace('/[^0-9]/', '', $task->shippingAddress->phone_number ?? '');
                                $addressParts = collect([
                                    $task->shippingAddress->full_address ?? null,
                                    $task->shippingAddress->district->name ?? null,
                                    $task->shippingAddress->city->name ?? null,
                                    $task->shippingAddress->province->name ?? null,
                                    $task->shippingAddress->postal_code ?? null,
                                ])->filter()->toArray();
                                $fullAddress = implode(', ', $addressParts);
                            @endphp
                            <div class="rounded-[30px] border border-indigo-100 shadow-xl p-5 space-y-4" data-order-id="{{ $task->id }}">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase text-gray-400 tracking-[0.3em]">Task #{{ $task->order_number }}</p>
                                        <h3 class="text-xl font-black text-gray-900">{{ $task->user->name }}</h3>
                                        <p class="text-xs text-gray-500">{{ $task->user->email }}</p>
                                    </div>
                                    <span id="order-status-{{ $task->id }}" class="px-3 py-1 rounded-full border border-indigo-200 text-[11px] font-black uppercase tracking-[0.3em] bg-indigo-50 text-indigo-600">
                                        {{ $statusBadge }}
                                    </span>
                                </div>

                                <div class="text-sm text-gray-600 space-y-1">
                                    <p class="font-semibold text-gray-800">Alamat Tujuan</p>
                                    <p>{{ $fullAddress ?: 'Alamat belum tersedia' }}</p>
                                    <p class="text-gray-400 text-xs">{{ $task->shippingAddress->recipient_name ?? '-' }} · {{ $task->shippingAddress->phone_number ?? '-' }}</p>
                                </div>

                                <div class="grid gap-4 md:grid-cols-3 text-sm text-gray-600">
                                    <div class="rounded-2xl border border-gray-100 p-4 space-y-2">
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Total Tagihan</p>
                                        <p class="text-lg font-black text-gray-900">Rp{{ number_format($task->total_price, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400 uppercase tracking-[0.3em]">Kuantitas</p>
                                        <p class="font-bold text-gray-800">{{ $task->quantity }} barang</p>
                                    </div>
                                    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/80 p-4 space-y-2">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-indigo-500">Status Barang</p>
                                        <p class="text-lg font-black text-gray-900">{{ $statusLabels[$task->item_status] ?? 'Menunggu Kurir' }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($statusLabels as $statusKey => $statusLabel)
                                                <span class="rounded-full border px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em]
                                                    {{ $task->item_status === $statusKey ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white text-gray-500 border-gray-100' }}">
                                                    {{ Str::upper($statusLabel) }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Catatan Kurir</p>
                                        <p class="text-sm text-gray-500">{{ $task->courier_note ?? 'Tidak ada catatan' }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-gray-100 p-4 space-y-3">
                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Kolom Gagal</p>
                                        @if ($task->item_status === 'gagal')
                                            <p class="text-sm font-bold text-rose-600">Pengiriman gagal</p>
                                            <p class="text-xs text-rose-500">{{ $task->courier_note ?? 'Laporan belum tersedia' }}</p>
                                        @else
                                            <p class="text-sm font-bold text-emerald-600">Tidak ada kegagalan</p>
                                            <p class="text-xs text-gray-500">Pantauan normal, lanjutkan ke bukti pengiriman.</p>
                                        @endif

                                        <p class="text-[10px] uppercase tracking-[0.3em] text-gray-500">Bukti Pengiriman</p>
                                        @if ($task->delivery_proof_path)
                                            <a href="{{ Storage::url($task->delivery_proof_path) }}" target="_blank" class="block rounded-2xl overflow-hidden border border-gray-200 bg-gray-100">
                                                <img src="{{ Storage::url($task->delivery_proof_path) }}" alt="Bukti {{ $task->order_number }}" class="w-full h-28 object-cover object-center">
                                            </a>
                                            <p class="text-[10px] text-gray-400">Diupload {{ $task->updated_at->format('d M Y, H:i') }}</p>
                                            <p class="text-sm text-gray-600">{{ $task->courier_note ?? 'Tidak ada catatan tambahan.' }}</p>
                                        @else
                                            <p class="text-sm text-gray-500">Belum ada bukti foto.</p>
                                        @endif

                                        @if ($task->item_status === 'dikirim')
                                            <form action="{{ route('courier.orders.proof', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                                @csrf
                                                <label class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Unggah Foto</label>
                                                <input type="file" name="proof_image" accept="image/*" class="w-full text-sm text-gray-500" required>
                                                <textarea name="note" rows="2" class="w-full border border-gray-200 rounded-2xl p-2 text-sm" placeholder="Catatan singkat (opsional)"></textarea>
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-indigo-600 text-white text-sm font-bold">
                                                    <i class="fa-solid fa-camera-retro"></i>
                                                    Upload & Tandai Sampai
                                                </button>
                                            </form>
                                        @endif

                                        @if ($task->item_status !== 'gagal')
                                            <form action="{{ route('courier.orders.failure', $task) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <label class="text-[10px] uppercase tracking-[0.3em] text-gray-400">Laporkan Gagal Pengiriman</label>
                                                <textarea name="failure_note" rows="2" class="w-full border border-gray-200 rounded-2xl p-2 text-sm" placeholder="Jelaskan hambatan atau alasan gagal" required></textarea>
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-rose-600 text-white text-sm font-bold hover:bg-rose-700 transition">
                                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                                    Laporkan Gagal
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="https://wa.me/{{ $phone }}?text={{ urlencode('Halo, saya kurir Libris. Saya sedang dalam perjalanan ke pesanan ' . $task->order_number) }}"
                                       target="_blank"
                                       rel="noopener"
                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-green-100 bg-green-50 text-green-700 text-sm font-semibold hover:bg-green-100 transition">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        Kirim WhatsApp
                                    </a>
                                    @if($task->item_status === 'diproses_kurir')
                                        @if($nextStatus)
                                            <button type="button"
                                                data-update-status
                                                data-url="{{ route('courier.orders.status', $task) }}"
                                                data-current-status="{{ $task->item_status }}"
                                                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                                <i class="fa-solid fa-rotate-right"></i>
                                                {{ $nextStatus }}
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const actionLabels = @json($courierActionLabels);

            document.querySelectorAll('[data-update-status]').forEach(button => {
                button.addEventListener('click', async function () {
                    const url = this.dataset.url;
                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                        },
                    });

                    if (!response.ok) {
                        const error = await response.json().catch(() => null);
                        alert(error?.message ?? 'Gagal memperbarui status.');
                        return;
                    }

                    const data = await response.json();
                    const orderId = this.closest('[data-order-id]').dataset.orderId;
                    const statusEl = document.getElementById(`order-status-${orderId}`);
                    if (statusEl) {
                        statusEl.textContent = data.nextStatus?.replace(/_/g, ' ').toUpperCase() ?? statusEl.textContent;
                    }

                    const nextLabel = actionLabels[data.nextStatus];
                    if (data.nextStatus === 'dikirim') {
                        window.location.reload();
                        return;
                    }
                    if (nextLabel) {
                        button.innerHTML = `<i class="fa-solid fa-rotate-right"></i> ${nextLabel}`;
                    } else {
                        button.disabled = true;
                        button.innerHTML = `<i class="fa-solid fa-check-circle"></i> Selesai`;
                        button.classList.remove('bg-indigo-600');
                        button.classList.add('bg-gray-200');
                    }
                });
            });
        });
    </script>
@endpush

@endsection
