<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Address;
use Laravolt\Indonesia\Facade as Indonesia;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Mengambil alamat user beserta nama wilayahnya (Eager Loading)
        $addresses = Address::where('user_id', $user->id)
            ->with(['province', 'city', 'district'])
            ->get();

        // Mengambil data provinsi menggunakan helper Laravolt
        $provinces = Indonesia::allProvinces();

        return view('customer.profile.index', [
            'user' => $user,
            'addresses' => $addresses,
            'provinces' => $provinces,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Store a new address for the user.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'label' => 'required|string',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'postal_code' => 'required|string',
            'full_address' => 'required|string|max:200',
        ]);

        // Simpan ke database
        $addressCount = auth()->user()->addresses()->count();
        $validated['user_id'] = auth()->id();
        $validated['is_default'] = ($addressCount === 0); // Set jadi utama jika ini alamat pertama

        Address::create($validated);

        return redirect()->back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    /**
     * Update an address.
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Pastikan address milik user yang login
        if ($address->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan mengubah alamat ini.');
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10',
            'label' => 'required|string',
            'province_id' => 'required|exists:indonesia_provinces,id',
            'city_id' => 'required|exists:indonesia_cities,id',
            'district_id' => 'required|exists:indonesia_districts,id',
            'postal_code' => 'required|string',
            'full_address' => 'required|string|max:200',
        ]);

        $address->update($validated);

        return redirect()->back()->with('success', 'Alamat berhasil diubah!');
    }

    /**
     * Delete an address.
     */
    public function destroyAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak diizinkan menghapus alamat ini.');
        }

        $address->delete();

        return redirect()->back()->with('success', 'Alamat berhasil dihapus!');
    }

    /**
     * Get cities by province (AJAX).
     */
    public function getCities($provinceId)
    {
        $cities = Indonesia::findCitiesByProvinceId($provinceId);
        return response()->json($cities);
    }

    /**
     * Get districts by city (AJAX).
     */
    public function getDistricts($cityId)
    {
        $districts = Indonesia::findDistrictsByCityId($cityId);
        return response()->json($districts);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
