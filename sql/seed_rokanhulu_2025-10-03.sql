-- Seed & fixes for Rokan Hulu (16 kecamatan, 139 desa)
-- Works on both Aiven MySQL and local MariaDB

SET NAMES utf8mb4;
-- Ensure the target database is selected (adjust if your DB name differs)
CREATE DATABASE IF NOT EXISTS `transparansi_desa`;
USE `transparansi_desa`;
START TRANSACTION;

-- Fix typos / names
UPDATE desa SET nama_desa='Rambah' WHERE nama_desa='Ramban' AND kecamatan='Rambah Hilir';
UPDATE desa SET nama_desa='Mahato' WHERE nama_desa='Manato' AND kecamatan='Tambusai Utara';
UPDATE desa SET nama_desa='Mahato Sakti' WHERE nama_desa='Manato Sakti' AND kecamatan='Tambusai Utara';
UPDATE desa SET kecamatan='Bangun Purba' WHERE nama_desa='Bangun Purba' AND kecamatan='Bangun Purbaa';

-- Ensure desa 139 exists
INSERT INTO desa (id_desa, nama_desa, kecamatan, kepala_desa)
VALUES (139, 'Sei Rokan Jaya', 'Kepenuhan', 'Belum Diisi')
ON DUPLICATE KEY UPDATE nama_desa=VALUES(nama_desa), kecamatan=VALUES(kecamatan);

-- Ensure missing 'Bangun Purba' village under kecamatan Bangun Purba
INSERT INTO desa (nama_desa, kecamatan, kepala_desa)
SELECT 'Bangun Purba', 'Bangun Purba', 'Belum Diisi'
WHERE NOT EXISTS (
  SELECT 1 FROM desa WHERE nama_desa='Bangun Purba' AND kecamatan='Bangun Purba'
);

-- Optional: bump AUTO_INCREMENT if needed (MariaDB dump may set it to 139)
-- ALTER TABLE desa AUTO_INCREMENT=140;

-- Dummy Anggaran: 3 jenis per desa (tahun 2024), skip if already present
INSERT INTO anggaran (id_desa, jenis_anggaran, jumlah, tahun)
SELECT d.id_desa, t.jenis, (100000000 + d.id_desa*10000 + t.idx*5000), 2024
FROM desa d
CROSS JOIN (
  SELECT 0 AS idx, 'Dana Desa' AS jenis
  UNION ALL SELECT 1, 'Alokasi Dana Desa'
  UNION ALL SELECT 2, 'Pendapatan Asli Desa'
) t
LEFT JOIN anggaran a ON a.id_desa=d.id_desa AND a.jenis_anggaran=t.jenis AND a.tahun=2024
WHERE a.id_anggaran IS NULL;

-- Dummy Pembangunan: 1 proyek per desa (tahun 2024), skip if already present for that desa+year
INSERT INTO pembangunan (id_desa, lokasi, realisasi, kegiatan, tahun)
SELECT d.id_desa,
       CONCAT('Lokasi ', d.nama_desa),
       (50000000 + d.id_desa*1000),
       CONCAT('Pembangunan fasilitas di ', d.nama_desa),
       2024
FROM desa d
LEFT JOIN pembangunan p ON p.id_desa=d.id_desa AND p.tahun=2024
WHERE p.id_pembangunan IS NULL;

-- Dummy Evaluasi: 1 approved evaluasi per desa, skip if any approved already exists for that desa
INSERT INTO evaluasi (id_desa, nama, kontak, kategori, laporan, status, tanggal)
SELECT d.id_desa,
       CONCAT('Warga ', d.nama_desa),
       CONCAT('08', LPAD(d.id_desa, 10, '0')),
       'Pemantauan Proyek',
       CONCAT('Laporan dummy untuk desa ', d.nama_desa),
       'approved',
       NOW()
FROM desa d
LEFT JOIN (
  SELECT id_desa, MIN(id_evaluasi) AS any_row
  FROM evaluasi
  WHERE status='approved'
  GROUP BY id_desa
) e ON e.id_desa=d.id_desa
WHERE e.any_row IS NULL;

COMMIT;
