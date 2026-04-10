@extends('customer.layouts.app')

@section('content')
<div class="relative min-h-screen py-12 bg-[#F8FAFC] overflow-hidden font-sans selection:bg-indigo-500 selection:text-white mt-16 md:mt-20">
    <!-- Ambient Background Elements -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-indigo-300/30 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-pulse"></div>
    <div class="absolute top-[20%] right-[-5%] w-72 h-72 bg-purple-300/30 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-[-10%] left-[20%] w-80 h-80 bg-pink-300/30 rounded-full mix-blend-multiply filter blur-[100px] opacity-70 animate-pulse" style="animation-delay: 4s;"></div>

    <div class="relative z-10 max-w-6xl mx-auto px-6">
        
        {{-- Profile Header Card --}}
        <div class="mb-10 flex flex-col md:flex-row items-center gap-6 bg-white/60 backdrop-blur-2xl border border-white p-8 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 p-1 shadow-xl shadow-indigo-200/50 transform transition-transform hover:scale-105">
                <div class="w-full h-full bg-white rounded-full flex items-center justify-center text-4xl font-black text-transparent bg-clip-text bg-gradient-to-br from-indigo-600 to-purple-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
            <div class="text-center md:text-left">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ auth()->user()->name }}</h1>
                <div class="mt-2 flex flex-wrap items-center justify-center md:justify-start gap-3">
                    <p class="text-sm text-gray-500 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-gray-400"></i> {{ auth()->user()->email }}
                    </p>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 hidden md:block"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl border border-indigo-100">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-8 p-5 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100/50 rounded-[1.5rem] flex items-center gap-4 shadow-lg shadow-emerald-500/5 backdrop-blur-sm animate-fade-in-down">
                <div class="w-10 h-10 bg-white rounded-full shadow-sm flex items-center justify-center flex-shrink-0 text-emerald-500">
                    <i class="fa-solid fa-check text-lg"></i>
                </div>
                <span class="text-sm font-bold text-emerald-800">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Bagian Kiri: Pengaturan Akun & Keamanan --}}
            <div class="lg:col-span-5 space-y-8">
                
                {{-- Form Info Akun --}}
                <section class="bg-white/70 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-[1rem] bg-gradient-to-br from-indigo-50 to-purple-50 border border-indigo-100 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                            <i class="fa-regular fa-id-card text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Informasi Pribadi</h2>
                            <p class="text-xs text-gray-400 mt-1 font-semibold">Perbarui data diri Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4">
                            <div class="group relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 absolute left-4 top-3 transition-all group-focus-within:text-indigo-500">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full pt-8 pb-3 px-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-[1.25rem] outline-none transition-all text-sm font-bold text-gray-800 shadow-sm">
                                @error('name') <p class="text-rose-500 text-[10px] mt-1.5 font-bold ml-2 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>

                            <div class="group relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 absolute left-4 top-3 transition-all group-focus-within:text-indigo-500">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full pt-8 pb-3 px-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 rounded-[1.25rem] outline-none transition-all text-sm font-bold text-gray-800 shadow-sm">
                                @error('email') <p class="text-rose-500 text-[10px] mt-1.5 font-bold ml-2 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-gradient-to-r from-gray-900 to-gray-800 hover:from-black hover:to-gray-900 text-white py-4 rounded-[1.25rem] text-xs font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 active:scale-95 group flex items-center justify-center gap-2">
                            <span>Simpan Info</span>
                            <i class="fa-solid fa-check text-[10px] opacity-70"></i>
                        </button>
                    </form>
                </section>

                {{-- Form Keamanan --}}
                <section class="bg-white/70 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl hover:shadow-rose-500/5 transition-all duration-300">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-[1rem] bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-100 flex items-center justify-center text-rose-500">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900">Keamanan</h2>
                            <p class="text-xs text-gray-400 mt-1 font-semibold">Ganti kata sandi akun</p>
                        </div>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4">
                            <div class="group relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 absolute left-4 top-3 transition-all group-focus-within:text-rose-500">Password Lama</label>
                                <input type="password" name="current_password"
                                    class="w-full pt-8 pb-3 px-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 rounded-[1.25rem] outline-none transition-all text-sm font-bold shadow-sm">
                                @error('current_password', 'updatePassword') <p class="text-rose-500 text-[10px] mt-1.5 font-bold ml-2">{{ $message }}</p> @enderror
                            </div>

                            <div class="group relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 absolute left-4 top-3 transition-all group-focus-within:text-rose-500">Password Baru</label>
                                <input type="password" name="password"
                                    class="w-full pt-8 pb-3 px-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 rounded-[1.25rem] outline-none transition-all text-sm font-bold shadow-sm">
                                @error('password', 'updatePassword') <p class="text-rose-500 text-[10px] mt-1.5 font-bold ml-2">{{ $message }}</p> @enderror
                            </div>

                            <div class="group relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 absolute left-4 top-3 transition-all group-focus-within:text-rose-500">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full pt-8 pb-3 px-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 rounded-[1.25rem] outline-none transition-all text-sm font-bold shadow-sm">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-white border-2 border-gray-100 hover:border-gray-200 hover:bg-gray-50 text-gray-800 py-4 rounded-[1.25rem] text-xs font-black uppercase tracking-widest transition-all shadow-sm active:-translate-y-0.5 group">
                            Ganti Password
                        </button>
                    </form>
                </section>

                {{-- Logout Button --}}
                <div class="bg-white/70 backdrop-blur-xl p-6 rounded-[2.5rem] border border-rose-100/50 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center">
                            <i class="fa-solid fa-power-off"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-sm">Akhiri Sesi</h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Keluar dari perangkat ini</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-rose-500 hover:bg-rose-600 text-white px-5 py-3 rounded-xl text-xs font-black uppercase tracking-widest transition-all hover:shadow-lg shadow-rose-200 flex items-center justify-center gap-2 group">
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </div>

            {{-- Bagian Kanan: Daftar Alamat (Customer Only) --}}
            <div class="lg:col-span-7">
                @if(auth()->user()->role === 'customer')
                    @if($errors->any() && !$errors->updatePassword->any())
                        <div class="bg-rose-50 border border-rose-100 p-5 rounded-[1.5rem] mb-6 flex gap-4 animate-fade-in-down items-start">
                            <div class="mt-0.5 text-rose-500"><i class="fa-solid fa-circle-exclamation"></i></div>
                            <div>
                                <div class="font-bold text-rose-800 text-sm">Periksa kembali data alamat Anda:</div>
                                <div class="text-rose-600 text-xs mt-1 font-medium">{{ $errors->first() }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-white/70 backdrop-blur-xl p-8 rounded-[2.5rem] border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] h-full flex flex-col">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3">
                                    Daftar Alamat
                                    <span class="bg-indigo-100 text-indigo-700 text-[10px] px-2.5 py-1 rounded-full font-black">{{ count($addresses) }}</span>
                                </h2>
                                <p class="text-xs text-gray-500 mt-1.5 font-medium">Tempat tujuan pengiriman pesanan Anda</p>
                            </div>
                            <button onclick="toggleModal('modal-address')"
                                class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-3 border border-indigo-500/50 rounded-2xl text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-lg shadow-indigo-200/50 transition-all hover:-translate-y-0.5 active:scale-95 w-full sm:w-auto justify-center">
                                <i class="fa-solid fa-plus"></i> Baru
                            </button>
                        </div>

                        <div class="flex-1 space-y-4">
                            @forelse($addresses as $addr)
                                <div class="group relative bg-white border-2 {{ $addr->is_default ? 'border-indigo-400 shadow-md shadow-indigo-100/50' : 'border-gray-100 hover:border-gray-200 shadow-sm' }} p-6 rounded-[2rem] transition-all duration-300 overflow-hidden">
                                    
                                    @if($addr->is_default)
                                        <div class="absolute top-0 right-0">
                                            <div class="bg-gradient-to-bl from-indigo-500 to-purple-600 text-white text-[10px] font-black px-5 py-2 rounded-bl-[1.5rem] uppercase tracking-widest shadow-sm">
                                                <i class="fa-solid fa-star mr-1 text-indigo-200"></i> Utama
                                            </div>
                                        </div>
                                    @endif

                                    <div class="pr-16">
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="bg-gray-50 text-gray-600 text-[10px] font-black px-3 py-1.5 rounded-lg border border-gray-200 uppercase flex items-center gap-1.5">
                                                <i class="fa-solid {{ $addr->label == 'Kantor' ? 'fa-building' : 'fa-house-chimney' }} text-[12px] opacity-70"></i> {{ $addr->label }}
                                            </span>
                                        </div>

                                        <p class="font-black text-gray-900 text-lg sm:text-xl">{{ $addr->recipient_name }}</p>
                                        <p class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600 text-sm mt-0.5 mb-3">{{ $addr->phone_number }}</p>

                                        <p class="text-sm text-gray-600 font-medium leading-relaxed bg-gray-50/80 p-4 rounded-2xl border border-gray-100">
                                            {{ $addr->full_address }}<br>
                                            <span class="text-gray-400 mt-1 block">
                                                Kec. {{ $addr->district->name ?? '-' }}, {{ $addr->city->name ?? '-' }}<br>
                                                {{ $addr->province->name ?? '-' }} {{ $addr->postal_code }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="mt-5 pt-5 border-t border-gray-100 flex flex-wrap items-center gap-3">
                                        <button onclick="toggleModal('modal-address-{{ $addr->id }}')"
                                            class="bg-white border border-gray-200 hover:border-indigo-300 hover:text-indigo-600 text-gray-700 px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm hover:shadow-md flex items-center gap-1.5 group">
                                            <i class="fa-regular fa-pen-to-square group-hover:scale-110 transition-transform"></i> Ubah
                                        </button>

                                        @if(!$addr->is_default)
                                            <form action="{{ route('address.set-default', $addr) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-emerald-50 hover:bg-emerald-500 border border-emerald-100 hover:border-emerald-500 text-emerald-600 hover:text-white px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 group">
                                                    <i class="fa-solid fa-check group-hover:scale-110 transition-transform"></i> Utama
                                                </button>
                                            </form>
                                        @endif

                                        <div class="flex-1"></div>

                                        <form action="{{ route('address.destroy', $addr) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-600 hover:bg-rose-50 px-3 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                                                <i class="fa-regular fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-center py-16 bg-white/50 rounded-[2rem] border-2 border-dashed border-gray-200">
                                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner border border-indigo-100">
                                        <i class="fa-solid fa-location-dot text-3xl text-indigo-300"></i>
                                    </div>
                                    <h3 class="text-gray-800 font-extrabold text-lg">Belum Ada Alamat</h3>
                                    <p class="text-gray-400 font-medium text-sm mt-1 mb-6 px-8 leading-relaxed">Tambahkan alamat pengiriman untuk kemudahan saat berbelanja.</p>
                                    <button onclick="toggleModal('modal-address')" class="text-indigo-600 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-colors flex items-center gap-2 shadow-sm">
                                        Mulai <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Add Address --}}
