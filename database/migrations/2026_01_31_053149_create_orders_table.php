<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Contoh: ORD-20231001-001
            $table->foreignId('item_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 15, 2);

            // Status Progres Barang
            // Contoh: pending, diproses, dikirim, selesai, dibatalkan
            $table->string('item_status')->default('pending');

            // Status Pembayaran (Sinkron dengan Midtrans)
            // Contoh: pending, success, failed
            $table->string('payment_status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
