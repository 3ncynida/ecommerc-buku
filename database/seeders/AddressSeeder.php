<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // Tambahkan ini di baris paling atas fungsi run
        Schema::disableForeignKeyConstraints();

        $user = \App\Models\User::first();

        if ($user) {
            \App\Models\Address::create([
                'user_id'          => $user->id,
                'recipient_name'   => 'Admin',
                'phone_number'     => '081234567890',
                'label'            => 'Rumah',
                'full_address'     => 'Jl. Jenderal Sudirman No. 123',
                'is_default'       => true,
                'postal_code'      => '10210',
                'province_id'      => 31,
                'city_id'          => 3171,
                'district_id'      => 3171010,
            ]);
        }

        // Aktifkan kembali di akhir
        Schema::enableForeignKeyConstraints();
    }
}
