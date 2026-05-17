# Kurban Transaksi Elektronik

Aplikasi web berbasis Laravel 12 untuk mengelola transaksi dan pelaksanaan ibadah kurban secara digital. Sistem ini memfasilitasi proses dari pemesanan hewan kurban, integrasi pembayaran digital, manajemen ketersediaan, hingga pelaksanaan penyembelihan dan distribusi daging kurban.

## 🌟 Fitur Utama

Aplikasi ini mencakup berbagai fitur untuk mendukung proses kurban dari awal hingga akhir:

- **Manajemen Pengguna & Dashboard**: Akses terpisah antara Admin dan User (Mudhohi) dengan autentikasi penuh.
- **Ketersediaan Hewan**: Sistem pengelolaan stok hewan kurban yang tersedia.
- **Pemesanan Kurban**: Proses pemesanan hewan kurban oleh pengguna.
- **Integrasi Pembayaran (Midtrans)**: Pembayaran otomatis dengan Payment Gateway Midtrans (Snap API).
- **Manajemen Operasional**: Pengelolaan rekening Bank, Dana DKM, dan Dana Operasional.
- **Pelaksanaan & Penyembelihan**: Pencatatan status pelaksanaan kurban dan penyembelihan.
- **Distribusi**: Manajemen pendistribusian daging kurban kepada yang berhak.

## 📋 Persyaratan Sistem (Requirements)

Untuk menjalankan aplikasi ini secara lokal (tanpa Docker), Anda membutuhkan:
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL / MariaDB
- Akun Midtrans (Server Key & Client Key untuk `.env`)

*Direkomendasikan menggunakan **Docker Desktop** untuk mempermudah proses instalasi dan testing tanpa perlu mengatur environment PHP dan database secara manual.*

## 📂 Struktur Folder Proyek

Proyek ini mengikuti standar struktur direktori Laravel dengan beberapa penyesuaian:

```text
kurban_transaksielektronik/
├── app/               # Logika inti aplikasi (Controllers, Models, Middleware dll)
├── bootstrap/         # Skrip inisialisasi aplikasi Laravel
├── config/            # Konfigurasi aplikasi, database, dan Midtrans
├── database/          # File migrasi (migrations) dan seeder database
├── public/            # File aset publik (gambar, css/js hasil build) & entry point (index.php)
├── resources/         # Tampilan Blade templates, CSS (Tailwind), dan JavaScript
├── routes/            # Definisi rute aplikasi (web.php, api.php)
├── storage/           # Penyimpanan file yang di-upload, logs, dan cache aplikasi
├── tests/             # File untuk automated testing (Unit dan Feature tests)
├── docker-compose.yml # Konfigurasi Docker services (App & MySQL)
├── Dockerfile         # Instruksi build image Docker untuk aplikasi
└── package.json       # Konfigurasi dependensi frontend (NPM)
```

## 🐳 Cara Menjalankan dan Testing dengan Docker Desktop

Aplikasi ini sudah di-containerize menggunakan Docker. Anda dapat langsung menjalankan dan mengetesnya dengan sangat mudah.

**Langkah-langkah:**

1. Pastikan aplikasi **Docker Desktop** sudah terinstal dan dalam status **Running**.
2. Buka Terminal / Command Prompt dan navigasikan ke dalam folder proyek ini.
3. Jalankan perintah berikut untuk mem-build dan menjalankan container di background:
   ```bash
   docker-compose up -d --build
   ```
   *Catatan: Proses ini otomatis akan melakukan:*
   - *Install dependensi PHP via Composer*
   - *Membuat file `.env` dari `.env.example`*
   - *Generate Application Key (`php artisan key:generate`)*
   - *Menjalankan Migrasi Database (`php artisan migrate`)*
   - *Install dan build aset frontend (`npm install` & `npm run build`)*
   - *Menjalankan server Laravel di port 8000*
4. Tunggu hingga proses build selesai. Jika berhasil, database MySQL dan Aplikasi akan berjalan di container.
5. Buka browser dan akses aplikasi di:
   👉 **http://localhost:8000**
6. (Opsional) Jika ingin melihat log aplikasi yang berjalan di Docker:
   ```bash
   docker-compose logs -f app
   ```
7. Untuk mematikan dan menghapus container yang sedang berjalan, gunakan perintah:
   ```bash
   docker-compose down
   ```

---
*Dikembangkan untuk keperluan manajemen Transaksi Elektronik Kurban.*
