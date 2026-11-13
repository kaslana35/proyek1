# Struktur Proyek: Sewa Laptop Enterprise Core

Proyek ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** dengan standar keamanan industri dan pemisahan *concern* yang rapi. Berikut adalah hierarki file utama:

---

## 1. Application Logic (`app/`)

### Controllers (`app/Http/Controllers/`)
- **`AdminProdukController.php`** — Mengelola CRUD produk dan validasi *Serial Number* spesifik per merek.
- **`AdminStokController.php`** — Mengelola transfer stok fisik (Ready, Maintenance, Not Ready), ringkasan stok, riwayat transfer, dan audit log.
- **`CatalogController.php`** — Menangani katalog publik, pencarian, *sorting*, detail spesifikasi, dan proses sewa (dengan atomic transaction + lockForUpdate).
- **`TransactionController.php`** — Mengelola riwayat sewa (User) dan manajemen status transaksi (Admin: Pending, Approved, Returned, Cancelled) dengan atomic transaction.
- **`DashboardController.php`** — Mengelola statistik pendapatan bulanan, *low-stock alert*, dan inspeksi unit terakhir.
- **`AuthController.php`** — Menangani otentikasi Web (Login, Register, Logout) dengan proteksi *Rate Limiter*.
- **`ProfileController.php`** — Mengelola pembaruan profil pengguna dan password.
- **`ReviewController.php`** — Menangani pengiriman ulasan dan rating produk dari penyewa.
- **`InspectionController.php`** — Menampilkan riwayat pemeriksaan fisik unit (*Unit Inspections*).
- **`UserController.php`** — Mengelola hak akses dan *Role* pengguna (Spatie Permission) dengan pagination.
- **`Api/`**
  - `AuthController.php` — Otentikasi API menggunakan Laravel Sanctum (*Bearer Token*).
  - `ProductController.php` — Endpoint RESTful untuk katalog produk (dengan role-based access control).
  - `TransactionController.php` — Endpoint RESTful untuk pemesanan via API (dengan atomic transaction + lockForUpdate).

### Form Requests (`app/Http/Requests/`)
- **`StoreLaptopRequest.php`** — Validasi ketat input produk baru, regulasi *Serial Number* per brand, dan *File Upload*.
- **`UpdateLaptopRequest.php`** — Validasi pembaruan data produk.

### Middleware (`app/Http/Middleware/`)
- **`LogRequests.php`** — Middleware pencatatan semua respon API (200, 201, 400, 401, 403, 404, 429, 500) ke `laravel.log`.

### Models (`app/Models/`)
- **`Laptop.php`** — Model laptop dengan pencegahan stok negatif (*clamping*) dan manajemen status otomatis.
- **`Transaction.php`** — Model transaksi dengan relasi ke User dan Laptop.
- **`Review.php`** — Model untuk ulasan dan rating produk.
- **`UnitInspection.php`** — Model untuk audit log perpindahan status unit fisik (dengan catatan).
- **`User.php`** — Model pengguna dengan implementasi *Role-Based Access Control* (Spatie) dan *Sanctum Tokens*.

### Providers (`app/Providers/`)
- **`AppServiceProvider.php`** — Konfigurasi Rate Limiter ('api': 60 req/menit, 'auth': 5 req/menit).

---

## 2. Database & Migrations (`database/`)

- **`database/migrations/`** — Berisi skema migrasi tabel relasional (`users`, `laptops`, `transactions`, `reviews`, `unit_inspections`, `permissions`, `personal_access_tokens`).
- **`database/seeders/`**
  - `DatabaseSeeder.php` — Inisialisasi peran (Admin/User), akun default, dan pemanggilan seeder produk.
  - `LaptopSeeder.php` — Data dummy 34 unit laptop (6 Dell, 6 Lenovo, 6 HP, 4 Acer, 2 Gigabyte, 4 Asus, 4 Apple, 2 MSI) dengan validasi Serial Number real-world.

---

## 3. Frontend & UI (`resources/views/`)

