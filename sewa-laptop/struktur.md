# Struktur Proyek: Sewa Laptop Enterprise Core

Proyek ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** dengan standar keamanan industri dan pemisahan *concern* yang rapi. Berikut adalah hierarki file utama:

---

## 1. Application Logic (`app/`)

### Controllers (`app/Http/Controllers/`)
- **`AdminProdukController.php`** — Mengelola CRUD produk dan validasi *Serial Number* spesifik per merek.
- **`AdminStokController.php`** — Mengelola transfer stok fisik (Ready, Maintenance, Not Ready) dan audit log.
- **`CatalogController.php`** — Menangani katalog publik, pencarian, *sorting*, detail spesifikasi, dan proses sewa.
- **`TransactionController.php`** — Mengelola riwayat sewa (User) dan manajemen status transaksi (Admin: Pending, Approved, Returned, Cancelled).
- **`DashboardController.php`** — Mengelola statistik pendapatan bulanan, *low-stock alert*, dan inspeksi unit terakhir.
- **`AuthController.php`** — Menangani otentikasi Web (Login, Register, Logout) dengan proteksi *Rate Limiter*.
- **`ProfileController.php`** — Mengelola pembaruan profil pengguna.
- **`ReviewController.php`** — Menangani pengiriman ulasan dan rating produk dari penyewa.
- **`InspectionController.php`** — Menampilkan riwayat pemeriksaan fisik unit (*Unit Inspections*).
- **`UserController.php`** — Mengelola hak akses dan *Role* pengguna (Spatie Permission).
- **`Api/`**
  - `AuthController.php` — Otentikasi API menggunakan Laravel Sanctum (*Bearer Token*).
  - `ProductController.php` — Endpoint RESTful untuk katalog produk.
  - `TransactionController.php` — Endpoint RESTful untuk pemesanan via API.

### Form Requests (`app/Http/Requests/`)
- **`StoreLaptopRequest.php`** — Validasi ketat input produk baru, regulasi *Serial Number*, dan *File Upload*.
- **`UpdateLaptopRequest.php`** — Validasi pembaruan data produk.

### Models (`app/Models/`)
- **`Laptop.php`** — Model laptop dengan pencegahan stok negatif (*clamping*) dan manajemen status otomatis.
- **`Transaction.php`** — Model transaksi dengan relasi ke User dan Laptop.
- **`Review.php`** — Model untuk ulasan dan rating produk.
- **`UnitInspection.php`** — Model untuk audit log perpindahan status unit fisik.
- **`User.php`** — Model pengguna dengan implementasi *Role-Based Access Control* (Spatie) dan *Sanctum Tokens*.

---

## 2. Database & Migrations (`database/`)

- **`database/migrations/`** — Berisi skema migrasi tabel relasional (`users`, `laptops`, `transactions`, `reviews`, `unit_inspections`, `permissions`).
- **`database/seeders/`**
  - `DatabaseSeeder.php` — Inisialisasi peran (Admin/User), akun default, dan pemanggilan seeder produk.
  - `LaptopSeeder.php` — Data dummy 32 unit laptop dengan variasi merek dan panjang *Serial Number* yang valid.

---

## 3. Frontend & UI (`resources/views/`)

- **`layouts/app.blade.php`** — Layout global, navigasi responsif (*Hamburger Menu*), notifikasi *badge*, dan footer korporat.
- **`admin/`** — Area khusus admin (Manajemen Produk, Stok, Transaksi, Pengguna, dan Inspeksi).
- **`laptops/`** — Katalog publik, detail spesifikasi produk, dan kolom ulasan.
- **`transactions/`** — Riwayat sewa pengguna dan layout cetak bukti transaksi (*printable* bersih dari navigasi).
- **`auth/`** — Halaman Login dan Register dengan logo perusahaan dan fitur *show/hide password*.
- **`profile/`** — Halaman edit profil pengguna.

---

## 4. Routing & Configuration (`routes/` & `bootstrap/`)

- **`routes/web.php`** — Rute web dengan proteksi *middleware* autentikasi (`auth`) dan otorisasi peran (`role:admin` / `role:user`).
- **`routes/api.php`** — Rute API dengan proteksi Laravel Sanctum dan pembatas laju *Rate Limiter* (`throttle`).
- **`bootstrap/app.php`** — Konfigurasi *Kernel*, *Middleware* kustom, dan penjadwalan otomatis (*Scheduler* untuk pemeriksaan masa sewa).

---

## 5. Automated Testing (`tests/`)

- **`tests/Feature/AdminTest.php`** — Pengujian otomatis (*Feature Test*) untuk alur kerja admin (tambah produk, setujui, dan selesaikan transaksi).
- **`tests/Feature/UserTest.php`** — Pengujian otomatis untuk alur kerja pengguna (registrasi, melihat katalog, dan penyewaan).
