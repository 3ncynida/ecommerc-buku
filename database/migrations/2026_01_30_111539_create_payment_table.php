<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // ID unik dari aplikasi kamu (misal: INV-1001)
            $table->string('order_id')->unique(); 
            // ID transaksi resmi dari pihak Midtrans
            $table->string('transaction_id')->nullable(); 
            
            $table->decimal('amount', 15, 2);
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, dll
            
            // Status utama: pending, settlement, capture, expire, cancel, deny
            $table->string('status'); 
            
            // Opsional: Menyimpan URL pembayaran Snap jika user belum bayar
            $table->string('snap_token')->nullable();
            $table->string('checkout_url')->nullable();
            
            // Metadata tambahan untuk debugging
            $table->json('raw_response')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};