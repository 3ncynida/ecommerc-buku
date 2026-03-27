<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function statuses(): array
    {
        return [
            'menunggu_pembayaran',
            'pembayaran_gagal',
            'menunggu_kurir',
            'diproses_kurir',
            'dikirim',
            'sampai',
            'selesai',
            'gagal',
        ];
    }

    public function up(): void
    {
        $statuses = $this->statuses();
        $enumList = implode(', ', array_map(fn ($value) => "'{$value}'", $statuses));

        DB::table('orders')
            ->whereNotIn('item_status', $statuses)
            ->update(['item_status' => 'menunggu_pembayaran']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM({$enumList}) NOT NULL DEFAULT 'menunggu_pembayaran'");
    }

    public function down(): void
    {
        DB::table('orders')
            ->whereIn('item_status', ['menunggu_pembayaran', 'pembayaran_gagal'])
            ->update(['item_status' => 'menunggu_kurir']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM('menunggu_kurir','diproses_kurir','dikirim','sampai','selesai','gagal') NOT NULL DEFAULT 'menunggu_kurir'");
    }
};
