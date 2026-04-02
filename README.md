# 📚 Libris - Toko Buku Online (E-Commerce)

Libris adalah platform e-commerce (Toko Buku Online) modern berbasis **Laravel**. Sistem ini dirancang secara khusus untuk mendukung pengalaman belanja ragam karya sastra dan bahan bacaan dengan integrasi pembayaran aman, arsitektur database _robust_, serta manajemen kurir yang elegan.

---

## 🚀 Fitur Utama
1. **Multi-Role Authentication Area**:
   - **Admin**: Manajemen data buku, kategori, penulis, penugasan otomatis maupun manual kurir, serta pemantauan analitik penjualan di dasbor.
   - **Customer**: Menelusuri buku, memasukkan ke keranjang belanja (_Cart_), *checkout* jenis benda ganda (multi-produk), dan melacak riwayat transaksi & posisi paket.
   - **Kurir**: Dasbor terpisah untuk menerima tugas pengiriman, mengupdate jejak langkah logistik (Diproses $\rightarrow$ Dikirim $\rightarrow$ Sampai), serta mengunggah/retur dengan bukti foto.
2. **Katalog & Keranjang Dinamis**: Sistem keranjang belanja (`Cart`) ditangani via _Session_ sehingga sangat responsif. Sistem sekarang secara murni mendukung kalkulasi **Pemesanan Multi-Produk** dalam satu faktur tagihan (_Invoice_).
3. **Midtrans Payment Gateway**: Pembayaran aman yang disambungkan menuju penyedia _midtrans_ dengan jendela _pop-up Snap API_ secara mulus.
4. **Logistik Daerah Berjenjang**: Dukungan rantai _dropdown_ pemilihan pengiriman mulai dari **Provinsi $\rightarrow$ Kota/Kabupaten $\rightarrow$ Kecamatan** yang di-substitusi penuh oleh *Package* tangguh ([laravolt/indonesia](https://github.com/laravolt/indonesia)).
5. **Webhook & Email Terjadwal**: Terdapat sistem _Webhook_ yang siaga menangkap *update* otomatis dari server bank. Nota pembelian PDF langsung diproses diam-diam (_Background Jobs_) dan ditembakkan ke _email inbox_ pembeli tanpa membekukan layar (memanfaatkan Queue dengan *SMTP Mailtrap*).
6. **Laporan Transaksi Generatif**: Fitur untuk melakukan saringan (filterisasi) rekapan buku-buku berpendapatan teratas berdasarkan _date range_ dan menu khusus untuk mencetaknya di kertas / ke format *Document*.

---

## 🛠️ Teknologi & Peralatan (Tech Stack)
- **Logika Sistem (Server)**: Laravel 11.x (PHP 8.2+)
- **Antarmuka Layar (User Interface)**: Alpine.js, Tailwind CSS versi kustom (Pendekatan *Glassmorphism* & *Premium Card Interface*)
- **Penyimpanan**: MySQL / MariaDB Server.
- **Pihak Ketiga (Integrasi API)**: Midtrans API, FontAwesome, SMTP Mailtrap, Laravolt Wilayah.

---

## 📦 Panduan Instalasi Cepat (Quick Start Clone)

Gunakan langkah-langkah di bawah ini untuk memasang aplikasi Libris di komputer lokal (*localhost*) milik Anda.

### 1. Kebutuhan Sistem Terlebih Dahulu:
Pastikan PC/Laptop sudah memiliki yang berikut ini:
- PHP (Versi 8.2 atau lebih tinggi ter-*install*)
- Composer (Sebagai manajer paket PHP)
- Node.js & NPM (Sebagai _build tools_ antarmuka Tailwind)
- XAMPP / Laragon atau paket database MySQL setara.

### 2. Tahap _Cloning_ & Konfigurasi Dasar
1. Tarik (Clone) proyek dari repositori Git menggunakan command prompt Anda:
   ```bash
   # Ganti URL menggunakan link repository asli nantinya
   git clone https://github.com/USERNAME/libris-ecommerce.git
   cd libris-ecommerce
   ```
2. Instal library PHP *(Backend)* dan Javascript *(Frontend)*:
   ```bash
   composer install
   npm install
   ```
3. Gandakan file `.env.example` ke dalam spesifikasi environment mandiri `.env`:
   ```bash
   cp .env.example .env
   ```
4. Hubungkan database MySQL di `.env`. Buatlah suatu database kosong (contohnya `db_buku`) lewat _phpMyAdmin_.
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_buku   # <- Sesuaikan nama DB Anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Hubungkan Layanan API Pihak ketiga di `.env`:
   *(Ambil Sandbox Server Key/Client Key di situs peloton Midtrans Anda, dan juga konfigurasi Mailtrap)*
   ```env
   MIDTRANS_MERCHANT_ID=G-xx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxx
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxx
   MIDTRANS_IS_PRODUCTION=false

   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_user
   MAIL_PASSWORD=your_mailtrap_pass
   ```

### 3. Instalasi Ke Dalam Pusat Data (Database Structure)
Penting! Aktifkan Application Key internal laravel, migrasikan sistemnya dan inject seeder dasar.
```bash
# Aktifkan Token
php artisan key:generate

# Migrasikan Database dan tabel provisi (Otomatis membangkitkan wilayah kelurahan Laravolt & Akun Superadmin)
php artisan migrate:fresh --seed

# Mempertautkan direktori Storage untuk fitur membaca cover buku / resi foto kurir
php artisan storage:link
```

### 4. Aktivasi Antarmuka CSS & Layanan Penunjang Server
Kompilasi selubung gaya `Tailwind` kemudian nyalakan server pengembangan Anda perlahan.
Harus dipahami, demi mendukung perlintasan data di kerangka belakang (*Queue*), siapkan > 1 tab CLI.

```bash
# Lakukan Build untuk mengompilasi Vite & AlpineJs
npm run build
```

**Buka Terminal Pertama (Sebagai Web Engine Utama):**
```bash
php artisan serve
```

**Buka Terminal Kedua (Khusus Agar Pekerja Antrian Email Berjalan):**
```bash
php artisan queue:work
```

---
🎉 **Selamat!** Kini Anda bisa membuka *browser* dan mengakses situs E-Commerce `Libris` melewati URL yang diberikan (*misal:* `http://127.0.0.1:8000`). Minta pembeli untuk mengakses *homepage*. Berikan url tambahan seperti `/login` untuk memasukkan kurir dan Admin ke area kerja mereka.
