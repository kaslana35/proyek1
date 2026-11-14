# Deskripsi Proyek: Sewa Laptop Enterprise Core

## 1. Ringkasan Eksekutif
**Sewa Laptop Enterprise Core** adalah aplikasi web berbasis *Full-Stack* yang dirancang untuk mengotomatisasi dan mengelola proses bisnis penyewaan perangkat keras komputer (laptop) secara terintegrasi. Sistem ini menyediakan alur kerja menyeluruh (*end-to-end*), mulai dari katalog produk publik, manajemen inventaris fisik, siklus pemesanan, hingga pencatatan ulasan dan pelaporan operasional yang akurat.

---

## 2. Latar Belakang & Tujuan
Pengelolaan penyewaan laptop secara konvensional seringkali menghadapi kendala seperti inkonsistensi stok, kesalahan pencatatan transaksi manual, kesulitan melacak riwayat kondisi fisik unit, hingga lemahnya batasan akses antara admin dan penyewa. 

Proyek ini dibangun untuk mengatasi masalah tersebut dengan menerapkan:
- **Otomatisasi Inventaris**: Mencegah stok minus dan menyelaraskan status unit secara *real-time*.
- **Pemisahan Peran yang Ketat (RBAC)**: Memastikan keamanan data antara administrator dan pengguna biasa.
- **Standar Keamanan Industri**: Mencegah kerentanan umum seperti *Insecure Direct Object Reference* (IDOR), *Cross-Site Scripting* (XSS), *Brute Force*, *Race Condition*, dan *Broken Access Control*.

---

## 3. Fitur Utama Sistem

### A. Sisi Pengguna (Penyewa / Guest)
- **Katalog Publik & Pencarian**: Menjelajahi daftar laptop lengkap dengan spesifikasi rinci, harga sewa per hari, dan indikator ketersediaan stok.
- **Sistem Pemesanan (Sewa)**: Memilih rentang tanggal sewa dengan validasi durasi otomatis dan persetujuan syarat & ketentuan.
- **Riwayat & Cetak Bukti Sewa**: Memantau status pemesanan (Pending, Approved, Returned, Cancelled) dengan pagination 5 item per halaman, serta mencetak kwitansi/bukti transaksi resmi.
- **Ulasan & Penilaian (Review)**: Memberikan rating bintang (1-5) dan komentar testimoni pada laptop yang pernah disewa.

### B. Sisi Administrator (Manajemen Sistem)
- **Dashboard Eksekutif**: Menampilkan ringkasan transaksi pending, unit dalam perbaikan (*maintenance*), grafik pendapatan bulanan, *Low Stock Alert*, dan log inspeksi terakhir.
- **Manajemen Produk & Validasi Serial Number**: Pengelolaan inventaris laptop yang dilengkapi validasi ketat panjang karakter *Serial Number* berdasarkan merek (Dell: 7, Lenovo: 8, HP: 10, Acer: 22, Asus: 15, Apple: 10-12, MSI: 15-20, Gigabyte: 10).
- **Manajemen Stok & Audit Unit**: Memindahkan status unit fisik (*Ready, Maintenance, Not Ready*) lengkap dengan ringkasan stok, riwayat transfer (Dari → Ke), catatan, dan log audit perpindahan (*Unit Inspections*).
- **Manajemen Transaksi**: Menyetujui, menyelesaikan (pengembalian unit & pemulihan stok otomatis), atau membatalkan pesanan, serta mencetak laporan rekapitulasi operasional.
- **Manajemen Pengguna**: Pengelolaan data pengguna dan hak akses peran berbasis **Spatie Laravel Permission**.

### C. Layanan API (Backend Restful)
- **Otorisasi Token (Laravel Sanctum)**: Menyediakan *endpoint* autentikasi dan transaksi berbasis token untuk integrasi dengan aplikasi eksternal atau *Mobile App*.
- **Rate Limiter / Anti-Flood**: Proteksi pembatasan laju akses (*throttle*) pada rute login (5 req/menit) dan API (60 req/menit) untuk menangkal serangan *brute force*.
- **Role-Based Access Control (API)**: Endpoint CRUD produk hanya bisa diakses oleh admin, endpoint sewa bisa diakses semua user terautentikasi.
- **API Logging**: Pencatatan semua respon API (200, 201, 400, 401, 403, 404, 429, 500) ke `laravel.log`.

### D. Antarmuka Pengguna (UI/UX)
- **Dark/Light Mode**: Toggle tema dengan persistensi localStorage, tersedia di semua 18 halaman (navbar desktop + mobile + halaman standalone).
- **Responsive Design**: *Mobile-first* dengan Tailwind CSS, hamburger menu, touch-friendly targets (min 44x44px).
- **Typography**: Plus Jakarta Sans (Google Fonts) — font enterprise SaaS.
- **Print-Friendly**: Layout cetak bersih tanpa navigasi, dengan `print-color-adjust: exact`.
- **Favicon Custom**: Icon laptop SVG (menggantikan logo Laravel default).

---

## 4. Arsitektur & Teknologi yang Digunakan
- **Backend Framework**: Laravel 13 (PHP 8.4) dengan pola arsitektur **MVC** (*Model-View-Controller*).
- **Database**: MySQL dengan *Atomic Transactions* (`DB::beginTransaction` + `lockForUpdate`) untuk menjamin integritas data stok.
- **Frontend**: Blade Templating Engine dipadukan dengan **Tailwind CSS** untuk antarmuka yang responsif (*Mobile-First*).
- **Autentikasi & Otorisasi**: Laravel Sanctum (API) dan Spatie Laravel Permission (RBAC Web + API).
- **Pengujian (Testing)**: Automated Feature Testing menggunakan PHPUnit (`AdminTest.php` dan `UserTest.php`) — 7 tests, 14 assertions, 100% PASS.
- **Scheduler**: Artisan Command `laptop:check-expired` berjalan harian untuk deteksi sewa overdue.

