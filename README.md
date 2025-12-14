# ProjectAkhir_PAW

Proyek Akhir Mata Kuliah Pengembangan Aplikasi Web (PAW)

## 📋 Deskripsi

Aplikasi web manajemen investasi dan marketplace finansial yang dibangun menggunakan PHP native dengan arsitektur MVC (Model-View-Controller). Aplikasi ini menyediakan platform lengkap untuk mengelola portofolio investasi, marketplace produk investasi, membaca artikel edukasi finansial, serta panel admin untuk mengelola konten. Dilengkapi dengan sistem pembayaran dan manajemen profil pengguna.

## 🚀 Teknologi yang Digunakan

- *Backend*: PHP
- *Frontend*: HTML, CSS, JavaScript
- *Database*: MySQL/MariaDB
- *Arsitektur*: MVC Pattern

👥 Default User Accounts
Admin Account
Email: ayudia@gmail.com
Password: sefina123
Access: Full admin dashboard and product management

Regular User Account
Email: sefinasyauqi@gmail.com
Password: ayudia123
Access: User profile,Artikel,Marketplace,Investasi

```
ProjectAkhir_PAW/
├── assets/                           # Asset statis aplikasi
│   ├── css/                          # Stylesheet
│   │   ├── about.css
│   │   ├── add_article.css
│   │   ├── admin-marketplace.css
│   │   ├── admin_add_article.css
│   │   ├── admin_articles.css
│   │   ├── admin_edit_article.css
│   │   ├── admin_investasi.css
│   │   ├── article_detail.css
│   │   ├── articles.css
│   │   ├── forgot_password.css
│   │   ├── investasi.css
│   │   ├── marketplace.css
│   │   ├── profile.css
│   │   └── register.css
│   └── js/                           # JavaScript files
│       ├── Marketplace.js
│       ├── about.js
│       ├── add_article.js
│       ├── admin-articles.js
│       ├── admin-investasi.js
│       ├── admin-marketplace.js
│       ├── admin_add_article.js
│       ├── admin_edit_article.js
│       ├── article.js
│       ├── article_detail.js
│       ├── forgot_password.js
│       ├── investasi.js
│       ├── profile.js
│       └── register.js
├── backend/                          # Backend logic
│   ├── config.php                    # Konfigurasi database
│   └── update_profile.php            # Update profil user
├── controllers/                      # Controllers (MVC)
│   ├── AdminInvestasi_Controller.php
│   ├── AdminMarketplace_Controller.php
│   ├── Investasi_Controller.php
│   ├── Marketplace_Controller.php
│   ├── PassworsReset_Controller.php
│   ├── Payment_Controller.php
│   ├── about_Controller.php
│   ├── add_article.php
│   ├── admin_add_article.php
│   ├── admin_article_detail.php
│   ├── admin_articles.php
│   ├── admin_edit_article.php
│   ├── article.php
│   ├── article_detail.php
│   └── articles.php
├── public/                           # File publik dan views
│   ├── folderimage/                  # Folder untuk gambar
│   ├── about.html
│   ├── add_article.html
│   ├── admin-investasi.html
│   ├── admin-marketplace.html
│   ├── admin.html
│   ├── admin_add_article.html
│   ├── admin_article_detail.html
│   ├── admin_articles.html
│   ├── admin_edit_article.html
│   ├── article.html
│   ├── article_detail.html
│   ├── home.php
│   ├── investasi.html
│   ├── marketplace.html
│   └── profile.html
├── index.php                         # Landing page / Entry point
└── logout.php                        # Script logout
```

## 🛠 Instalasi

### Prasyarat

- PHP 7.4 atau lebih tinggi
- MySQL/MariaDB
- Web Server (Apache/Nginx) atau PHP Built-in Server
- Composer 

### Langkah-langkah Instalasi

1. *Clone repository*
   bash
   git clone https://github.com/nailakeisha/ProjectAkhir_PAW.git
   cd ProjectAkhir_PAW
   

2. *Konfigurasi Database*
   - Buat database baru di MySQL/MariaDB
   sql
   CREATE DATABASE projectakhir_paw;
   
   - Import file SQL
   - Buka file backend/config.php
   - Sesuaikan konfigurasi database:
   php
   <?php
   $host = "localhost";
   $username = "root";
   $password = "";
   $database = "niseva_agro";
   ?>
   

3. *Konfigurasi File*
   - Pastikan folder public/folderimage/ memiliki permission write
   bash
   chmod 755 public/folderimage/
   
   - Sesuaikan path di file controllers jika diperlukan

4. *Jalankan Aplikasi*
   
   Menggunakan PHP Built-in Server:
   bash
   php -S localhost:8000
   
   
   Atau deploy ke web server (Apache):
   - Arahkan document root ke direktori project
   - Pastikan mod_rewrite sudah aktif (untuk Apache)

