@extends('admin.admin-layout')
@section('title', 'Detail Pesanan')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pesanan #{{ $order->order_number }}</h1>
                <p class="text-[13px] font-medium text-slate-500">
                    Dibuat pada <span class="font-bold text-slate-700">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-[13px] font-bold text-slate-600 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-wallet text-slate-400"></i>
                <span class="uppercase tracking-wider">{{ $order->payment_status }}</span>
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span class="text-[13px] font-bold">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-exclamation text-lg"></i>
            <span class="text-[13px] font-bold">{{ session('error') }}</span>
        </div>
    @endif

    @php
        $itemStatuses = [
            'menunggu_pembayaran' => ['label' => 'Menunggu Pembayaran', 'icon' => 'fa-wallet'],
            'pembayaran_gagal' => ['label' => 'Pembayaran Gagal', 'icon' => 'fa-xmark-circle'],
            'menunggu_kurir' => ['label' => 'Menunggu Kurir', 'icon' => 'fa-boxes-packing'],
            'diproses_kurir' => ['label' => 'Diproses', 'icon' => 'fa-people-carry-box'],
            'dikirim' => ['label' => 'Sedang Dikirim', 'icon' => 'fa-truck-fast'],
            'sampai' => ['label' => 'Sampai Tujuan', 'icon' => 'fa-house-circle-check'],
            'selesai' => ['label' => 'Selesai', 'icon' => 'fa-check-double'],
            'gagal' => ['label' => 'Gagal Pengiriman', 'icon' => 'fa-triangle-exclamation'],
            'dibatalkan' => ['label' => 'Dibatalkan', 'icon' => 'fa-ban'],
        ];
        $currentStatusData = $itemStatuses[$order->item_status] ?? ['label' => 'Status Unknown', 'icon' => 'fa-question'];
    @endphp

    {{-- Progress Status Bar --}}
    <div class="mb-8 bg-white p-6 rounded-[24px] border border-slate-200 shadow-sm overflow-x-auto">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-4 whitespace-nowrap">Jejak Status Pesanan</p>
        <div class="flex gap-3 min-w-[700px]">
            @foreach($itemStatuses as $key => $data)
                <div class="flex-1 rounded-xl p-3 text-center transition-all border
                    {{ $order->item_status === $key 
                        ? 'bg-indigo-600 border-indigo-600 text-white shadow-md transform scale-105' 
                        : 'bg-slate-50/50 border-slate-200 text-slate-400 opacity-70' }}">
                    <i class="fa-solid {{ $data['icon'] }} mb-1.5 text-lg block opacity-80"></i>
                    <p class="text-[10px] font-black uppercase tracking-widest leading-tight">{{ $data['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        
        {{-- KOLOM KIRI (2/3): Produk & Kurir --}}
        <div class="col-span-2 space-y-8">
            
            {{-- Box Detail Produk --}}
            <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden shadow-sm">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center gap-3 text-slate-800">
                    <i class="fa-solid fa-box-open text-indigo-500"></i>
                    <h3 class="font-black text-[15px] uppercase tracking-wider">Item Pembelian</h3>
                </div>
                
                <div class="p-8 flex flex-col gap-6">
                    @foreach($order->items as $orderItem)
                    <div class="flex flex-col sm:flex-row gap-6 {{ !$loop->last ? 'pb-6 border-b border-dashed border-slate-200' : '' }}">
                        <div class="w-32 rounded-2xl overflow-hidden shrink-0 border border-slate-200 shadow-sm bg-slate-50 aspect-[3/4] relative group">
                            <img src="{{ asset('storage/' . $orderItem->item->image) }}" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <h4 class="font-black text-slate-900 text-xl leading-tight mb-1">{{ $orderItem->item->name }}</h4>
                                <p class="text-[12px] text-slate-500 font-bold uppercase tracking-widest mb-4">
                                    <i class="fa-solid fa-feather-pointed mr-1 opacity-50"></i> {{ $orderItem->item->author->name }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-6 mt-2">
                                    <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Jumlah</p>
                                        <p class="font-black text-slate-800 text-[15px]"><i class="fa-solid fa-cubes text-slate-300 mr-1.5 text-[12px]"></i> {{ $orderItem->quantity }} Unit</p>
                                    </div>
                                    <div class="bg-slate-50 px-4 py-2.5 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Harga Satuan</p>
                                        <p class="font-black text-slate-800 text-[15px]">Rp {{ number_format($orderItem->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="mt-6 sm:mt-0 text-right sm:border-t-0 border-t border-slate-100 pt-6 sm:pt-0">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-1">Total Tagihan Pemesan</p>
                        <p class="font-black text-indigo-600 text-3xl tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Box Penunjukan Kurir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-8 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-3 text-slate-800">
                            <i class="fa-solid fa-motorcycle text-indigo-500"></i>
                            <h3 class="font-black text-[13px] uppercase tracking-wider">Info Kurir Pengantar</h3>
                        </div>
                        <span class="bg-indigo-50 text-indigo-600 text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-widest border border-indigo-100">
                            {{ $currentStatusData['label'] }}
                        </span>
                    </div>
                    
                    <div class="p-8 flex-1">
                        @if ($order->courier)
                            <div class="flex items-center gap-4 mb-6">
                                <div>
                                    <h4 class="font-black text-slate-900 text-[16px]">{{ $order->courier->name }}</h4>
                                    <p class="text-[12px] font-bold text-slate-500">{{ $order->courier->email }}</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-4 mb-6 p-4 rounded-2xl bg-slate-50 border border-slate-100 border-dashed">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm border border-slate-200">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-700 text-[13px]">Belum Ditugaskan</h4>
                                    <p class="text-[11px] font-medium text-slate-500 mt-0.5">Sistem sedang mencari kurir terdekat.</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 border-l-2 border-indigo-500 pl-2">Log Catatan Kurir</p>
                            <p class="text-[13px] font-medium text-slate-600 italic bg-slate-50 p-3 rounded-xl border border-slate-100">
                                "{{ $order->courier_note ?? 'Belum ada tanggapan/catatan yang diberikan kurir.' }}"
                            </p>
                        </div>
                        
                        @if ($order->payment_status === 'success' && $order->item_status === 'gagal')
                            <form action="{{ route('admin.orders.reassign', $order) }}" method="POST" class="mt-6">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 text-[13px] font-bold text-white hover:bg-indigo-700 shadow-md shadow-indigo-200 transition">
                                    <i class="fa-solid fa-rotate-right mr-2"></i> Tugaskan Ulang ke Antrian
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Box Bukti --}}
                <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 text-slate-800">
                        <i class="fa-solid fa-camera text-indigo-500"></i>
                        <h3 class="font-black text-[13px] uppercase tracking-wider">Bukti Ekspedisi</h3>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        @if ($order->delivery_proof_path)
                            <a href="{{ asset('storage/' . $order->delivery_proof_path) }}" target="_blank" class="block rounded-[20px] overflow-hidden border border-slate-200 bg-slate-50 relative group flex-1">
                                <img src="{{ asset('storage/' . $order->delivery_proof_path) }}" alt="Bukti {{ $order->order_number }}" class="absolute w-full h-full object-cover object-center transition group-hover:scale-110 duration-700">
                                <div class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/40 transition duration-300 flex items-center justify-center">
                                    <span class="opacity-0 group-hover:opacity-100 bg-white/90 text-slate-900 px-4 py-2 rounded-xl text-[12px] font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition duration-300">
                                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Perbesar
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="flex-1 rounded-[20px] border-2 border-dashed border-slate-200 bg-slate-50/50 flex flex-col items-center justify-center p-6 text-center">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-slate-300 shadow-sm border border-slate-200 mb-3">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                <h4 class="font-bold text-slate-600 text-[13px]">Belum Ada Bukti</h4>
                                <p class="text-[11px] font-medium text-slate-400 mt-1">Foto penerimaan paket akan muncul di sini setelah diunggah kurir.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>

        {{-- KOLOM KANAN (1/3): Info Pemesan & Pembayaran --}}
        <div class="space-y-8">
            
            {{-- Box Pelanggan --}}
            <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 flex items-center gap-3 text-slate-800">
                    <i class="fa-solid fa-address-card text-indigo-500"></i>
                    <h3 class="font-black text-[13px] uppercase tracking-wider">Informasi Pemesan</h3>
                </div>
                
                <div class="p-8">
                    {{-- Profil Akun --}}
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-black text-lg shadow-md">
                            {{ substr($order->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-[15px]">{{ $order->user->name }}</p>
                            <p class="text-[11px] font-bold text-slate-400">{{ $order->user->email }}</p>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <div class="flex items-center justify-between mb-3 border-b border-slate-200/60 pb-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest"><i class="fa-solid fa-location-dot mr-1 text-slate-300"></i> Tujuan Pengiriman</p>
                        </div>
                        
                        @if ($order->shippingAddress)
                            <div class="space-y-2">
                                <p class="font-bold text-slate-800 text-[13px]">
                                    {{ $order->shippingAddress->recipient_name }}
                                </p>
                                <p class="text-[12px] font-bold text-slate-600 bg-white border border-slate-200 inline-block px-2 xl rounded-md mb-1 shadow-sm">
                                    <i class="fa-solid fa-phone text-[10px] text-slate-400 mr-1"></i> {{ $order->shippingAddress->phone_number }}
                                </p>
                                <p class="text-[12px] font-medium text-slate-500 leading-relaxed">
                                    {{ $order->shippingAddress->full_address }}<br>
                                    <span class="font-bold text-slate-600">
                                        {{ $order->shippingAddress->district->name ?? '' }},
                                        {{ $order->shippingAddress->city->name ?? '' }},
                                        {{ $order->shippingAddress->province->name ?? '' }}
                                    </span><br>
                                    <span class="text-slate-400">Kode Pos: {{ $order->shippingAddress->postal_code }}</span>
                                </p>
                            </div>
                            
                            {{-- Estimasi --}}
                            @if(optional($deliveryEstimate)->hasValue())
                                <div class="mt-5 rounded-xl border border-indigo-100 bg-white p-4 shadow-sm relative overflow-hidden">
                                    <div class="absolute right-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-400 mb-1.5">
                                        <i class="fa-solid fa-clock-rotate-left mr-1"></i> Estimasi Distribusi
                                    </p>
                                    <p class="text-[13px] font-black text-slate-800">
                                        {{ $deliveryEstimate->formattedDuration() }}
                                    </p>
                                    <p class="text-[10px] font-medium text-slate-500 mt-1">
                                        Jarak : ±{{ $deliveryEstimate->formattedDistance() }} dari Gudang ({{ config('store.address') }})
                                    </p>
                                    @if($eta = $deliveryEstimate->arrivalAt())
                                        <p class="text-[11px] font-bold text-indigo-600 mt-2 pt-2 border-t border-indigo-50/50">
                                            <i class="fa-regular fa-calendar-check mr-1"></i> Max Tiba: {{ $eta->format('d M y, H:i') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-4 pt-4 border-t border-slate-100 text-sm font-semibold text-slate-800">
                                    Ongkos Kirim: Rp{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}
                                </div>
                            @endif
                        @else
                            <div class="py-4 text-center">
                                <p class="text-[12px] font-bold text-slate-400 italic">Data Alamat Hilang / Terhapus.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Box Payment Detail (Midtrans) --}}
            <div class="{{ $order->payment_status === 'success' ? 'bg-gradient-to-br from-indigo-800 to-indigo-950' : ($order->payment_status === 'failed' ? 'bg-gradient-to-br from-rose-800 to-rose-950' : 'bg-gradient-to-br from-slate-800 to-slate-950') }} rounded-[32px] p-8 text-white shadow-lg overflow-hidden relative">
                {{-- Background Deco --}}
                <div class="absolute -right-10 -top-10 text-white/5 text-[150px] rotate-12">
                    <i class="fa-brands fa-cc-stripe"></i>
                </div>
                
                <div class="relative z-10">
                    <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-building-columns"></i> Gerbang Pembayaran
                    </p>
                    <h4 class="font-black text-xl mb-6">Midtrans API <br> <span class="text-[12px] font-medium text-white/70">Payment Gateway Log</span></h4>
                    
                    <div class="bg-black/20 rounded-2xl p-4 border border-white/10 backdrop-blur-md relative overflow-hidden">
                        <p class="text-[9px] font-black uppercase tracking-widest text-white/40 mb-1">Status Pembayaran Akhir</p>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid {{ $order->payment_status === 'success' ? 'fa-shield-check text-emerald-400' : ($order->payment_status === 'failed' ? 'fa-triangle-exclamation text-rose-400' : 'fa-clock text-amber-400') }} text-lg drop-shadow shadow-black"></i>
                            <span class="text-[16px] font-black uppercase tracking-wider text-white drop-shadow-md">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>

                    @if ($order->payment_status === 'failed')
                        <div class="mt-5 rounded-2xl bg-rose-500/20 border border-rose-300/30 px-5 py-4 backdrop-blur-sm">
                            <p class="text-[9px] uppercase tracking-[0.2em] font-black text-rose-200 mb-2 border-b border-rose-400/20 pb-1">Log Galat (Error Reason)</p>
                            <p class="text-[12px] text-white/90 font-medium leading-relaxed">
                                {{ $order->payment?->raw_response['status_message'] ?? $order->payment?->raw_response['transaction_status'] ?? 'Detail kegagalan tidak diterima dari gateway.' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
