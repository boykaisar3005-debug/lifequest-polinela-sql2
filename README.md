# LifeQuest: Sistem Manajemen Tugas & Misi Harian Berbasis Gamifikasi
[![Platform](https://img.shields.io/badge/Platform-Web-orange.svg)]()
[![PHP Version](https://img.shields.io/badge/PHP-8.2-blue.svg)]()
[![Database](https://img.shields.io/badge/Database-MySQL-blue.svg)]()
[![Theme](https://img.shields.io/badge/Theme-Cozy%20Glassmorphism-purple.svg)]()

**LifeQuest** adalah aplikasi manajemen tugas harian (*to-do list*) interaktif yang memadukan produktivitas dengan konsep **gamifikasi**. Pengguna dapat mengelola tugas kuliah/kerjaan (*Tasks*) dan misi produktivitas harian (*Quests*) untuk mendapatkan poin Pengalaman (*Experience Points* - XP) guna meningkatkan Level karakter pengguna.
Proyek ini dikembangkan sebagai **Laporan Final Project Pemrograman SQL II** pada **Program Studi Manajemen Informatika, Jurusan Teknologi Informasi, Politeknik Negeri Lampung**.
---
## 👥 Identitas Mahasiswa
* **Nama:** M. Chaesar Abdullah
* **NIM:** 24781076
* **Program Studi:** Manajemen Informatika
* **Dosen Pengampu:** M Reza Redo Islami, S.kom., M.T.I.
* **Institusi:** Politeknik Negeri Lampung
* **Tautan Repository:** [GitHub Repository](https://github.com/boykaisar3005-debug/lifequest-polinela-sql2.git)
* **Tautan Video Demo:** [YouTube Video Demo](https://youtu.be/pCh4sWT2Uqo)
---
## ✨ Fitur Utama
1. **Gamifikasi Produktivitas (Leveling & XP)**: 
   - Menyelesaikan Tugas memberikan reward **+10 XP**.
   - Menyelesaikan Misi Harian (Quest) memberikan reward XP sesuai tingkat kesulitan quest.
   - Peningkatan Level otomatis dihitung menggunakan formula: `Level = floor(XP / 100) + 1`.
   - Dasbor dilengkapi visualisasi *dynamic circular XP progress ring*.
2. **Manajemen Tugas Lengkap (CRUD)**:
   - Tambah, Lihat, Edit (Judul, Kategori, Deskripsi, Deadline, Status), dan Hapus Tugas.
3. **Pencarian & Filter Instan**:
   - Pencarian judul/deskripsi tugas secara langsung (*real-time text matching*).
   - Filter tugas berdasarkan kategori dan status tugas secara instan tanpa memuat ulang halaman (*client-side filtering*).
4. **Manajemen Kategori & Master Quest**:
   - Menu pengelolaan kategori mandiri.
   - Menu penambahan master quest baru lengkap dengan pengaturan besaran reward XP.
5. **Modern Glassmorphism UI & Persistent Dark Mode**:
   - Tampilan bersih transparan berbasis kartu kaca (*glass cards*) dengan transisi halus.
   - Pengubah tema gelap/terang (*theme switcher*) yang tersimpan secara persisten pada browser menggunakan `localStorage`.
6. **Smart Environment Auto-detection**:
   - File konfigurasi database secara cerdas membedakan apakah aplikasi sedang dijalankan di lingkungan lokal (**XAMPP**) atau di web hosting online (**InfinityFree**), sehingga *source code* siap langsung dideploy tanpa harus merubah konfigurasi manual secara terus menerus.
---
## 🛠️ Tech Stack
* **Frontend**: HTML5, CSS3 (Vanilla dengan CSS Variables), Vanilla JavaScript.
* **Backend**: PHP 8.2+ (PHP Native).
* **Database**: MySQL.
* **Server Lokal**: Apache (via XAMPP).
* **Web Hosting**: InfinityFree.
---
## 📂 Struktur Folder Proyek
```
lifequest/
│
├── assets/
│   ├── css/
│   │   └── style.css      # Core Design System, Glassmorphism, & Tema
│   └── js/
│       └── main.js       # Toast Alert, Jam Digital, XP Ring, & Dark Mode
│
├── config/
│   └── koneksi.php       # Konektor MySQL dinamis (Lokal vs Hosting)
│
├── tugas/
│   ├── index.php         # Daftar tugas, panel pencarian, & filter instan
│   ├── tambah.php        # Form tambah tugas baru
│   └── edit.php          # Form edit detail tugas & status
│
├── kategori/
│   └── index.php         # Panel CRUD Kategori Tugas
│
├── quest/
│   └── index.php         # Panel CRUD Master Quest Harian
│
├── database.sql          # Skema database relasional & seeder data contoh
├── index.php             # Dashboard Utama Aplikasi
├── selesai.php           # Proses penyelesaian tugas (+10 XP)
├── quest_selesai.php     # Proses penyelesaian quest (+XP Reward)
├── reset_quest.php       # Proses reset harian quest
└── hapus.php             # Proses hapus tugas
```
---
## 💾 Skema Database
Aplikasi ini berjalan di atas 5 tabel relasional dengan integritas data referensial:
* **Tabel_users**: Menyimpan profil tunggal pengguna dan akumulasi poin pengalaman (`xp`).
* **Tabel_kategori**: Menyimpan daftar kategori klasifikasi tugas.
* **Tabel_tugas**: Menyimpan daftar tugas, deskripsi, tanggal deadline, status, serta relasi foreign key ke user dan kategori.
* **Tabel_quest**: Menyimpan kumpulan daftar quest produktivitas harian.
* **Tabel_progress**: Log pencatatan transaksi tanggal penyelesaian tugas dan quest.
---
## 🚀 Panduan Instalasi Lokal (XAMPP)
1. **Persiapan Berkas**:
   - Clone repository ini ke dalam folder root XAMPP Anda (biasanya di `C:\xampp\htdocs\lifequest\`).
     ```bash
     git clone https://github.com/boykaisar3005-debug/lifequest-polinela-sql2.git
     ```
2. **Jalankan XAMPP**:
   - Buka XAMPP Control Panel, lalu aktifkan modul **Apache** dan **MySQL**.
3. **Import Database**:
   - Buka browser dan akses **[http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)**.
   - Buat database baru dengan nama `lifequest`.
   - Pilih database `lifequest`, masuk ke tab **Import**, pilih berkas **[database.sql](database.sql)** yang berada di folder proyek, lalu klik **Kirim/Go**.
4. **Jalankan Aplikasi**:
   - Akses aplikasi di browser melalui tautan: **[http://localhost/lifequest/](http://localhost/lifequest/)**.
---
## 🌐 Panduan Deployment (InfinityFree)
1. Ekspor database lokal Anda atau gunakan berkas `database.sql`.
2. Buat akun baru di **InfinityFree** dan buat database MySQL baru melalui control panel hosting.
3. Import database Anda ke phpMyAdmin online milik InfinityFree.
4. Upload semua file dalam folder proyek ini langsung ke folder `/htdocs` menggunakan File Manager hosting atau FTP client (seperti FileZilla).
5. Edit file `config/koneksi.php` pada bagian `else` (baris 13-17) untuk memasukkan kredensial database akun hosting baru Anda:
   ```php
   $host = "sqlXXX.infinityfree.com"; // MySQL Hostname Anda
   $user = "if0_XXXXXXXX";           // MySQL Username Anda
   $pass = "PASSWORD_ANDA";          // MySQL Password Anda
   $db   = "if0_XXXXXXXX_database";  // Nama Database Hosting Anda
   ```
6. Akses domain gratis yang diberikan oleh InfinityFree untuk mulai menggunakan aplikasi secara online!
