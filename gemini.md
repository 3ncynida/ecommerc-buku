Halo, saya sedang mengembangkan aplikasi web e-commerce bernama "Libris" (Toko Buku Online). Tolong gunakan konteks berikut untuk semua jawabanmu ke depannya:

1. TECH STACK:
- Framework: Laravel (menggunakan Blade template).
- Database: MySQL.
- Payment Gateway: Midtrans (API & Snap, saat ini mode Sandbox).
- Data Wilayah: Package `laravolt/indonesia`.
- Email Testing: Mailtrap (via SMTP).

2. STRUKTUR DATABASE & MODEL UTAMA:
- `User`: Model standar Laravel. Berelasi One-to-Many dengan `Address` dan `Order`.
- `Item`: Menyimpan data buku (id, name, price, image, stok).
- `Address`: Menyimpan alamat pengiriman user. Memiliki kolom `province_id`, `city_id`, dan `district_id` yang terhubung ke tabel data Laravolt (tipe data ID Laravolt ini diperlakukan khusus, misalnya menggunakan string/char atau menonaktifkan pengecekan constraint).
- `Order`: Menyimpan data transaksi. Kolom penting: `order_number` (cth: LBRS-123456), `user_id`, `item_id`, `shipping_address_id`, `quantity`, `total_price`, `payment_status` (pending/success/failed), `item_status`.
- `Payment`: Menyimpan detail respons dari Midtrans (snap_token, raw_response, status).

3. ALUR KERJA SAAT INI (CONTROLLER):
- `CartController`: Menangani penambahan item ke keranjang (disimpan di Session), dan halaman checkout. Di checkout, sistem mengambil alamat default user atau membiarkan user memilih alamat.
- `PaymentController`: Menangani pembuatan transaksi Midtrans (createTransaction). Menyimpan `Order` dan `Payment` di dalam DB Transaction, lalu mengembalikan `snap_token` ke frontend. Juga menangani halaman redirect `success/finish`.
- `PaymentCallbackController` (Webhook): Bertugas menerima notifikasi otomatis (API Request) dari Midtrans saat status pembayaran berubah, lalu mengupdate `payment_status` di tabel `orders`.

4. FITUR YANG SUDAH JALAN:
- Add to Cart (Session base).
- Checkout dengan pemilihan alamat yang terintegrasi dengan Laravolt.
- Pembuatan Snap Token Midtrans.
- Pengurangan stok item otomatis saat pesanan sukses.
- Pengiriman email notifikasi (PaymentSuccessNotification) ke user via Mailtrap menggunakan fitur Queue Laravel.

Instruksi tambahan: Jika saya meminta bantuan kode, pastikan kodenya sesuai dengan struktur di atas. Jangan menyarankan untuk merombak relasi kecuali saya yang meminta.