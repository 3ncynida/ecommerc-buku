<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Libris</title>
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' http://127.0.0.1:5173 http://localhost:5173 https://app.sandbox.midtrans.com; connect-src 'self' http://127.0.0.1:5173 ws://127.0.0.1:5173 https://app.sandbox.midtrans.com https://api.sandbox.midtrans.com;">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    <aside class="w-64 bg-indigo-900 text-white min-h-screen sticky top-0">
        <div class="p-6 text-2xl font-bold border-b border-indigo-800">Libris {{ auth()->user()->name }}</div>
<nav class="mt-6 px-4 space-y-2">

    <a href="{{ route('admin.dashboard.index') }}"
       class="block py-2.5 px-4 rounded transition
       {{ request()->routeIs('admin.dashboard.index') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }}">
        <i class="fa-solid fa-gauge mr-2"></i> Dashboard
    </a>

    <a href="{{ route('categories.index') }}"
       class="block py-2.5 px-4 rounded transition
       {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }}">
        <i class="fa-solid fa-list mr-2"></i> Kategori
    </a>

    <a href="{{ route('authors.index') }}"
       class="block py-2.5 px-4 rounded transition
       {{ request()->routeIs('authors.*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }}">
        <i class="fa-solid fa-pen-nib mr-2"></i> Penulis
    </a>

    <a href="{{ route('items.index') }}"
       class="block py-2.5 px-4 rounded transition
       {{ request()->routeIs('items.*') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }}">
        <i class="fa-solid fa-book mr-2"></i> Data Buku
    </a>

    <a href="{{ route('admin.profile.edit') }}"
       class="block py-2.5 px-4 rounded transition
       {{ request()->routeIs('admin.profile.edit') ? 'bg-indigo-700' : 'hover:bg-indigo-800' }}">
        <i class="fa-solid fa-user mr-2"></i> Profil Admin
    </a>

    <a href="/test" class="block py-2.5 px-4 rounded transition hover:bg-indigo-800">
        <i class="fa-solid fa-cog mr-2"></i> test
    </a>
</nav>

    </aside>

    <main class="flex-1">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-700">@yield('title')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500">Halo, Administrator</span>
                <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-full">
            </div>
        </header>

        <div class="p-8">
            @yield('content')
        </div>
    </main>

</body>
</html>