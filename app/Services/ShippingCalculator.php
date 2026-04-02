<?php

namespace App\Services;

use App\Models\Address;

class ShippingCalculator
{
    public function __construct(private DeliveryEstimator $estimator)
    {
    }

    public function forAddress(?Address $address): array
    {
        $estimate = $this->estimator->estimate($address);
        $distance = $estimate?->distanceKm;

        return [
            'cost' => $this->calculateCost($distance),
            'distance' => $distance,
            'estimate' => $estimate,
        ];
    }

    public function calculateCost(?float $distance): float
    {
        $rate = (float) config('store.shipping_rate_per_km', 2000);
        $minimum = (float) config('store.shipping_minimum', 10000);

        if (! $distance || $distance <= 0) {
            return $minimum;
        }

        return max($minimum, round($distance * $rate, 0));
    }
}
