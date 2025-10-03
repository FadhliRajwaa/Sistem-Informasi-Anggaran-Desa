# 📋 Panduan Instalasi dan Penggunaan 
## Sistem Informasi Transparansi Anggaran dan Pembangunan Desa

---

## 🎯 Persyaratan Sistem

- **XAMPP** (Apache + MySQL + PHP 7.4+)
- **Web Browser** modern (Chrome, Firefox, Edge)
- **File Editor** (Notepad++, VS Code, dll)

---

## 🚀 Langkah Instalasi

### 1. **Persiapan XAMPP**
- Download dan install XAMPP dari [https://www.apachefriends.org](https://www.apachefriends.org)
- Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel
- Pastikan port 80 (Apache) dan 3306 (MySQL) tidak bentrok

### 2. **Setup Database**
```sql
-- Buka phpMyAdmin di http://localhost/phpmyadmin
-- Jalankan query berikut untuk membuat database:

CREATE DATABASE IF NOT EXISTS transparansi_desa;
USE transparansi_desa;
```

### 3. **Import Database**
- Buka file `database.sql` di root project
- Copy semua isinya dan paste di phpMyAdmin → SQL tab
- Klik **Go** untuk menjalankan
- Database akan terisi dengan struktur tabel dan data contoh

### 4. **Update Database (PENTING!)**
- Setelah import `database.sql`, jalankan `database_update.sql`
- File ini berisi:
  - Update kolom tabel evaluasi (nama, kontak, kategori, status)
  - Tabel referensi jenis anggaran
  - Update password admin dengan hash yang aman
  - Index untuk performa
  - View untuk laporan

```sql
-- Di phpMyAdmin, jalankan database_update.sql:
-- 1. Buka tab SQL
-- 2. Copy paste isi database_update.sql 
-- 3. Klik Go
```

### 5. **Konfigurasi Koneksi Database**
File `includes/db.php` sudah dikonfigurasi default untuk XAMPP:
```php
$host = "localhost";
$user = "root"; 
$pass = "";
$db   = "transparansi_desa";
```

### 6. **Testing Instalasi**
- Buka browser dan akses: `http://localhost/transparansi_desa`
- Jika berhasil, Anda akan melihat halaman beranda sistem

---

## 🔐 Login Admin

**URL:** `http://localhost/transparansi_desa/admin/login.php`

**Default Account:**
- Username: `admin`
- Password: `admin123`

> ⚠️ **Penting:** Ganti password default setelah login pertama!

---

## 📊 Struktur Menu dan Fitur

### **🌐 Halaman Publik**

#### 1. **Beranda (`index.php`)**
- Statistik keseluruhan sistem
- Form pencarian desa berdasarkan kecamatan
- Informasi umum tentang transparansi

#### 2. **Detail Desa (`desa.php?id_desa=X`)**
Dengan 4 tab navigasi:
- **Profil Desa:** Info dasar desa dan ringkasan
- **Jenis Anggaran:** Tabel anggaran per tahun dan jenis
- **Pembangunan:** Data proyek dan realisasi
- **Evaluasi:** Daftar evaluasi approved + form kirim baru

#### 3. **Evaluasi Publik (`evaluasi.php`)**
- Form untuk masyarakat kirim aspirasi/laporan
- Kategorisasi: Pelaporan Masalah, Pemantauan Proyek, Saran Perbaikan, Aspirasi Masyarakat
- Data pending moderasi admin

### **🔒 Panel Admin**

#### 1. **Dashboard (`admin/dashboard.php`)**
Interface tab-based dengan statistik:
- **Dashboard:** Ringkasan data dan statistik
- **Kelola Desa:** CRUD data desa (nama, kecamatan, kepala desa)
- **Kelola Anggaran:** CRUD anggaran dengan jenis standar
- **Kelola Pembangunan:** CRUD proyek pembangunan
- **Kelola Evaluasi:** Moderasi evaluasi (approve/reject/edit)

#### 2. **Import Data CSV (`admin/import_data.php`)**
- Upload file CSV untuk import massal
- Template tersedia untuk setiap jenis data
- Validasi format dan error handling

---

## 📁 Upload Data Client

### **Template CSV yang Disediakan:**

#### 1. **Template Desa** (`template_desa.csv`)
```csv
Nama Desa,Kecamatan,Kepala Desa
Suka Damai,Ujung Batu,H. Ahmad Suryadi
```

#### 2. **Template Anggaran** (`template_anggaran.csv`)
```csv
Kecamatan,Nama Desa,Jenis Anggaran,Tahun,Jumlah
Ujung Batu,Suka Damai,Dana Desa,2024,850000000
```

#### 3. **Template Pembangunan** (`template_pembangunan.csv`)
```csv
Kecamatan,Nama Desa,Tahun,Kegiatan,Lokasi,Realisasi
Ujung Batu,Suka Damai,2024,Pembangunan Jalan Desa,RT 01/RW 03,350000000
```

#### 4. **Template Evaluasi** (`template_evaluasi.csv`)
```csv
Kecamatan,Nama Desa,Nama Pelapor,Kategori,Laporan,Kontak
Ujung Batu,Suka Damai,Ahmad Hidayat,Pemantauan Proyek,Proyek jalan desa sudah berjalan dengan baik,081234567890
```

### **Cara Import Data:**
1. Login sebagai admin
2. Klik menu **"Import Data CSV"**
3. Pilih jenis data yang akan diimport
4. Upload file CSV sesuai template
5. Sistem akan validasi dan import otomatis

---

## 🎨 Jenis Anggaran Standar

Sistem menggunakan 7 kategori anggaran sesuai regulasi:

1. **Dana Desa** (prioritas 1)
2. **Alokasi Dana Desa** (prioritas 2)  
3. **Bantuan Keuangan Provinsi** (prioritas 3)
4. **Swadaya Masyarakat** (prioritas 4)
5. **Bagi Hasil Pajak** (prioritas 5)
6. **Pendapatan Asli Desa** (prioritas 6)
7. **Lain-lain** (prioritas 7)

---

## 🔧 Teknologi yang Digunakan

- **Backend:** PHP 7.4+ dengan MySQLi
- **Frontend:** Bootstrap 5.3, Font Awesome 6.0
- **Database:** MySQL/MariaDB
- **Security:** Prepared statements, password hashing, input sanitization
- **UI Components:** DataTables, responsive design

---

## 🚨 Troubleshooting

### **Problem: Database connection failed**
- Pastikan MySQL service jalan di XAMPP
- Cek konfigurasi di `includes/db.php`
- Pastikan database `transparansi_desa` sudah dibuat

### **Problem: Login admin gagal**
- Pastikan sudah jalankan `database_update.sql`
- Coba reset password admin:
```sql
UPDATE admin SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE username = 'admin';
```

### **Problem: Error saat import CSV**
- Pastikan format CSV sesuai template
- Pastikan encoding UTF-8
- Data desa harus ada sebelum import anggaran/pembangunan

### **Problem: Halaman tidak muncul dengan benar**
- Clear browser cache
- Pastikan semua file ter-upload dengan benar
- Cek file permissions (755 untuk folder, 644 untuk file)

---

## 📞 Kontak Support

Jika mengalami kendala teknis:
1. Cek file error log di `xampp/apache/logs/error.log`
2. Screenshot error message
3. Catat langkah yang menyebabkan error

---

## 🔄 Update dan Maintenance

### **Backup Database:**
```sql
-- Export melalui phpMyAdmin:
-- 1. Pilih database transparansi_desa
-- 2. Tab Export → Quick → SQL → Go
```

### **Update Password Admin:**
```sql
-- Ganti 'password_baru' dengan password yang diinginkan:
UPDATE admin SET password = PASSWORD('password_baru') WHERE username = 'admin';
```

---

**📋 Sistem Informasi Transparansi Desa v1.0**  
*Dikembangkan untuk meningkatkan akuntabilitas pemerintahan desa*
