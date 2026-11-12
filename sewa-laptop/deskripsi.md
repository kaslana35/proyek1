# Deskripsi Proyek: Sewa Laptop Enterprise Core

## 1. Ringkasan Eksekutif
**Sewa Laptop Enterprise Core** adalah aplikasi web berbasis *Full-Stack* yang dirancang untuk mengotomatisasi dan mengelola proses bisnis penyewaan perangkat keras komputer (laptop) secara terintegrasi. Sistem ini menyediakan alur kerja menyeluruh (*end-to-end*), mulai dari katalog produk publik, manajemen inventaris fisik, siklus pemesanan, hingga pencatatan ulasan dan pelaporan operasional yang akurat.

---

## 2. Latar Belakang & Tujuan
Pengelolaan penyewaan laptop secara konvensional seringkali menghadapi kendala seperti inkonsistensi stok, kesalahan pencatatan transaksi manual, kesulitan melacak riwayat kondisi fisik unit, hingga lemahnya batasan akses antara admin dan penyewa. 

Proyek ini dibangun untuk mengatasi masalah tersebut dengan menerapkan:
- **Otomatisasi Inventaris**: Mencegah stok minus dan menyelaraskan status unit secara *real-time*.
- **Pemisahan Peran yang Ketat (RBAC)**: Memastikan keamanan data antara administrator dan pengguna biasa.
- **Standar Keamanan Industri**: Mencegah kerentanan umum seperti *Insecure Direct Object Reference* (IDOR), *Cross-Site Scripting* (XSS), dan *Brute Force / API Flooding*.

---

## 3. Fitur Utama Sistem

### A. Sisi Pengguna (Penyewa / Guest)
- **Katalog Publik & Pencarian**: Menjelajahi daftar laptop lengkap dengan spesifikasi rinci, harga sewa per hari, dan indikator ketersediaan stok.
- **Sistem Pemesanan (Sewa)**: Memilih rentang tanggal sewa dengan validasi durasi otomatis dan persetujuan syarat & ketentuan.
- **Riwayat & Cetak Bukti Sewa**: Memantau status pemesanan (Pending, Approved, Returned, Cancelled) serta mencetak kwitansi/bukti transaksi resmi.
- **Ulasan & Penilaian (Review)**: Memberikan rating bintang (1-5) dan komentar testimoni pada laptop yang pernah disewa.

### B. Sisi Administrator (Manajemen Sistem)
- **Dashboard Eksekutif**: Menampilkan ringkasan transaksi pending, unit dalam perbaikan (*maintenance*), grafik pendapatan bulanan, serta *Low Stock Alert*.
- **Manajemen Produk & Validasi Serial Number**: Pengelolaan inventaris laptop yang dilengkapi validasi ketat panjang karakter *Serial Number* berdasarkan merek (Dell, Lenovo, HP, Asus, Apple, dll).
- **Manajemen Stok & Audit Unit**: Memindahkan status unit fisik (*Ready, Maintenance, Not Ready*) lengkap dengan log audit perpindahan (*Unit Inspections*).
- **Manajemen Transaksi**: Menyetujui, menyelesaikan (pengembalian unit & pemulihan stok otomatis), atau membatalkan pesanan, serta mencetak laporan rekapitulasi operasional.
- **Manajemen Pengguna**: Pengelolaan data pengguna dan hak akses peran berbasis **Spatie Laravel Permission**.

### C. Layanan API (Backend Restful)
- **Otorisasi Token (Laravel Sanctum)**: Menyediakan *endpoint* autentikasi dan transaksi berbasis token untuk integrasi dengan aplikasi eksternal atau *Mobile App*.
- **Rate Limiter / Anti-Flood**: Proteksi pembatasan laju akses (*throttle*) pada rute login dan API untuk menangkal serangan *brute force*.

---

## 4. Arsitektur & Teknologi yang Digunakan
- **Backend Framework**: Laravel 13 (PHP 8.4) dengan pola arsitektur **MVC** (*Model-View-Controller*).
- **Database**: MySQL dengan *Atomic Transactions* (`DB::beginTransaction`) untuk menjamin integritas data stok.
- **Frontend**: Blade Templating Engine dipadukan dengan **Tailwind CSS** untuk antarmuka yang responsif (*Mobile-First*).
- **Autentikasi & Otorisasi**: Laravel Sanctum (API) dan Spatie Laravel Permission (RBAC).
- **Pengujian (Testing)**: Automated Feature Testing menggunakan PHPUnit (`AdminTest.php` dan `UserTest.php`).

---

## 5. Keunggulan Kompetitif (Nilai Plus untuk Sidang)
1. **Integritas Data Tinggi**: Menggunakan transaksi atomik untuk mencegah *race condition* pada stok barang.
2. **Keamanan Berlapis**: Memiliki mitigasi IDOR, XSS *auto-escaping*, dan validasi input yang ketat pada level *Form Request*.
3. **Teruji Secara Otomatis**: Dilengkapi dengan *test suite* mandiri untuk memastikan fitur kritikal (admin & user) berjalan tanpa kesalahan.
4. **Desain Profesional**: Antarmuka bergaya korporat (*SaaS Style*) yang mendukung mode cetak bersih (*print-friendly*) tanpa elemen navigasi.
