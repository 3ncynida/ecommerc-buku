<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'recipient_name', 'phone_number', 'label', 
        'province_id', 'city_id', 'district_id', 'postal_code', 
        'full_address', 'is_default'
    ];

    // Relasi ke Laravolt
    public function province() { return $this->belongsTo(Province::class, 'province_id'); }
    public function city() { return $this->belongsTo(City::class, 'city_id'); }
    public function district() { return $this->belongsTo(District::class, 'district_id'); }

    public function user() { return $this->belongsTo(User::class); }
}   