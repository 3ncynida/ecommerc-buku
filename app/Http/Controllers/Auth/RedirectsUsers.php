<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;

trait RedirectsUsers
{
    /**
     * Resolve the post-auth redirect path based on the user's role.
     */
    protected function redirectPathFor(User $user): string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard.index', absolute: false),
            'courier' => route('courier.dashboard', absolute: false),
            default => route('home', absolute: false),
        };
    }
}
