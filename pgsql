/transparansi_desa/
│── index.php          (tampilan awal masyarakat pilih desa/kecamatan)
│── profil.php         (profil desa terpilih)
│── anggaran.php       (tampilkan data anggaran per desa)
│── pembangunan.php    (tampilkan data pembangunan per desa)
│── evaluasi.php       (tampilkan laporan evaluasi per desa)
│
├── /admin/
│   │── login.php
│   │── dashboard.php  (CRUD utama: desa, anggaran, pembangunan, evaluasi)
│   │── logout.php
│
├── /includes/
│   │── db.php         (koneksi database)
│   │── auth.php       (cek login admin)
│
├── /css/
│   │── style.css
│
└── database.sql

/admin/
│── login.php
│── logout.php
│── dashboard.php
│
│── tambah_desa.php
│── edit_desa.php
│── hapus_desa.php
│
│── tambah_anggaran.php
│── edit_anggaran.php
│── hapus_anggaran.php
│
│── tambah_pembangunan.php
│── edit_pembangunan.php
│── hapus_pembangunan.php
│
│── tambah_evaluasi.php
│── edit_evaluasi.php
│── hapus_evaluasi.php
