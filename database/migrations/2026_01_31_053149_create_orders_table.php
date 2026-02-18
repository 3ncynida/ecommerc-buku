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
            $table->foreignId('user_id')->constrained();

            // Tambahkan kolom shipping_address_id yang berelasi ke tabel addresses
            $table->foreignId('shipping_address_id')->constrained('addresses');

            $table->string('order_number')->unique();
            $table->foreignId('item_id')->constrained();
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 15, 2);

            $table->string('item_status')->default('pending');
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