@if(auth()->user()->role === 'customer')
    <div id="modal-address" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" onclick="toggleModal('modal-address')"></div>
            
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-100">
                <div class="bg-gray-50 border-b border-gray-100 px-8 py-6 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                            <i class="fa-solid fa-map-location-dot text-lg"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900">Tambah Alamat</h3>
                    </div>
                    <button onclick="toggleModal('modal-address')" class="w-8 h-8 rounded-full bg-gray-200/50 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('address.store') }}" method="POST" class="p-8 space-y-5">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5 relative">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Penerima</label>
                            <input type="text" name="recipient_name" placeholder="John Doe"
                                class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm placeholder-gray-400">
                        </div>

                        <div class="space-y-1.5 relative">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">No. WhatsApp</label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-4 rounded-l-[1.25rem] border border-r-0 border-gray-200 bg-gray-100 text-gray-500 font-bold text-sm">
                                    +62
                                </span>
                                <input type="text" name="phone_number" placeholder="8123456"
                                    class="flex-1 w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-r-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm placeholder-gray-400">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Label</label>
                        <select name="label"
                            class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat text-gray-700">
                            <option value="Rumah">Rumah (Utama)</option>
                            <option value="Kantor">Kantor</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5 flex flex-col">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Provinsi</label>
                            <select id="province" name="province_id"
                                class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat text-gray-700">
                                <option value="">Pilih</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5 flex flex-col">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kota / Kab</label>
                            <select id="city" name="city_id" disabled
                                class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm disabled:opacity-60 disabled:cursor-not-allowed text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5 flex flex-col">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kecamatan</label>
                            <select id="district" name="district_id" disabled
                                class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm disabled:opacity-60 disabled:cursor-not-allowed text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="space-y-1.5 flex flex-col">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kode Pos</label>
                            <input type="text" name="postal_code" placeholder="12345"
                                class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm placeholder-gray-400">
                        </div>
                    </div>

                    <div class="space-y-1.5 relative">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Alamat Spesifik</label>
                        <textarea name="full_address" maxlength="200" placeholder="Jl. Sudirman No 123..."
                            class="w-full py-4 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] h-28 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm resize-none placeholder-gray-400"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white py-4 rounded-[1.25rem] font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-indigo-200 hover:-translate-y-0.5 mt-2 flex items-center justify-center gap-2">
                        Simpan Alamat
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Edit Address -->
    @foreach($addresses as $addr)
        <div id="modal-address-{{ $addr->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" onclick="toggleModal('modal-address-{{ $addr->id }}')"></div>
                
                <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-100">
                    <div class="bg-gray-50 border-b border-gray-100 px-8 py-6 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-pen-nib text-lg"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-900">Ubah Alamat</h3>
                        </div>
                        <button onclick="toggleModal('modal-address-{{ $addr->id }}')" class="w-8 h-8 rounded-full bg-gray-200/50 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form action="{{ route('address.update', $addr) }}" method="POST" class="p-8 space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5 relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Penerima</label>
                                <input type="text" name="recipient_name" value="{{ $addr->recipient_name }}"
                                    class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm placeholder-gray-400">
                            </div>

                            <div class="space-y-1.5 relative">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">No. WhatsApp</label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-4 rounded-l-[1.25rem] border border-r-0 border-gray-200 bg-gray-100 text-gray-500 font-bold text-sm">
                                        +62
                                    </span>
                                    <input type="text" name="phone_number" value="{{ str_replace('+62', '', $addr->phone_number) }}"
                                        class="flex-1 w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-r-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm placeholder-gray-400">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5 border-t border-b border-gray-50 py-4 mt-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Label</label>
                            <select name="label"
                                class="w-full mt-1.5 py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat text-gray-700">
                                <option value="Rumah" {{ $addr->label === 'Rumah' ? 'selected' : '' }}>Rumah</option>
                                <option value="Kantor" {{ $addr->label === 'Kantor' ? 'selected' : '' }}>Kantor</option>
                                <option value="Lainnya" {{ $addr->label !== 'Rumah' && $addr->label !== 'Kantor' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Provinsi</label>
                                <select name="province_id"
                                    class="province-select w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat text-gray-700">
                                    <option value="">Pilih</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" {{ $addr->province_id == $province->id ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kota / Kab</label>
                                <select name="city_id"
                                    class="city-select w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                                    <option value="">Pilih</option>
                                    @if($addr->city)
                                        <option value="{{ $addr->city->id }}" selected>{{ $addr->city->name }}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kecamatan</label>
                                <select name="district_id"
                                    class="district-select w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm text-gray-700 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                                    <option value="">Pilih</option>
                                    @if($addr->district)
                                        <option value="{{ $addr->district->id }}" selected>{{ $addr->district->name }}</option>
                                    @endif
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Kode Pos</label>
                                <input type="text" name="postal_code" value="{{ $addr->postal_code }}"
                                    class="w-full py-3.5 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5 relative">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1">Alamat Spesifik</label>
                            <textarea name="full_address" maxlength="200"
                                class="w-full py-4 px-4 bg-gray-50 border border-gray-200 rounded-[1.25rem] h-28 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all text-sm font-bold shadow-sm resize-none">{{ $addr->full_address }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-gray-900 to-black hover:from-gray-800 hover:to-gray-900 text-white py-4 rounded-[1.25rem] font-black text-sm uppercase tracking-widest transition-all shadow-lg hover:-translate-y-0.5 mt-2 flex items-center justify-center gap-2">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    const apiBaseUrl = @json(url('/api'));

    // Cascading dropdown untuk modal tambah alamat
    document.getElementById('province')?.addEventListener('change', function () {
        let provinceId = this.value;
        let cityDropdown = document.getElementById('city');

        cityDropdown.disabled = true;
        cityDropdown.innerHTML = '<option value="">Memuat...</option>';

        fetch(`${apiBaseUrl}/cities/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                cityDropdown.innerHTML = '<option value="">Pilih Kota / Kab</option>';
                data.forEach(city => {
                    cityDropdown.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                });
                cityDropdown.disabled = false;
            });
    });

    document.getElementById('city')?.addEventListener('change', function () {
        let cityId = this.value;
        let districtDropdown = document.getElementById('district');

        districtDropdown.disabled = true;
        districtDropdown.innerHTML = '<option value="">Memuat...</option>';

        fetch(`${apiBaseUrl}/districts/${cityId}`)
            .then(res => res.json())
            .then(data => {
                districtDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
                data.forEach(district => {
                    districtDropdown.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                });
                districtDropdown.disabled = false;
            });
    });

    // Cascading dropdown untuk modal edit (multiple)
    document.querySelectorAll('.province-select').forEach(select => {
        select.addEventListener('change', function () {
            let provinceId = this.value;
            let modal = this.closest('form').closest('[id^="modal-address-"]');
            let cityDropdown = modal.querySelector('.city-select');

            cityDropdown.innerHTML = '<option value="">Memuat...</option>';

            fetch(`${apiBaseUrl}/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    cityDropdown.innerHTML = '<option value="">Pilih Kota / Kab</option>';
                    data.forEach(city => {
                        cityDropdown.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                    });
                });
        });
    });

    document.querySelectorAll('.city-select').forEach(select => {
        select.addEventListener('change', function () {
            let cityId = this.value;
            let modal = this.closest('form').closest('[id^="modal-address-"]');
            let districtDropdown = modal.querySelector('.district-select');

            districtDropdown.innerHTML = '<option value="">Memuat...</option>';

            fetch(`${apiBaseUrl}/districts/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    districtDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(district => {
                        districtDropdown.innerHTML += `<option value="${district.id}">${district.name}</option>`;
                    });
                });
        });
    });
</script>
@endsection
