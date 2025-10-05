# Sistem Transparansi Desa

Sistem web untuk transparansi data desa: informasi desa, anggaran, proyek pembangunan, serta kanal evaluasi/aspirasi dari masyarakat. Aplikasi ini menyediakan halaman publik yang elegan dan Panel Admin modern untuk mengelola data secara aman dan cepat.

---

## Fitur Utama
- **Publik**
  - **Beranda** (`index.php`) dengan ringkasan informasi.
  - **Daftar Desa** (`desa.php`) dengan pencarian/penyaringan sederhana.
  - **Kirim Evaluasi** (`evaluasi.php`) lengkap dengan modal sukses/gagal dan overlay loading.
- **Admin Panel** (`admin/dashboard.php`)
  - Navigasi berbasis tab: `#desa`, `#anggaran`, `#pembangunan`, `#evaluasi`.
  - CRUD lengkap (Tambah, Edit, Hapus) untuk semua modul.
  - **Flash Modal** (sukses/gagal/peringatan) pasca operasi CRUD.
  - **Loading Overlay** global saat submit/aksi penting.
  - Redirect cerdas pasca CRUD ke tab yang relevan (mis. `#pembangunan`).
  - Autentikasi admin (`admin/login.php`).

---

## Teknologi
- **Backend**: PHP (mysqli, prepared statements), Session handler kustom (simpan di DB).
- **Database**: MySQL/MariaDB.
- **Frontend**: Tailwind CSS (CDN), Font Awesome (ikon), komponen UI ringan vanilla JS.
- **Deploy-ready**: Konfigurasi environment variable untuk koneksi DB dan opsi SSL (Aiven/Vercel).

---

## Struktur Proyek (ringkas)
- `admin/` — Panel Admin (dashboard, login, halaman CRUD).
- `includes/` — Koneksi DB (`db.php`), session handler (`session.php`), flash helper (`flash.php`), overlay UI (`ui.php`).
- `css/`, `api/`, `tools/` — aset/perkakas pendukung.
- `sql/` — skrip seed contoh, mis. `sql/seed_rokanhulu_2025-10-03.sql`.
- `index.php`, `desa.php`, `evaluasi.php` — halaman publik.

---

## Setup Lokal (XAMPP)
- **Prasyarat**: XAMPP (Apache + PHP + MySQL/MariaDB), Git (opsional).
- **Letakkan folder** ini ke `htdocs` (mis. `C:/Xampp/htdocs/transparansi_desa`).
- **Buat database** (default: `transparansi_desa`).
- **Import skema**:
  - `database.sql` lalu `database_update.sql` (jika ada perubahan tambahan).
  - Opsional: seed contoh `sql/seed_rokanhulu_2025-10-03.sql`.
- **Buat akun admin** (opsi cepat menggunakan MD5 legacy yang didukung login):
  ```sql
  INSERT INTO admin (username, password)
  VALUES ('admin', MD5('admin123'));
  ```
  Setelah login, harap ubah ke password hash modern via fitur aplikasi atau update manual ke `password_hash`.
- **Jalankan**:
  - Publik: `http://localhost/transparansi_desa/`
  - Admin: `http://localhost/transparansi_desa/admin/login.php`

> Catatan: File `includes/db.php` memakai environment variable bila tersedia, jika tidak akan jatuh ke default lokal: `localhost:3306`, user `root`, password kosong, DB `transparansi_desa`.

---

## Konfigurasi Koneksi Database (Environment Variable)
`includes/db.php` membaca ENV berikut (opsional):
- `DB_HOST` (default `localhost`)
- `DB_PORT` (default `3306`)
- `DB_USER` (default `root`)
- `DB_PASS` (default kosong)
- `DB_NAME` (default `transparansi_desa`)
- `DB_SSL_MODE` (`DISABLED` | `REQUIRED`), default `DISABLED`
- `DB_SSL_CA` (opsional; isi string CA jika perlu verifikasi ketat)

Contoh `.htaccess` (opsional) untuk set ENV di Apache:
```apache
SetEnv DB_HOST your-db-host
SetEnv DB_USER your-db-user
SetEnv DB_PASS your-secret
SetEnv DB_NAME transparansi_desa
SetEnv DB_SSL_MODE DISABLED
```

---

## Pola Navigasi & UX Admin
- **Tab Dashboard** memakai hash URL. Contoh:
  - `dashboard.php#desa` → tab Desa aktif.
  - `dashboard.php#pembangunan` → tab Pembangunan aktif.
- **Pasca CRUD** diarahkan ke tab terkait agar alur kerja konsisten.
- **Tombol Kembali** pada setiap form admin mengarah ke tab terkait, bukan ke dashboard umum.
- **Flash Modal & Loading Overlay**:
  - `includes/flash.php` untuk set/get pesan satu kali (session).
  - `includes/ui.php` menyisipkan overlay loading dan auto-aktif saat submit form atau elemen berkelas `js-loading` diklik.

---

## Keamanan
- Query menggunakan **prepared statements**.
- Session disimpan di DB (`includes/session.php`) dengan cookie `HttpOnly`, `SameSite=Lax`, dan opsi `Secure` saat HTTPS.
- Disarankan mengaktifkan HTTPS dan mengganti kredensial default.

---

## Seed Data Contoh (Opsional)
Gunakan `sql/seed_rokanhulu_2025-10-03.sql` untuk:
- Koreksi penamaan desa tertentu.
- Memastikan entri desa tertentu ada (UPSERT).
- Men-generate dummy anggaran 2024 per desa bila belum ada.

Jalankan lewat phpMyAdmin/CLI setelah import skema dasar.

---

## Troubleshooting
- **Tombol tidak terlihat / warna tidak keluar**:
  - Pastikan tidak meng-override warna standar Tailwind (mis. palet `orange`). Di file, gunakan key berbeda seperti `brand-orange` saat extend warna, bukan `orange` langsung.
- **Overlay tidak hilang**:
  - Pastikan `includes/ui.php` di-include sebelum `</body>` sehingga script berjalan setelah DOM siap, dan gunakan class `hidden` sebagai default.
- **Selalu kembali ke dashboard umum**:
  - Pastikan URL redirect mengandung hash tab (mis. `dashboard.php#evaluasi`).

---

## Deploy Singkat (opsional, Vercel + MySQL/Aiven)
- Buat database MySQL terkelola (Aiven/PlanetScale/dll).
- Set ENV: `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`, serta `DB_SSL_MODE=REQUIRED` jika penyedia mewajibkan TLS.
- Import `database.sql` ke database tersebut.
- Pastikan runtime PHP yang Anda gunakan mendukung `mysqli`.

---

## Kontribusi
- Fork → buat branch fitur → PR.
- Sertakan deskripsi singkat perubahan dan alasan desain.

---

## Kredit
Didesain untuk mendorong transparansi informasi desa dan partisipasi publik.
