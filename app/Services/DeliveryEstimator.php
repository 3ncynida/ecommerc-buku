<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Support\Facades\Log;
use Geocoder;

class DeliveryEstimator
{
    public function estimate(?Address $address): ?DeliveryEstimate
    {
        if (! $address) {
            return null;
        }

        $destination = $this->resolveAddressCoordinates($address);
        $store = $this->storeCoordinates();

        if (! $destination || ! $store) {
            return null;
        }

        $distance = $this->haversineDistance(
            $store['lat'],
            $store['lng'],
            $destination['lat'],
            $destination['lng']
        );

        $speed = max(1, (float) config('store.average_speed_kmh', 33));
        $minutes = (int) ceil(($distance / $speed) * 60);
        $minutes = max(15, $minutes);

        $buffer = $this->distanceBufferMinutes($distance);
        if ($buffer > 0) {
            $minutes = max($minutes, $buffer);
        }

        return new DeliveryEstimate($distance, $minutes);
    }

    protected function resolveAddressCoordinates(Address $address): ?array
    {
        $metaSources = [
            optional($address->district)->meta,
            optional($address->city)->meta,
            optional($address->province)->meta,
        ];

        foreach ($metaSources as $meta) {
            $coords = $this->metaToCoordinate($meta);

            if ($coords) {
                return $coords;
            }
        }

        return $this->geocodeAddress($address);
    }

    protected function geocodeAddress(Address $address): ?array
    {
        $parts = array_filter([
            $address->full_address,
            optional($address->district)->name,
            optional($address->city)->name,
            optional($address->province)->name,
        ]);

        $query = implode(', ', $parts);

        if (! $query) {
            return null;
        }

        try {
            $result = Geocoder::getCoordinatesForAddress($query);

            if (! $result) {
                return null;
            }

            return $this->metaToCoordinate($result);
        } catch (\Throwable $e) {
            Log::warning('Geocoding failed', [
                'error' => $e->getMessage(),
                'address' => $query,
            ]);

            return null;
        }
    }

    protected function metaToCoordinate($meta): ?array
    {
        if (! is_array($meta)) {
            return null;
        }

        $latitude = data_get($meta, 'lat') ?? data_get($meta, 'latitude');
        $longitude = data_get($meta, 'long') ?? data_get($meta, 'lng') ?? data_get($meta, 'longitude');

        if ($latitude === null || $longitude === null) {
            return null;
        }

        return [
            'lat' => (float) $latitude,
            'lng' => (float) $longitude,
        ];
    }

    protected function storeCoordinates(): ?array
    {
        $latitude = config('store.latitude');
        $longitude = config('store.longitude');

        if ($latitude === null || $longitude === null) {
            return null;
        }

        return [
            'lat' => (float) $latitude,
            'lng' => (float) $longitude,
        ];
    }

    protected function distanceBufferMinutes(float $distance): int
    {
        $daily = max(1, (float) config('store.daily_distance_km', 300));

        if ($distance <= $daily) {
            return 0;
        }

        $days = (int) ceil($distance / $daily);

        return $days * 24 * 60;
    }

    protected function haversineDistance(float $latFrom, float $lngFrom, float $latTo, float $lngTo): float
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($latTo - $latFrom);
        $lngDelta = deg2rad($lngTo - $lngFrom);

        $a = sin($latDelta / 2) ** 2 +
            cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) * sin($lngDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
