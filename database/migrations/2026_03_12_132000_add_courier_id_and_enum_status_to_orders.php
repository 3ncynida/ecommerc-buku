<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function enumStatuses(): array
    {
        return ['menunggu_kurir', 'diproses_kurir', 'dikirim', 'sampai', 'selesai'];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $statuses = $this->enumStatuses();
        $enumList = implode(', ', array_map(fn ($value) => "'{$value}'", $statuses));

        DB::table('orders')
            ->whereNotIn('item_status', $statuses)
            ->update(['item_status' => 'menunggu_kurir']);

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('courier_id')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status ENUM({$enumList}) NOT NULL DEFAULT 'menunggu_kurir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['courier_id']);
            $table->dropColumn('courier_id');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN item_status VARCHAR(255) NOT NULL DEFAULT 'pending'");
    }
};