- **`layouts/app.blade.php`** — Layout global dengan Dark/Light mode toggle, navigasi responsif (*Hamburger Menu*), notifikasi *badge*, footer korporat, dan favicon SVG custom.
- **`admin/`** — Area khusus admin:
  - `produk/manage.blade.php` — Manajemen produk (tabel, pagination 6, dark mode).
  - `laptops/manage.blade.php` — Manajemen stok (transfer, ringkasan, riwayat transfer).
  - `laptops/edit.blade.php` — Form edit produk (dark mode).
  - `laptops/create.blade.php` — Form tambah produk (dark mode, dropdown brand + MSI).
  - `transactions/index.blade.php` — Manajemen transaksi (tabel, pagination 10, dark mode).
  - `stok/manage.blade.php` — Ringkasan stok + transfer form + riwayat transfer.
  - `inspections/index.blade.php` — Riwayat pemeriksaan unit (pagination 15, dark mode).
  - `users/index.blade.php` — Manajemen user & role (pagination 10, dark mode).
  - `users/edit_role.blade.php` — Form edit role (dark mode).
- **`laptops/`** — Katalog publik (grid responsive, search, sort, pagination 6), detail spesifikasi produk, dan kolom ulasan (dark mode).
- **`transactions/`** — Riwayat sewa pengguna (pagination 5, dark mode) dan layout cetak bukti transaksi (*printable* bersih dari navigasi).
- **`auth/`** — Halaman Login, Register, dan Lupa Password dengan logo perusahaan, fitur *show/hide password*, dan Dark/Light mode toggle.
- **`profile/`** — Halaman edit profil (informasi + password) dengan dark mode.
- **`dashboard.blade.php`** — Dashboard admin dengan stats, grafik pendapatan, low-stock alert, dan inspeksi (dark mode, SVG icons).

---

## 4. Routing & Configuration (`routes/` & `bootstrap/`)

- **`routes/web.php`** — Rute web dengan proteksi *middleware* autentikasi (`auth`) dan otorisasi peran (`role:admin` / `role:user`).
- **`routes/api.php`** — Rute API dengan proteksi Laravel Sanctum, *Rate Limiter* (`throttle`), dan *Role-Based Access Control* (`role:admin`).
- **`bootstrap/app.php`** — Konfigurasi *Kernel*, *Middleware* kustom (LogRequests), dan penjadwalan otomatis (*Scheduler* untuk pemeriksaan masa sewa).

---

## 5. Automated Testing (`tests/`)

- **`tests/Feature/AdminTest.php`** — Pengujian otomatis (*Feature Test*) untuk alur kerja admin (tambah produk, setujui, dan selesaikan transaksi).
- **`tests/Feature/UserTest.php`** — Pengujian otomatis untuk alur kerja pengguna (registrasi, melihat katalog, dan penyewaan).

---

## 6. Dokumentasi

- **`struktur.md`** — Dokumentasi struktur proyek (file ini).
- **`deskripsi.md`** — Dokumentasi deskripsi proyek (ringkasan eksekutif, fitur, arsitektur, keamanan, keunggulan).
- **`README.md`** — (jika ada) Dokumentasi instalasi dan penggunaan.

---

## 7. Konfigurasi & Environment

- **`.env`** — Konfigurasi environment (APP_DEBUG=false, APP_KEY, DB_CONNECTION=mysql).
- **`tailwind.config`** (inline di Blade) — Dark mode class-based, Plus Jakarta Sans font, custom colors (primary, accent).
- **`composer.json`** — Dependencies: Laravel 13, Spatie Permission, Laravel Sanctum.
- **`package.json`** — (jika ada) Frontend dependencies.

---

## 8. Git History

Proyek ini memiliki 20+ commit dengan pesan profesional (Conventional Commits):
- `feat:` — Penambahan fitur baru
- `fix:` — Perbaikan bug
- `style:` — Perbaikan UI/UX
- `docs:` — Pembaruan dokumentasi
- `chore:` — Perubahan konfigurasi

Semua perubahan tercatat secara sistematis di repository Git lokal.