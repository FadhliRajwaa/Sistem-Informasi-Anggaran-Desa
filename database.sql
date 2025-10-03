-- Buat database
CREATE DATABASE IF NOT EXISTS transparansi_desa;
USE transparansi_desa;

-- ==========================
-- Tabel Desa
-- ==========================
DROP TABLE IF EXISTS desa;
CREATE TABLE desa (
    id_desa INT AUTO_INCREMENT PRIMARY KEY,
    nama_desa VARCHAR(100) NOT NULL,
    kecamatan VARCHAR(100) NOT NULL,
    kepala_desa VARCHAR(100)
);

-- Data Kecamatan & Desa
-- Kecamatan UJUNG BATU
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Suka Damai', 'Ujung Batu'),
('Ngaso', 'Ujung Batu'),
('Ujung Batu Timur', 'Ujung Batu'),
('Pematang Tebih', 'Ujung Batu');

-- Kecamatan ROKAN IV KOTO
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Cipang Kanan', 'Rokan IV Koto'),
('Cipang Kiri Hulu', 'Rokan IV Koto'),
('Cipang Kiri Hilir', 'Rokan IV Koto'),
('Tanjung Medan', 'Rokan IV Koto'),
('Lubuk Bendahara Timur', 'Rokan IV Koto'),
('Lubuk Bendahara', 'Rokan IV Koto'),
('Sikebau Jaya', 'Rokan IV Koto'),
('Rokan Koto Ruang', 'Rokan IV Koto'),
('Rokan Timur', 'Rokan IV Koto'),
('Lubuk Betung', 'Rokan IV Koto'),
('Pemandang', 'Rokan IV Koto'),
('Alahan', 'Rokan IV Koto'),
('Tibawan', 'Rokan IV Koto');

-- Kecamatan RAMBAH
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Rambah Tengah Utara', 'Rambah'),
('Rambah Tengah Hilir', 'Rambah'),
('Rambah Tengah Hulu', 'Rambah'),
('Rambah Tengah Barat', 'Rambah'),
('Menaming', 'Rambah'),
('Pasir Baru', 'Rambah'),
('Sialang Jaya', 'Rambah'),
('Tanjung Belit', 'Rambah'),
('Koto Tinggi', 'Rambah'),
('Suka Maju', 'Rambah'),
('Pematang Berangan', 'Rambah'),
('Babussalam', 'Rambah'),
('Pasir Maju', 'Rambah');

-- Kecamatan TAMBUSAI
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Tambusai Barat', 'Tambusai'),
('Tambusai Timur', 'Tambusai'),
('Batas', 'Tambusai'),
('Talikumain', 'Tambusai'),
('Rantau Panjang', 'Tambusai'),
('Sungai Kumango', 'Tambusai'),
('Batang Kumu', 'Tambusai'),
('Sialang Rindang', 'Tambusai'),
('Suka Maju', 'Tambusai'),
('Lubuk Soting', 'Tambusai'),
('Tingkok', 'Tambusai');

-- Kecamatan KEPENUHAN
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Kepenuhan Barat', 'Kepenuhan'),
('Kepenuhan Hilir', 'Kepenuhan'),
('Kepenuhan Timur', 'Kepenuhan'),
('Kepenuhan Raya', 'Kepenuhan'),
('Kepenuhan Baru', 'Kepenuhan'),
('Kepenuhan Barat Mulya', 'Kepenuhan'),
('Ulak Patian', 'Kepenuhan'),
('Rantau Binuang Sakti', 'Kepenuhan');

-- Kecamatan KUNTO DARUSSALAM
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Kota Intan', 'Kunto Darussalam'),
('Muara Dilam', 'Kunto Darussalam'),
('Kota Raya', 'Kunto Darussalam'),
('Kota Baru', 'Kunto Darussalam'),
('Sungai Kuti', 'Kunto Darussalam'),
('Pasir Indah', 'Kunto Darussalam'),
('Pasir Luhur', 'Kunto Darussalam'),
('Bukit Intan Makmur', 'Kunto Darussalam'),
('Bagan Tujuh', 'Kunto Darussalam');

-- Kecamatan RAMBAH SAMO
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Rambah Samo', 'Rambah Samo'),
('Rambah Samo Barat', 'Rambah Samo'),
('Rambah Baru', 'Rambah Samo'),
('Rambah Utama', 'Rambah Samo'),
('Pasir Makmur', 'Rambah Samo'),
('Karya Mulya', 'Rambah Samo'),
('Marga Mulya', 'Rambah Samo'),
('Langkitin', 'Rambah Samo'),
('Masda Makmur', 'Rambah Samo'),
('Lubuk Napal', 'Rambah Samo'),
('Teluk Aur', 'Rambah Samo'),
('Sei Salak', 'Rambah Samo'),
('Sei Kuning', 'Rambah Samo'),
('Lubuk Bilang', 'Rambah Samo');

-- Kecamatan RAMBAH HILIR
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Rambah Hilir', 'Rambah Hilir'),
('Rambah Hilir Tengah', 'Rambah Hilir'),
('Rambah Hilir Timur', 'Rambah Hilir'),
('Pasir Utama', 'Rambah Hilir'),
('Pasir Jaya', 'Rambah Hilir'),
('Rambah Muda', 'Rambah Hilir'),
('Sungai Sitolang', 'Rambah Hilir'),
('Lubuk Kerapat', 'Rambah Hilir'),
('Ramban', 'Rambah Hilir'),
('Serombou Indah', 'Rambah Hilir'),
('Sungai Dua Indah', 'Rambah Hilir'),
('Muara Musu', 'Rambah Hilir'),
('Sejati', 'Rambah Hilir');

