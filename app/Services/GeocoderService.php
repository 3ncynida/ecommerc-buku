<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocoderService
{
    public function getCoordinatesForAddress(string $address): ?array
    {
        $apiKey = config('services.geocoding.google_api_key');
        if (! $apiKey) {
            Log::warning('Geocoding skipped because GOOGLE_MAPS_API_KEY is missing.');
            return null;
        }

        $response = Http::timeout((float) config('services.geocoding.timeout', 5))
            ->retry(2, 200)
            ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $apiKey,
            ]);

        if (! $response->ok()) {
            Log::warning('Geocoding request failed', ['status' => $response->status()]);
            return null;
        }

        $payload = $response->json();

        if (($payload['status'] ?? null) !== 'OK' || empty($payload['results'][0]['geometry']['location'])) {
            Log::warning('Geocoding did not return any results', ['payload' => $payload]);
            return null;
        }

        $location = $payload['results'][0]['geometry']['location'];

        return [
            'lat' => $location['lat'],
            'lng' => $location['lng'],
        ];
    }
}
