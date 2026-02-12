@extends('customer.layouts.app')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-6">

            {{-- Alert Success --}}
            @if(session('success'))
                <div
                    class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-8">
                {{-- Section: Informasi Akun --}}
                <section
                    class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 shadow-inner">
                            <i class="fa-solid fa-user-astronaut text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900 leading-none">Informasi Akun</h2>
                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-bold">Detail Profil Personal
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black uppercase text-gray-400 ml-1">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border {{ $errors->has('name') ? 'border-red-500' : 'border-transparent' }} focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm font-bold text-gray-700">
                                @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold ml-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black uppercase text-gray-400 ml-1">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-5 py-4 bg-gray-50 border {{ $errors->has('email') ? 'border-red-500' : 'border-transparent' }} focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all text-sm font-bold text-gray-700">
                                @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold ml-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 active:scale-95">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </section>

                {{-- Section: Keamanan --}}
                <section
                    class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <div class="flex items-center gap-4 mb-8">
                        <div
                            class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-600 shadow-inner">
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900 leading-none">Keamanan</h2>
                            <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-bold">Kelola Password Anda
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <input type="password" name="current_password" placeholder="Password Saat Ini"
                                    class="w-full px-5 py-4 bg-gray-50 border {{ $errors->updatePassword->has('current_password') ? 'border-red-500' : 'border-transparent' }} focus:bg-white focus:border-rose-500 rounded-2xl outline-none text-sm font-bold">
                                @error('current_password', 'updatePassword') <p
                                class="text-red-500 text-[10px] mt-1 font-bold ml-2">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <input type="password" name="password" placeholder="Password Baru"
                                    class="w-full px-5 py-4 bg-gray-50 border {{ $errors->updatePassword->has('password') ? 'border-red-500' : 'border-transparent' }} focus:bg-white focus:border-rose-500 rounded-2xl outline-none text-sm font-bold">
                                @error('password', 'updatePassword') <p
                                class="text-red-500 text-[10px] mt-1 font-bold ml-2">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi Password"
                                    class="w-full px-5 py-4 bg-gray-50 border border-transparent focus:bg-white focus:border-rose-500 rounded-2xl outline-none text-sm font-bold">
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="bg-gray-900 hover:bg-black text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all shadow-lg active:scale-95">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </section>


                @if(auth()->user()->role === 'customer')
                                {{-- Section: Daftar Alamat --}}
                                <section class="space-y-6">
                                    <div class="flex items-center justify-between px-2">
                                        <div>
                                            <h2 class="text-2xl font-black text-gray-900 leading-none">Daftar Alamat</h2>
                                            <p class="text-xs text-gray-400 mt-2 uppercase tracking-wider font-bold">Tujuan pengiriman
                                                pesanan Anda</p>
                                        </div>
                                        <button onclick="toggleModal('modal-address')"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-xl shadow-indigo-100 transition-all hover:-translate-y-0.5 active:scale-95">
                                            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baru
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4">
                                        @forelse($addresses as $addr)
                                            <div
                                                class="bg-white p-6 rounded-[2rem] border-2 {{ $addr->is_default ? 'border-indigo-500 bg-indigo-50/10' : 'border-transparent' }} shadow-sm relative overflow-hidden transition-all hover:shadow-md">
                                                @if($addr->is_default)
                                                    <div class="absolute top-0 right-0">
                                                        <span
                                                            class="bg-indigo-500 text-white text-[9px] font-black px-4 py-1.5 rounded-bl-2xl uppercase tracking-tighter">Utama</span>
                                                    </div>
                                                @endif

                                                <div class="flex items-start justify-between gap-4">
                                                    <div class="space-y-3">
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="bg-white border border-gray-200 text-gray-400 text-[10px] font-black px-3 py-1 rounded-lg uppercase flex items-center gap-1.5 shadow-sm">
                                                                <i class="fa-solid fa-house-chimney text-[9px]"></i> {{ $addr->label }}
                                                            </span>
                                                        </div>

                                                        <div>
                                                            <p class="font-black text-gray-900 text-lg leading-tight">
                                                                {{ $addr->recipient_name }}
                                                            </p>
                                                            <p class="font-bold text-indigo-600 text-sm mt-0.5">{{ $addr->phone_number }}</p>
                                                        </div>

                                                        <p class="text-sm text-gray-500 font-medium leading-relaxed max-w-2xl">
                                                            {{ $addr->full_address }}, {{ $addr->district->name }}, {{ $addr->city->name }},
                                                            {{ $addr->province->name }}, {{ $addr->postal_code }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="mt-6 pt-6 border-t border-gray-100 flex items-center gap-4">
                                                    <button onclick="toggleModal('modal-address-{{ $addr->id }}')"
                                                        class="text-xs font-black uppercase tracking-widest text-gray-900 hover:text-indigo-600 transition-colors">
                                                        <i class="fa-solid fa-pen-to-square mr-1.5 text-[10px]"></i> Ubah Alamat
                                                    </button>
                                                    <div class="w-1 h-1 bg-gray-300 rounded-full"></div>
                                                    <form action="{{ route('address.destroy', $addr) }}" method="POST" class="inline"
                                                        onsubmit="return confirm('Hapus alamat ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-xs font-black uppercase tracking-widest text-rose-500 hover:text-rose-700 transition-colors">
                                                            <i class="fa-solid fa-trash-can mr-1.5 text-[10px]"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
                                                <div
                                                    class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-200">
                                                    <i class="fa-solid fa-map-location-dot text-4xl"></i>
                                                </div>
                                                <p class="text-gray-400 font-bold italic">Belum ada alamat pengiriman yang terdaftar.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                @endif

    {{-- Modal Styles & JS tetap sama (Pastikan id modal sesuai) --}}

    <div id="modal-address" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"
                onclick="toggleModal('modal-address')"></div>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-8 pt-8 pb-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 text-center flex-1 ml-6">Detail Alamat</h3>
                    <button onclick="toggleModal('modal-address')" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="{{ route('address.store') }}" method="POST" class="p-8 pt-2 space-y-4">
                    @csrf
                    <input type="text" name="recipient_name" placeholder="Nama Penerima"
                        class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">

                    <div class="relative">
                        <span class="absolute left-4 top-4 text-gray-400 font-bold text-sm">+62</span>
                        <input type="text" name="phone_number" placeholder="No. Telp"
                            class="w-full p-4 pl-14 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">
                    </div>

                    <select name="label"
                        class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                        <option value="" disabled selected>Label (Rumah, Kantor, dll)</option>
                        <option value="Rumah">Rumah</option>
                        <option value="Kantor">Kantor</option>
                    </select>

                    <select id="province" name="province_id"
                        class="w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition appearance-none bg-[url('...')] bg-no-repeat bg-[right_1rem_center]">
                        <option value="">Pilih Provinsi</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                    </select>

                    <select id="city" name="city_id"
                        class="w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition"
                        disabled>
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>

                    <select id="district" name="district_id"
                        class="w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition"
                        disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>

                    <input type="text" name="postal_code" placeholder="Kode Pos"
                        class="w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition">

                    <div class="relative">
                        <textarea name="full_address" maxlength="200" placeholder="Alamat Lengkap"
                            class="w-full p-4 border border-gray-200 rounded-2xl h-32 text-sm font-medium outline-none transition resize-none"></textarea>
                        <span
                            class="absolute bottom-3 right-4 text-[10px] text-gray-400 font-bold tracking-widest uppercase">0/200</span>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black text-lg transition shadow-xl shadow-indigo-100 mt-4">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-8 mt-12 mb-20">
        <div class="bg-white p-6 rounded-[30px] border border-gray-100 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-right-from-bracket text-red-500 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Sesi Akun</h3>
                    <p class="text-sm text-gray-500">Keluar dari akun Anda dengan aman</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
                @csrf
                <button type="submit"
                    class="w-full md:w-64 bg-red-500 text-white px-8 py-4 rounded-2xl font-bold hover:bg-red-600 hover:shadow-lg hover:shadow-red-100 transition-all duration-300 flex items-center justify-center gap-2">
                    <span>Keluar dari Akun</span>
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    @if(auth()->user()->role === 'customer')
        <!-- Edit Address Modals -->
        @foreach($addresses as $addr)
            <div id="modal-address-{{ $addr->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"
                        onclick="toggleModal('modal-address-{{ $addr->id }}')"></div>

                    <div
                        class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="px-8 pt-8 pb-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-900 text-center flex-1 ml-6">Ubah Alamat</h3>
                            <button onclick="toggleModal('modal-address-{{ $addr->id }}')"
                                class="text-gray-400 hover:text-gray-600">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <form action="{{ route('address.update', $addr) }}" method="POST" class="p-8 pt-2 space-y-4">
                            @csrf
                            @method('PUT')
                            <input type="text" name="recipient_name" placeholder="Nama Penerima" value="{{ $addr->recipient_name }}"
                                class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">

                            <div class="relative">
                                <span class="absolute left-4 top-4 text-gray-400 font-bold text-sm">+62</span>
                                <input type="text" name="phone_number" placeholder="No. Telp"
                                    value="{{ str_replace('+62', '', $addr->phone_number) }}"
                                    class="w-full p-4 pl-14 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">
                            </div>

                            <select name="label"
                                class="w-full p-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22none%22%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M5%207.5L10%2012.5L15%207.5%22%20stroke%3D%22%239CA3AF%22%20stroke-width%3D%221.67%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E')] bg-[length:20px_20px] bg-[right_1rem_center] bg-no-repeat">
                                <option value="Rumah" {{ $addr->label === 'Rumah' ? 'selected' : '' }}>Rumah</option>
                                <option value="Kantor" {{ $addr->label === 'Kantor' ? 'selected' : '' }}>Kantor</option>
                            </select>

                            <select name="province_id"
                                class="province-select w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" {{ $addr->province_id == $province->id ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>

                            <select name="city_id"
                                class="city-select w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition">
                                <option value="">Pilih Kota/Kabupaten</option>
                                @if($addr->city)
                                    <option value="{{ $addr->city->id }}" selected>{{ $addr->city->name }}</option>
                                @endif
                            </select>

                            <select name="district_id"
                                class="district-select w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition">
                                <option value="">Pilih Kecamatan</option>
                                @if($addr->district)
                                    <option value="{{ $addr->district->id }}" selected>{{ $addr->district->name }}</option>
                                @endif
                            </select>

                            <input type="text" name="postal_code" placeholder="Kode Pos" value="{{ $addr->postal_code }}"
                                class="w-full p-4 border border-gray-200 rounded-2xl text-sm font-medium outline-none transition">

                            <div class="relative">
                                <textarea name="full_address" maxlength="200" placeholder="Alamat Lengkap"
                                    class="w-full p-4 border border-gray-200 rounded-2xl h-32 text-sm font-medium outline-none transition resize-none">{{ $addr->full_address }}</textarea>
                                <span
                                    class="absolute bottom-3 right-4 text-[10px] text-gray-400 font-bold tracking-widest uppercase">{{ strlen($addr->full_address) }}/200</span>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-black text-lg transition shadow-xl shadow-indigo-100 mt-4">
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

        // Cascading dropdown untuk modal tambah alamat
        document.getElementById('province')?.addEventListener('change', function () {
            let provinceId = this.value;
            let cityDropdown = document.getElementById('city');

            cityDropdown.disabled = true;
            cityDropdown.innerHTML = '<option value="">Memuat...</option>';

            fetch(`/api/cities/${provinceId}`)
                .then(res => res.json())
                .then(data => {
                    cityDropdown.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
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

            fetch(`/api/districts/${cityId}`)
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

                fetch(`/api/cities/${provinceId}`)
                    .then(res => res.json())
                    .then(data => {
                        cityDropdown.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
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

                fetch(`/api/districts/${cityId}`)
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