5. *Akses Aplikasi*
   
   http://localhost:8000
   http://niseva-agro.infinityfree.me


## 💡 Fitur Utama

### 👥 User Features
- 🔐 *Sistem Autentikasi* 
  - Login dan Register
  - Forgot Password & Password Reset
  - Logout aman
- 🏠 *Home/Dashboard* - Halaman utama dengan informasi ringkasan
- 💰 *Investasi* - Manajemen dan tracking portofolio investasi
- 🛒 *Marketplace* - Platform jual beli produk investasi
- 💳 *Payment System* - Sistem pembayaran terintegrasi
- 📰 *Artikel* 
  - Browse artikel edukasi finansial
  - Baca detail artikel
  - Tambah artikel (untuk user tertentu)
- 👤 *Profil User* - Kelola informasi profil dan foto
- ℹ *About* - Informasi tentang aplikasi

### 🔧 Admin Features
- 📊 *Admin Dashboard* - Panel kontrol admin
- 📝 *Manajemen Artikel*
  - Lihat semua artikel
  - Tambah artikel baru
  - Edit artikel
  - Detail artikel
  - Hapus artikel
- 💼 *Manajemen Investasi* - Kelola data investasi
- 🏪 *Manajemen Marketplace* - Kelola produk marketplace

### 🎨 Additional Features
- 🎨 *Responsive Design* - Tampilan optimal di berbagai perangkat
- 🔒 *Keamanan Data* - Proteksi data pengguna dan transaksi
- ⚡ *Dynamic JavaScript* - Interaksi yang smooth dan responsif

## 📖 Cara Penggunaan

### 👤 Untuk User

#### 1. Registrasi & Login
- Akses halaman utama aplikasi
- Klik "Register" untuk membuat akun baru
- Isi form registrasi dengan lengkap
- Login menggunakan username dan password

#### 2. Home/Dashboard
- Lihat ringkasan informasi utama
- Akses quick links ke fitur-fitur utama

#### 3. Investasi
- *Lihat Portfolio*: Monitor semua investasi yang dimiliki
- *Tambah Investasi*: investasi baru
- *Analisis*: Lihat performa investasi

#### 4. Marketplace
- Browse produk investasi yang tersedia
- Lihat detail produk
- Lakukan pembelian melalui sistem payment
- Track order dan transaksi

#### 5. Artikel
- Baca artikel lengkap dengan tips dan panduan
- Lihat detail artikel

#### 6. Profil User
- Lihat informasi profil
- Ubah password
- Kelola pengaturan akun

#### 7. Lupa Password
- Klik "Forgot Password" di halaman login
- Ikuti instruksi reset password
- Masukkan password baru

### 🔧 Untuk Admin

#### 1. Admin Dashboard
- Login sebagai admin
- Akses panel kontrol admin
- Lihat statistik dan overview sistem

#### 2. Manajemen Artikel
- *Lihat Artikel*: Browse semua artikel di sistem
- *Tambah Artikel*: Buat artikel baru
- *Edit Artikel*: Update konten artikel
- *Detail Artikel*: Lihat informasi lengkap
- *Hapus Artikel*: Remove artikel dari sistem

#### 3. Manajemen Investasi
- Kelola data investasi user
- Monitor performa investasi
- Update informasi produk investasi

#### 4. Manajemen Marketplace
- Kelola produk di marketplace
- Update harga dan stok
- Monitor transaksi
- Manajemen vendor/seller

### 🚪 Logout
- Klik tombol Logout untuk mengakhiri sesi
- Data Anda tetap aman tersimpan

## 🔒 Keamanan

- Password di-hash menggunakan algoritma yang aman
- Validasi input untuk mencegah SQL Injection
- Session management untuk autentikasi

##  🚢 Deployment
http://niseva-agro.infinityfree.me

## 📝 Catatan Pengembangan

### Kontributor
- [nailakeisha](https://github.com/nailakeisha)
- [Nikita Salsabila](https://github.com/nikitaasl)
- [Sefina Ayudia]

### Versi
- Current: 1.0.0

## 🤝 Kontribusi

Kontribusi, issues, dan feature requests sangat diterima!

1. Fork project ini
2. Buat branch baru (git checkout -b feature/AmazingFeature)
3. Commit perubahan (git commit -m 'Add some AmazingFeature')
4. Push ke branch (git push origin feature/AmazingFeature)
5. Buat Pull Request

## 📄 Lisensi

Project ini dibuat untuk keperluan akademis sebagai tugas akhir mata kuliah Pengembangan Aplikasi Web.

## 📧 Kontak

Nailakeisha - [@nailakeisha](https://github.com/nailakeisha)

Project Link: [https://github.com/nailakeisha/ProjectAkhir_PAW](https://github.com/nailakeisha/ProjectAkhir_PAW)

## 🙏 Acknowledgments
All open-source contributors



---

