# 🔐 Konfigurasi Redirect Login

## Opsi 1: Redirect Berdasarkan Role (SUDAH DIIMPLEMENTASI)

Saat user login, mereka akan diarahkan ke:
- **Admin** → `/admin/dashboard`
- **Customer** → `/` (home page)

Kode di `AuthenticatedSessionController@store()` sudah diupdate.

---

## Opsi 2: Redirect Berdasarkan Role + Custom Logic

Jika ingin lebih kompleks, edit `app/Http/Controllers/Auth/AuthenticatedSessionController.php`:

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();
    
    // Opsi redirect berbeda berdasarkan role
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'customer' => redirect()->route('home'),
        'seller' => redirect()->route('seller.dashboard'),
        default => redirect()->route('home'),
    };
}
```

---

## Opsi 3: Redirect ke URL Tertentu (Hard-coded)

Jika semua user harus ke halaman yang sama:

```php
return redirect('/dashboard');  // Ke halaman tertentu
```

---

## Opsi 4: Redirect ke Last Visited Page (Default)

```php
return redirect()->intended(route('home'));  // Redirect ke halaman sebelumnya jika ada
```

---

## Opsi 5: Redirect Berdasarkan Kondisi Lainnya

```php
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();
    $request->session()->regenerate();

    $user = Auth::user();
    
    // Cek apakah email sudah verified
    if (!$user->email_verified_at) {
        return redirect()->route('verification.notice');
    }
    
    // Cek role
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('home');
}
```

---

## Verifikasi Routes

Pastikan route yang digunakan sudah terdaftar di `routes/web.php`:

```php
Route::get('/admin/dashboard', ...)->name('admin.dashboard');
Route::get('/', [CustomerController::class, 'index'])->name('home');
```

Lihat di `routes/web.php` untuk memastikan semua route ada!
