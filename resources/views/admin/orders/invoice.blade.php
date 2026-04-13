<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            color: #334155;
            background-color: #f8fafc;
        }
        @media print {
            body { background-color: #ffffff; }
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            @page { margin: 0; }
            body { margin: 1cm; }
        }
    </style>
</head>
<body class="antialiased text-sm">

<div class="max-w-4xl mx-auto mt-6 no-print flex justify-end px-4">
    <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow border border-indigo-600 hover:bg-indigo-700 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Dokumen
    </button>
</div>

<div class="max-w-3xl mx-auto p-8 bg-white border border-slate-200 rounded-xl shadow-sm my-6 print:border-none print:shadow-none print:m-0 print:p-0 print:w-full">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-black text-indigo-700 mb-1 font-sans tracking-tighter">LIBRIS.</h1>
            <p class="text-[11px] text-slate-500 max-w-[250px] leading-relaxed">
                Jendela Dunia Dalam Genggaman.<br>
                {{ config('store.address', 'Jl. Merdeka No. 123, Jakarta Selatan, 12950') }}
            </p>
        </div>
        <div class="text-right">
            <h2 class="text-lg font-black text-slate-800 uppercase tracking-widest mb-1 border-b-[2px] border-indigo-500 inline-block pb-0.5">Invoice</h2>
            <p class="text-slate-700 font-black mb-1 text-sm">#{{ $order->order_number }}</p>
            <p class="text-[10px] text-slate-500 font-medium font-sans mb-2">Tanggal Terbit: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <div class="inline-block px-2 py-0.5 text-[9px] font-black tracking-widest uppercase rounded border {{ $order->payment_status === 'success' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                {{ $order->payment_status }}
            </div>
        </div>
    </div>

    <div class="flex justify-between gap-6 mb-6 border-t border-b border-slate-200/60 py-4 font-sans">
        <div class="w-1/2">
            <h3 class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-2">Ditagihkan Kepada</h3>
            <p class="font-bold text-slate-800 text-[12px] mb-0.5">{{ $order->user->name }}</p>
            <p class="text-[11px] font-semibold text-slate-500">{{ $order->user->email }}</p>
        </div>
        <div class="w-1/2">
            <h3 class="text-[9px] font-black uppercase text-slate-400 tracking-widest mb-2">Tujuan Pengiriman</h3>
            @if($order->shippingAddress)
                <p class="font-bold text-slate-800 text-[12px] mb-0.5">{{ $order->shippingAddress->recipient_name }}</p>
                <p class="text-[11px] font-semibold text-slate-600 mb-0.5 tracking-wide">{{ $order->shippingAddress->phone_number }}</p>
                <p class="text-[10px] font-medium text-slate-500 leading-snug">
                    {{ $order->shippingAddress->full_address }}<br>
                    {{ $order->shippingAddress->district->name ?? '' }}, {{ $order->shippingAddress->city->name ?? '' }}, {{ $order->shippingAddress->province->name ?? '' }}<br>
                    {{ $order->shippingAddress->postal_code }}
                </p>
            @else
                <p class="text-[11px] italic text-slate-400">Data alamat tidak tersedia.</p>
            @endif
        </div>
    </div>

    <table class="w-full text-left border-collapse mb-6">
        <thead>
            <tr class="bg-slate-50/80 border-y border-slate-200 font-sans">
                <th class="py-2 px-3 text-[9px] font-black uppercase text-slate-500 tracking-widest w-[50%]">Deskripsi Produk</th>
                <th class="py-2 px-3 text-[9px] font-black uppercase text-slate-500 tracking-widest text-center">Harga Satuan</th>
                <th class="py-2 px-3 text-[9px] font-black uppercase text-slate-500 tracking-widest text-center">Qty</th>
                <th class="py-2 px-3 text-[9px] font-black uppercase text-slate-500 tracking-widest text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($order->items as $item)
                <tr>
                    <td class="py-3 px-3">
                        <p class="font-bold text-slate-800 text-[11px] font-sans hover:text-indigo-600 transition">{{ $item->item->name }}</p>
                    </td>
                    <td class="py-3 px-3 text-center text-[11px] font-semibold text-slate-600">
                        Rp{{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-3 text-center text-[11px] font-black text-slate-700">
                        {{ $item->quantity }}
                    </td>
                    <td class="py-3 px-3 text-right text-[11px] font-bold text-slate-800">
                        Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-end mb-8">
        <div class="w-1/2 max-w-[280px]">
            <div class="flex justify-between py-1.5 text-[11px] text-slate-600 border-b border-slate-100">
                <span class="font-medium">Subtotal Produk</span>
                <span class="font-bold">Rp{{ number_format($order->items->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-1.5 text-[11px] text-slate-600 border-b border-slate-200 mb-2 font-sans">
                <span class="font-medium">Ongkos Kirim</span>
                <span class="font-bold tracking-wide">Rp{{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-1.5 text-[11px] text-slate-600 border-b border-slate-200 mb-2 font-sans">
                <span class="font-medium">Biaya Admin</span>
                <span class="font-bold tracking-wide">Rp{{ number_format($order->admin_fee ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-2 text-[14px] font-black text-indigo-700 bg-indigo-50/50 rounded-lg px-3 border border-indigo-100">
                <span>Total</span>
                <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="text-center pt-4 border-t border-slate-200">
        <p class="text-[11px] font-black text-slate-800 mb-0.5 uppercase tracking-widest">Terima kasih atas pesanan Anda.</p>
        <p class="text-[9px] text-slate-500 font-medium font-sans">Jika ada pertanyaan terkait tagihan ini, silakan hubungi kami di support@libris.com.</p>
    </div>
</div>

</body>
</html>
