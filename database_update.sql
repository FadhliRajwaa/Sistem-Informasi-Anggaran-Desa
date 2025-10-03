-- ==========================
-- UPDATE SKEMA DATABASE TRANSPARANSI DESA
-- ==========================

USE transparansi_desa;

-- ==========================
-- Update Tabel Evaluasi - Tambah kolom baru
-- ==========================
ALTER TABLE evaluasi 
ADD COLUMN nama VARCHAR(100) DEFAULT '',
ADD COLUMN kontak VARCHAR(100) DEFAULT '',
ADD COLUMN kategori ENUM('Pelaporan Masalah', 'Pemantauan Proyek', 'Saran Perbaikan', 'Aspirasi Masyarakat') DEFAULT 'Pelaporan Masalah',
ADD COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
MODIFY COLUMN tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- ==========================
-- Tabel Jenis Anggaran (Referensi)
-- ==========================
DROP TABLE IF EXISTS anggaran_jenis;
CREATE TABLE anggaran_jenis (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(100) NOT NULL UNIQUE,
    urutan INT DEFAULT 0,
    keterangan TEXT,
    aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert jenis anggaran standar sesuai kebutuhan client
INSERT INTO anggaran_jenis (nama_jenis, urutan, keterangan) VALUES
('Dana Desa', 1, 'Dana yang bersumber dari APBN yang diperuntukkan bagi desa'),
('Alokasi Dana Desa', 2, 'Dana perimbangan yang diterima kabupaten/kota dalam APBD'),
('Bantuan Keuangan Provinsi', 3, 'Bantuan keuangan dari pemerintah provinsi'),
('Swadaya Masyarakat', 4, 'Kontribusi masyarakat berupa uang, barang, atau tenaga'),
('Bagi Hasil Pajak', 5, 'Bagi hasil pajak daerah dan retribusi daerah'),
('Pendapatan Asli Desa', 6, 'Pendapatan yang berasal dari kewenangan desa'),
('Lain-lain', 7, 'Sumber pendapatan lain yang sah');

-- ==========================
-- Update Password Admin dengan Hash yang Aman
-- ==========================
-- Ganti password admin menjadi menggunakan password_hash PHP
-- Password default: admin123
UPDATE admin SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin';

-- Jika belum ada admin, buat baru
INSERT IGNORE INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ==========================
-- Update beberapa data kepala desa yang kosong (contoh)
-- ==========================
UPDATE desa SET kepala_desa = 'Belum Diisi' WHERE kepala_desa IS NULL OR kepala_desa = '';

-- ==========================
-- Indeks untuk performa
-- ==========================
CREATE INDEX idx_desa_kecamatan ON desa(kecamatan);
CREATE INDEX idx_anggaran_tahun ON anggaran(tahun);
CREATE INDEX idx_pembangunan_tahun ON pembangunan(tahun);
CREATE INDEX idx_evaluasi_status ON evaluasi(status);
CREATE INDEX idx_evaluasi_tanggal ON evaluasi(tanggal);

-- ==========================
-- View untuk laporan gabungan (opsional)
-- ==========================
CREATE OR REPLACE VIEW v_anggaran_lengkap AS
SELECT 
    a.id_anggaran,
    d.kecamatan,
    d.nama_desa,
    d.kepala_desa,
    a.jenis_anggaran,
    a.jumlah,
    a.tahun
FROM anggaran a
JOIN desa d ON a.id_desa = d.id_desa
ORDER BY d.kecamatan, d.nama_desa, a.tahun DESC;

CREATE OR REPLACE VIEW v_pembangunan_lengkap AS
SELECT 
    p.id_pembangunan,
    d.kecamatan,
    d.nama_desa,
    p.kegiatan,
    p.lokasi,
    p.realisasi,
    p.tahun
FROM pembangunan p
JOIN desa d ON p.id_desa = d.id_desa
ORDER BY d.kecamatan, d.nama_desa, p.tahun DESC;

CREATE OR REPLACE VIEW v_evaluasi_lengkap AS
SELECT 
    e.id_evaluasi,
    d.kecamatan,
    d.nama_desa,
    e.nama,
    e.kontak,
    e.kategori,
    e.laporan,
    e.status,
    e.tanggal
FROM evaluasi e
JOIN desa d ON e.id_desa = d.id_desa
ORDER BY e.tanggal DESC;

-- ==========================
-- Selesai Update Database
-- ==========================
