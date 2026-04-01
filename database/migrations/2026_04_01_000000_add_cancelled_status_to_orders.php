<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $allowed = [
            'menunggu_pembayaran',
            'pembayaran_gagal',
            'menunggu_kurir',
            'diproses_kurir',
            'dikirim',
            'sampai',
            'selesai',
            'gagal',
            'dibatalkan',
        ];

        $enumList = implode("','", $allowed);

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM('{$enumList}') NOT NULL DEFAULT 'menunggu_pembayaran'");
    }

    public function down(): void
    {
        $allowed = [
            'menunggu_pembayaran',
            'pembayaran_gagal',
            'menunggu_kurir',
            'diproses_kurir',
            'dikirim',
            'sampai',
            'selesai',
            'gagal',
        ];

        $enumList = implode("','", $allowed);

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM('{$enumList}') NOT NULL DEFAULT 'menunggu_pembayaran'");
    }
};