-- Kecamatan TAMBUSAI UTARA
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Tambusai Utara', 'Tambusai Utara'),
('Manato', 'Tambusai Utara'),
('Bangun Jaya', 'Tambusai Utara'),
('Simpang Harapan', 'Tambusai Utara'),
('Pagar Mayang', 'Tambusai Utara'),
('Payung Sekaki', 'Tambusai Utara'),
('Mekar Jaya', 'Tambusai Utara'),
('Tanjung Medan', 'Tambusai Utara'),
('Suka Damai', 'Tambusai Utara'),
('Rantau Sakti', 'Tambusai Utara'),
('Manato Sakti', 'Tambusai Utara');

-- Kecamatan BANGUN PURBA
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Pasir Agung', 'Bangun Purba'),
('Pasir Intan', 'Bangun Purba'),
('Rambah Jaya', 'Bangun Purba'),
('Bangun Purba', 'Bangun Purba'),
('Bangun Purba Timur Jaya', 'Bangun Purba'),
('Bangun Purba Barat', 'Bangun Purba'),
('Tangun', 'Bangun Purba');

-- Kecamatan TANDUN
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Tandun', 'Tandun'),
('Kumain', 'Tandun'),
('Bono Tapung', 'Tandun'),
('Dayo', 'Tandun'),
('Tapung Jaya', 'Tandun'),
('Puo Raya', 'Tandun'),
('Sei Kuning', 'Tandun'),
('Koto Tandun', 'Tandun'),
('Tandun Barat', 'Tandun');

-- Kecamatan KABUN
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Kabun', 'Kabun'),
('Aliantan', 'Kabun'),
('Kota Ranah', 'Kabun'),
('Boncah Kesuma', 'Kabun'),
('Batu Langkah Besar', 'Kabun'),
('Giti', 'Kabun');

-- Kecamatan BONAI DARUSSALAM
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Teluk Sono', 'Bonai Darussalam'),
('Sontang', 'Bonai Darussalam'),
('Bonai', 'Bonai Darussalam'),
('Rawa Makmur', 'Bonai Darussalam'),
('Pauh', 'Bonai Darussalam'),
('Kasang Padang', 'Bonai Darussalam'),
('Kasang Mungkal', 'Bonai Darussalam');

-- Kecamatan PAGARAN TAPAH DARUSSALAM
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Pagaran Tapah', 'Pagaran Tapah Darussalam'),
('Kembang Damai', 'Pagaran Tapah Darussalam'),
('Sangkir Indah', 'Pagaran Tapah Darussalam');

-- Kecamatan KEPENUHAN HULU
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Kepenuhan Hulu', 'Kepenuhan Hulu'),
('Pekan Tebih', 'Kepenuhan Hulu'),
('Kepayang', 'Kepenuhan Hulu'),
('Muara Jaya', 'Kepenuhan Hulu'),
('Kepenuhan Jaya', 'Kepenuhan Hulu');

-- Kecamatan PENDALIAN IV KOTO
INSERT INTO desa (nama_desa, kecamatan) VALUES
('Pendalian', 'Pendalian IV Koto'),
('Bengkolan Salak', 'Pendalian IV Koto'),
('Suligi', 'Pendalian IV Koto'),
('Air Panas', 'Pendalian IV Koto'),
('Sei Kandis', 'Pendalian IV Koto');

-- ==========================
-- Tabel Anggaran
-- ==========================
DROP TABLE IF EXISTS anggaran;
CREATE TABLE anggaran (
    id_anggaran INT AUTO_INCREMENT PRIMARY KEY,
    id_desa INT NOT NULL,
    jenis_anggaran VARCHAR(100) NOT NULL,
    jumlah DECIMAL(15,2),
    tahun YEAR NOT NULL,
    FOREIGN KEY (id_desa) REFERENCES desa(id_desa) ON DELETE CASCADE
);

-- ==========================
-- Tabel Pembangunan
-- ==========================
DROP TABLE IF EXISTS pembangunan;
CREATE TABLE pembangunan (
    id_pembangunan INT AUTO_INCREMENT PRIMARY KEY,
    id_desa INT NOT NULL,
    lokasi VARCHAR(100),
    realisasi DECIMAL(15,2),
    kegiatan TEXT,
    tahun YEAR NOT NULL,
    FOREIGN KEY (id_desa) REFERENCES desa(id_desa) ON DELETE CASCADE
);

-- ==========================
-- Tabel Evaluasi
-- ==========================
DROP TABLE IF EXISTS evaluasi;
CREATE TABLE evaluasi (
    id_evaluasi INT AUTO_INCREMENT PRIMARY KEY,
    id_desa INT NOT NULL,
    laporan TEXT,
    tanggal DATE,
    FOREIGN KEY (id_desa) REFERENCES desa(id_desa) ON DELETE CASCADE
);

-- ==========================
-- Tabel Admin
-- ==========================
DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert admin default (username: admin, password: admin123)
INSERT INTO admin (username, password) VALUES
('admin', MD5('admin123'));
