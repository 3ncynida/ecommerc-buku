@extends('layouts.courier')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        use Illuminate\Support\Str;

        $courierActionLabels = [
            'diproses_kurir' => 'Mulai Kirim Paket',
            'dikirim' => 'Konfirmasi Sampai',
            'sampai' => 'Tandai Selesai',
        ];

        $statusLabels = [
            'menunggu_kurir' => 'Menunggu',
            'diproses_kurir' => 'Diproses',
            'dikirim' => 'Dikirim',
            'sampai' => 'Sampai',
            'selesai' => 'Selesai',
            'gagal' => 'Gagal',
        ];

        $statusCounts = [
            'menunggu_kurir' => $availableOrders->count(),
            'diproses_kurir' => $myTasks->where('item_status', 'diproses_kurir')->count(),
            'dikirim' => $myTasks->where('item_status', 'dikirim')->count(),
            'sampai' => $myTasks->where('item_status', 'sampai')->count(),
            'gagal' => $myTasks->where('item_status', 'gagal')->count(),
        ];

        $initialTab = in_array(request('tab'), ['available', 'tasks']) ? request('tab') : 'available';
    @endphp

    <div class="max-w-2xl mx-auto pb-24 px-4 sm:px-6 mt-6">

        {{-- Header Minimalis --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard Kurir</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola dan update pengiriman Anda hari ini.</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">
                {{ session('status') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Statistik Grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mb-8">
            @foreach ($statusCounts as $statusKey => $count)
                <div class="rounded-xl border border-gray-100 bg-white p-3 text-center shadow-sm">
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 mb-1 truncate">{{ $statusLabels[$statusKey] }}</p>
                    <p class="text-xl font-bold {{ $statusKey === 'gagal' ? 'text-red-600' : 'text-gray-900' }}">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        <div x-data="{ tab: '{{ $initialTab }}' }">

            {{-- Tabs Segmen Minimalis --}}
            <div class="flex p-1 bg-gray-100 rounded-xl mb-6">
                <button @click="tab = 'available'"
                    :class="tab === 'available' ? 'bg-white shadow-sm text-black font-semibold' : 'text-gray-500 font-medium hover:text-gray-700'"
                    class="flex-1 py-2.5 text-sm rounded-lg transition-all">
                    Tersedia
                </button>
                <button @click="tab = 'tasks'"
                    :class="tab === 'tasks' ? 'bg-white shadow-sm text-black font-semibold' : 'text-gray-500 font-medium hover:text-gray-700'"
                    class="flex-1 py-2.5 text-sm rounded-lg transition-all relative">
                    Tugas Saya
                    @if($myTasks->count() > 0)
                        <span class="absolute top-2.5 right-4 w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                </button>
            </div>

            {{-- TAB: TERSEDIA --}}
            <div x-show="tab === 'available'" x-cloak class="space-y-3">
                @if($availableOrders->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 py-12 text-center">
                        <i class="fa-solid fa-box-open text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Tidak ada paket baru saat ini.</p>
                    </div>
                @else
                    @foreach($availableOrders as $order)
                        @php
                            $fullAddress = collect([$order->shippingAddress->full_address ?? null, $order->shippingAddress->district->name ?? null, $order->shippingAddress->city->name ?? null])->filter()->implode(', ');
                        @endphp

                        {{-- Wrapper Card dengan state 'expanded' --}}
                        <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                            {{-- Header Card yang bisa di-klik --}}
                            <div @click="expanded = !expanded" class="p-5 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition-colors select-none">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Order #{{ $order->order_number }}</span>
                                    <h3 class="font-bold text-lg text-gray-900 mt-0.5">{{ $order->user->name }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider">
                                        {{ $statusLabels[$order->item_status] ?? 'Baru' }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-sm transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                                </div>
                            </div>

                            {{-- Body Card (Sembunyi by default) --}}
                            <div x-show="expanded" x-collapse x-cloak>
                                <div class="px-5 pb-5 border-t border-gray-50 pt-4">
                                    <div class="text-sm text-gray-600 mb-5">
                                        <p class="flex items-start gap-2 mb-2">
                                            <i class="fa-solid fa-location-dot mt-1 text-gray-300 w-4"></i>
                                            <span>{{ $fullAddress ?: 'Alamat tidak lengkap' }}</span>
                                        </p>
                                        <p class="flex items-start gap-2">
                                            <i class="fa-solid fa-box mt-1 text-gray-300 w-4"></i>
                                            <span>{{ $order->quantity }}x {{ $order->item->name ?? 'Produk' }}</span>
                                        </p>
                                    </div>

                                    <form action="{{ route('courier.orders.claim', $order) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="w-full bg-black text-white py-3.5 rounded-xl font-semibold text-sm hover:bg-gray-800 transition active:scale-[0.98]">
                                            Klaim Paket Ini
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- TAB: TUGAS SAYA --}}
            <div x-show="tab === 'tasks'" x-cloak class="space-y-3">
                @if($myTasks->isEmpty())
                    <div class="rounded-2xl border border-dashed border-gray-300 py-12 text-center">
                        <i class="fa-solid fa-truck-fast text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-500">Anda belum mengambil tugas pengiriman.</p>
                    </div>
                @else
                    @foreach($myTasks as $task)
                        @php
                            $statusBadge = $statusLabels[$task->item_status] ?? 'Diproses';
                            $nextStatus = $courierActionLabels[$task->item_status] ?? null;
                            $phone = preg_replace('/[^0-9]/', '', $task->shippingAddress->phone_number ?? '');
                            $fullAddress = collect([$task->shippingAddress->full_address ?? null, $task->shippingAddress->district->name ?? null, $task->shippingAddress->city->name ?? null, $task->shippingAddress->province->name ?? null])->filter()->implode(', ');
                        @endphp

                        {{-- Wrapper Card dengan state 'expanded' --}}
                        <div x-data="{ expanded: false }" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" data-order-id="{{ $task->id }}">

                            {{-- Card Header (Klik untuk buka/tutup) --}}
                            <div @click="expanded = !expanded" class="p-5 bg-gray-50/50 flex justify-between items-center cursor-pointer hover:bg-gray-100 transition-colors select-none">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest text-gray-500">ID: {{ $task->order_number }}</p>
                                    <h3 class="font-bold text-gray-900 mt-0.5">{{ $task->shippingAddress->recipient_name ?? $task->user->name }}</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span id="order-status-{{ $task->id }}" class="bg-black text-white px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest">
                                        {{ $statusBadge }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-gray-400 text-sm transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                                </div>
                            </div>

                            {{-- Card Body (Sembunyi by default) --}}
                            <div x-show="expanded" x-collapse x-cloak>
                                <div class="p-5 border-t border-gray-100">
                                    <div class="mb-5">
                                        <p class="text-xs font-semibold text-gray-900 mb-1">Tujuan Pengiriman</p>
                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $fullAddress }}</p>
                                        <p class="text-sm font-medium text-gray-800 mt-2"><i class="fa-solid fa-phone text-xs text-gray-400 mr-1"></i> {{ $task->shippingAddress->phone_number ?? '-' }}</p>
                                    </div>

                                    @if($task->note)
                                        <div class="mb-5 p-3 bg-yellow-50 border border-yellow-100 rounded-lg text-sm text-yellow-800">
                                            <span class="font-bold text-[10px] uppercase tracking-wider block mb-1">Catatan Pembeli:</span>
                                            {{ $task->note }}
                                        </div>
                                    @endif

                                    {{-- Aksi Berdasarkan Status --}}
                                    <div class="space-y-3 mt-6 border-t border-gray-50 pt-5">

                                        {{-- Tombol WhatsApp --}}
                                        <a href="https://wa.me/{{ $phone }}" target="_blank"
                                            class="w-full flex justify-center items-center gap-2 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition active:bg-gray-100">
                                            <i class="fa-brands fa-whatsapp text-green-500 text-lg"></i> Hubungi Pembeli
                                        </a>

                                        {{-- JIKA STATUS: DIPROSES KURIR (Siap jalan) --}}
                                        @if($task->item_status === 'diproses_kurir' && $nextStatus)
                                            <button type="button" data-update-status data-url="{{ route('courier.orders.status', $task) }}"
                                                class="w-full py-3.5 rounded-xl bg-black text-white font-semibold text-sm hover:bg-gray-800 transition active:scale-[0.98]">
                                                {{ $nextStatus }}
                                            </button>
                                        @endif

                                        {{-- JIKA STATUS: DIKIRIM (Sedang di jalan, butuh bukti foto) --}}
                                        @if($task->item_status === 'dikirim')
                                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mt-4">
                                                <p class="text-xs font-bold mb-3 uppercase tracking-widest text-gray-500">Selesaikan Pengiriman</p>
                                                <form action="{{ route('courier.orders.proof', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                    @csrf
                                                    <div>
                                                        <input type="file" name="proof_image" accept="image/*" required
                                                            class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800">
                                                    </div>
                                                    <button type="submit" class="w-full py-3 rounded-xl bg-black text-white font-semibold text-sm hover:bg-gray-800 transition active:scale-[0.98]">
                                                        Upload Bukti & Selesai
                                                    </button>
                                                </form>
                                            </div>

                                            {{-- Lapor Gagal --}}
                                            <div x-data="{ showFail: false }" class="mt-2">
                                                <button @click="showFail = !showFail" type="button" class="w-full py-2 text-xs font-semibold text-red-500 hover:text-red-700">
                                                    Ada Kendala Pengiriman?
                                                </button>
                                                <form x-show="showFail" x-collapse x-cloak action="{{ route('courier.orders.failure', $task) }}" method="POST" class="mt-2 space-y-2 p-3 border border-red-100 bg-red-50 rounded-xl">
                                                    @csrf
                                                    <textarea name="failure_note" rows="2" required placeholder="Alasan gagal kirim..."
                                                        class="w-full rounded-lg border-gray-300 text-sm focus:ring-red-500 focus:border-red-500 p-2"></textarea>
                                                    <button type="submit" class="w-full py-2 rounded-lg bg-red-600 text-white font-semibold text-xs active:scale-[0.98] transition-transform">Laporkan Gagal</button>
                                                </form>
                                            </div>
                                        @endif

                                        {{-- JIKA STATUS: GAGAL / SAMPAI (Hanya Tampilan View) --}}
                                        @if(in_array($task->item_status, ['sampai', 'selesai', 'gagal']))
                                            @if($task->delivery_proof_path)
                                                <div class="mt-4">
                                                    <p class="text-xs text-gray-500 mb-2">Bukti Foto:</p>
                                                    <img src="{{ Storage::url($task->delivery_proof_path) }}" class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                                </div>
                                            @endif
                                            @if($task->item_status === 'gagal')
                                                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg text-sm text-red-700">
                                                    <span class="font-bold">Gagal:</span> {{ $task->courier_note }}
                                                </div>
                                                <form action="{{ route('courier.orders.retry', $task) }}" method="POST" class="mt-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="w-full py-3 rounded-xl bg-black text-white font-semibold text-sm hover:bg-gray-800 transition active:scale-[0.98]">
                                                        Coba Kirim Lagi
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('[data-update-status]').forEach(button => {
                button.addEventListener('click', async function () {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
                    this.disabled = true;

                    const url = this.dataset.url;
                    try {
                        const response = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                            },
                        });

                        if (!response.ok) throw new Error('Gagal update status');

                        const data = await response.json();

                        if (data.nextStatus === 'dikirim' || data.nextStatus === 'sampai') {
                            window.location.reload();
                        }
                    } catch (error) {
                        alert(error.message);
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });
            });
        });
    </script>
@endpush
@endsection
