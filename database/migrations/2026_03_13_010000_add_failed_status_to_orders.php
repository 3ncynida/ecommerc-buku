<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM('menunggu_kurir','diproses_kurir','dikirim','sampai','selesai','gagal') NOT NULL DEFAULT 'menunggu_kurir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM('menunggu_kurir','diproses_kurir','dikirim','sampai','selesai') NOT NULL DEFAULT 'menunggu_kurir'");
    }
};
