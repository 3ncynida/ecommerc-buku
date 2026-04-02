<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function estimate(Request $request, ShippingCalculator $calculator)
    {
        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::with(['province', 'city', 'district'])
            ->where('user_id', auth()->id())
            ->findOrFail($validated['address_id']);

        $result = $calculator->forAddress($address);

        return response()->json([
            'cost' => $result['cost'],
            'distance' => $result['distance'],
            'formatted_distance' => optional($result['estimate'])->formattedDistance(),
            'formatted_duration' => optional($result['estimate'])->formattedDuration(),
        ]);
    }
}
