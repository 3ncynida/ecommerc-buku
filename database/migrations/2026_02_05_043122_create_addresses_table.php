<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('recipient_name');
            $table->string('phone_number');
            $table->string('label'); // Contoh: Rumah, Kantor

            // Relasi ke tabel Laravolt (Menggunakan ID wilayah)
            $table->foreignId('province_id')->constrained('indonesia_provinces');
            $table->foreignId('city_id')->constrained('indonesia_cities');
            $table->foreignId('district_id')->constrained('indonesia_districts');

            $table->string('postal_code');
            $table->text('full_address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
