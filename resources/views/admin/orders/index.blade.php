<div class="container mt-4">
    <h2 class="mb-4">Daftar Pesanan (Admin)</h2>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 20%">No. Order</th>
                    <th style="width: 25%">Nama Item</th>
                    <th style="width: 15%">Total Bayar</th>
                    <th style="width: 15%">Status Pembayaran</th>
                    <th style="width: 15%">Status Logistik</th>
                    <th style="width: 10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="text-center">
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->item->name }}</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->payment_status == 'success')
                            <span class="badge bg-success p-2">LUNAS</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="badge bg-warning text-dark p-2">MENUNGGU</span>
                        @else
                            <span class="badge bg-danger p-2">GAGAL</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info text-dark p-2">{{ strtoupper($order->item_status) }}</span>
                    </td>
                    <td>
                        <form action="{{ url('/admin/orders/'.$order->id.'/update') }}" method="POST">
                            @csrf
                            <select name="item_status" onchange="this.form.submit()" class="form-select form-select-sm">
                                <option value="pending" {{ $order->item_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diproses" {{ $order->item_status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dikirim" {{ $order->item_status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="selesai" {{ $order->item_status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>