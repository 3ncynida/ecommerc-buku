<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Facade as Indonesia;


class LocationController extends Controller
{
    /**
     * Mengambil daftar Kota berdasarkan ID Provinsi
     */
    public function cities($province_id)
    {
        $cities = Indonesia::findProvince($province_id, ['cities'])
            ->cities
            ->sortBy('name')
            ->values(); // WAJIB tambahkan ini agar jadi array [0,1,2...]

        return response()->json($cities);
    }

    public function districts($city_id)
    {
        $districts = Indonesia::findCity($city_id, ['districts'])
            ->districts
            ->sortBy('name')
            ->values(); // WAJIB tambahkan ini

        return response()->json($districts);
    }
}