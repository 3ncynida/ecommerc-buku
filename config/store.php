<?php

return [
    'name' => env('STORE_NAME', 'Libris Book Store'),
    'address' => env('STORE_ADDRESS', 'Jl. M.H. Thamrin No. 14, Jakarta Pusat'),
    'latitude' => env('STORE_LATITUDE', '-6.1812806'),
    'longitude' => env('STORE_LONGITUDE', '106.8269217'),
    'average_speed_kmh' => env('STORE_AVERAGE_SPEED_KMH', 33),
    'daily_distance_km' => env('STORE_DAILY_DISTANCE_KM', 300),
    'shipping_rate_per_km' => env('STORE_SHIPPING_RATE_PER_KM', 2000),
    'shipping_minimum' => env('STORE_SHIPPING_MINIMUM', 10000),
];
