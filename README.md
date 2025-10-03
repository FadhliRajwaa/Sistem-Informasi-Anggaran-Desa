# 🏛️ Sistem Informasi Transparansi Anggaran dan Pembangunan Desa

Aplikasi web untuk meningkatkan transparansi dan akuntabilitas pengelolaan anggaran serta pembangunan di tingkat desa.

## ✨ Fitur Utama

### 🌐 **Akses Publik**
- **Pencarian Desa:** Cari informasi desa berdasarkan kecamatan
- **Transparansi Anggaran:** Lihat detail anggaran per jenis dan tahun
- **Data Pembangunan:** Informasi proyek pembangunan dan realisasinya
- **Evaluasi Masyarakat:** Platform aspirasi dan pelaporan untuk masyarakat

### 🔒 **Panel Administrator**
- **Dashboard:** Statistik dan ringkasan data
- **Manajemen Data:** CRUD lengkap untuk desa, anggaran, pembangunan, evaluasi
- **Import CSV:** Upload data massal menggunakan template Excel/CSV
- **Moderasi:** Kelola evaluasi masyarakat (approve/reject)

## 🚀 Quick Start

1. **Setup XAMPP:** Pastikan Apache dan MySQL berjalan
2. **Import Database:** Jalankan `database.sql` dan `database_update.sql`
3. **Akses Aplikasi:** Buka `http://localhost/transparansi_desa`

## 🔐 Login Admin
- **URL:** `/admin/login.php`
- **Username:** `admin`
- **Password:** `admin123`

## 📊 Jenis Anggaran Standar
1. Dana Desa
2. Alokasi Dana Desa (ADD)
3. Bantuan Keuangan Provinsi
4. Swadaya Masyarakat
5. Bagi Hasil Pajak
6. Pendapatan Asli Desa (PADes)
7. Lain-lain

## 📁 Struktur File

```
transparansi_desa/
├── admin/                 # Panel administrator
│   ├── login.php         # Login admin
│   ├── dashboard.php     # Dashboard utama
│   ├── import_data.php   # Import CSV
│   ├── templates/        # Template CSV
│   └── *.php            # File CRUD admin
├── includes/             # File utilities
│   ├── db.php           # Koneksi database
│   ├── auth.php         # Autentikasi
│   ├── header.php       # Header template
│   └── footer.php       # Footer template
├── css/                 # Stylesheet
├── index.php            # Halaman beranda
├── desa.php             # Detail desa dengan tab
├── evaluasi.php         # Form evaluasi publik
├── database.sql         # Struktur database + data
├── database_update.sql  # Update skema database
└── PANDUAN_INSTALASI.md # Dokumentasi lengkap
```

## 💡 Teknologi

- **Backend:** PHP 7.4+ dengan MySQLi prepared statements
- **Frontend:** Bootstrap 5.3, Font Awesome 6.0, DataTables
- **Database:** MySQL/MariaDB
- **Security:** Password hashing, input sanitization, XSS protection

## 📋 Template Import

Sistem menyediakan template CSV untuk import data massal:
- `template_desa.csv` - Data desa dan kepala desa
- `template_anggaran.csv` - Data anggaran per jenis dan tahun
- `template_pembangunan.csv` - Data proyek pembangunan
- `template_evaluasi.csv` - Data evaluasi dan aspirasi

## 🔗 URL Akses

- **Beranda:** `/`
- **Detail Desa:** `/desa.php?id_desa=[ID]`
- **Evaluasi Publik:** `/evaluasi.php`
- **Admin Panel:** `/admin/`

## 📖 Dokumentasi

Lihat file `PANDUAN_INSTALASI.md` untuk:
- Instalasi step-by-step
- Konfigurasi database
- Troubleshooting
- Maintenance

---

**🏛️ Sistem Informasi Transparansi Desa**  
*Mendukung Good Governance dan Akuntabilitas Pemerintahan Desa*
