<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    use RedirectsUsers;

    /**
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        $state = Str::random(40);
        session()->put('google_oauth_state', $state);

        $params = [
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'access_type' => 'offline',
            'prompt' => 'select_account',
            'state' => $state,
        ];

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    }

    /**
     * Handle the OAuth callback from Google.
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        if ($request->filled('error')) {
            return redirect()->route('login')->withErrors(['google' => 'Login Google dibatalkan.']);
        }

        $state = session()->pull('google_oauth_state');

        if (!$state || $state !== $request->state) {
            return redirect()->route('login')->withErrors(['google' => 'Permintaan Google tidak valid.']);
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->code,
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if ($tokenResponse->failed()) {
            return redirect()->route('login')->withErrors(['google' => 'Gagal menerima token dari Google.']);
        }

        $accessToken = $tokenResponse->json('access_token');

        if (! $accessToken) {
            return redirect()->route('login')->withErrors(['google' => 'Token Google tidak lengkap.']);
        }

        $profileResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if ($profileResponse->failed()) {
            return redirect()->route('login')->withErrors(['google' => 'Gagal membaca profil Google.']);
        }

        $googleUser = $profileResponse->json();

        if (empty($googleUser['email'])) {
            return redirect()->route('login')->withErrors(['google' => 'Google tidak membagikan email.']);
        }

        $user = $this->findOrCreateUser($googleUser);

        Auth::login($user, true);

        return redirect($this->redirectPathFor($user));
    }

    /**
     * Find existing user or create a new one.
     */
    protected function findOrCreateUser(array $googleUser): User
    {
        $user = User::firstOrNew(['email' => $googleUser['email']]);

        $user->name = $googleUser['name'] ?? $googleUser['email'];
        $user->google_id = $googleUser['sub'] ?? $user->google_id;
        $user->google_avatar = $googleUser['picture'] ?? $user->google_avatar;

        if (! $user->exists) {
            $user->password = Hash::make(Str::random(32));
        }

        $user->save();

        return $user;
    }
}