---

## 5. Keamanan (Security Audit)

| Aspek | Status | Implementasi |
|-------|--------|-------------|
| SQL Injection | ✅ Kebal | Eloquent parameter binding |
| XSS (Web) | ✅ Kebal | Blade `{{ }}` auto-escaping + CSP |
| CSRF | ✅ Aktif | `@csrf` di semua form |
| Mass Assignment | ✅ Kebal | `$fillable` whitelist di semua model |
| IDOR | ✅ Kebal | Authorization Policy (`LaptopPolicy`, `TransactionPolicy`) |
| Brute Force | ✅ Kebal | Rate limiter `throttle:5,1` |
| API Flood | ✅ Kebal | Rate limiter `throttle:api` (60/min) + katalog (60/min) |
| Race Condition | ✅ Kebal | `DB::beginTransaction()` + `lockForUpdate()` |
| Broken Access Control | ✅ Kebal | `role:admin` middleware + Policy |
| Enum Injection | ✅ Kebal | `in:ready,maintenance,empty,not_ready` validation |
| Security Headers | ✅ Aktif | `SetSecurityHeaders` (CSP, X-Frame-Options, dll) |
| Force HTTPS | ✅ Aktif | `ForceHttps` middleware (production) |
| File Upload | ✅ Aman | `.htaccess` mencegah eksekusi script |
| Session Security | ✅ Aman | Invalidate + regenerateToken on logout |
| Password Policy | ✅ Ketat | `Password::defaults()->mixedCase()->numbers()->symbols()` |
| Sanctum Token Expiry | ✅ 24 jam | `SANCTUM_TOKEN_EXPIRATION` |

---

## 6. Keunggulan Kompetitif (Nilai Plus)

1. **Integritas Data Tinggi**: Menggunakan transaksi atomik + row locking untuk mencegah *race condition* dan *overselling* pada stok barang.
2. **Keamanan Berlapis**: Mitigasi OWASP Top 10 (SQLi, XSS, IDOR, Brute Force, Broken Access Control, Mass Assignment).
3. **Teruji Secara Otomatis**: Dilengkapi dengan *test suite* mandiri untuk memastikan fitur kritikal berjalan tanpa kesalahan (7 tests, 14 assertions, < 2 detik).
4. **Desain Profesional**: Antarmuka bergaya korporat (*SaaS Style*) dengan Dark/Light mode, responsive design, dan print-friendly layout.
5. **API Production-Ready**: RESTful API dengan Sanctum token, rate limiter, role-based access, dan logging.
6. **Validasi Real-World**: Serial number validation berdasarkan spesifikasi resmi manufacturer (Dell, Lenovo, HP, Acer, Asus, Apple, MSI, Gigabyte).
7. **Dokumentasi Lengkap**: `struktur.md` dan `deskripsi.md` dengan detail arsitektur dan spesifikasi teknis.

---

## 7. Struktur Proyek

```
sewa-laptop/
├── app/Http/Controllers/     # 12 controllers (Web + API)
├── app/Http/Middleware/      # LogRequests, SetSecurityHeaders, ForceHttps
├── app/Policies/             # LaptopPolicy, TransactionPolicy
├── app/Models/               # 5 models (User, Laptop, Transaction, Review, UnitInspection)
├── database/migrations/      # 21+ migration files (incl. indexes)
├── database/seeders/         # DatabaseSeeder + LaptopSeeder (34 laptops)
├── resources/views/          # 18 Blade templates (Dark mode support)
├── routes/                   # web.php + api.php
├── tests/Feature/            # AdminTest.php + UserTest.php
├── DEPLOYMENT.md             # Panduan operasional & deployment
├── struktur.md               # Dokumentasi struktur proyek
└── deskripsi.md              # Dokumentasi deskripsi proyek
```

---

## 8. Kredensial Default

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@sewa.com` | `Admin123@` |
| User  | `user@sewa.com`  | `UserLaptop@2024` |

---

## 9. Cara Menjalankan

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Migrate & seed database
php artisan migrate:fresh --seed

# 4. Jalankan server
php artisan serve

# 5. Jalankan tests
php artisan test
```

---

## 9b. Deployment & Operasional

Lihat `DEPLOYMENT.md` untuk panduan lengkap. Ringkasan:
- Environment production: `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`
- Cron job: `* * * * * php artisan schedule:run`
- Storage link: `php artisan storage:link`
- Optimization: `php artisan optimize`

---

## 10. Git History (Commit Terakhir)

```
5e4b7f5 fix: add dark mode to edit_role, edit laptop, and remaining admin pages
d605486 feat: add pagination (5 per page) to user transaction history
5aedfcc fix: add pagination links to stock management page
3756961 fix: change User::all() to User::paginate(10)
0f2cd0e feat: add dark mode to all admin pages, create form, and improve UI consistency
a1c0e80 feat: add dark mode support to all views, improve UI consistency
5ea9f2b feat: add dark/light mode toggle, upgrade UI with Plus Jakarta Sans
2fc8f01 style: upgrade UI/UX with Plus Jakarta Sans, professional navbar, footer, and dashboard icons
72630ee feat: finalize security audit, fix API authorization, race condition, and serial number validation
```
