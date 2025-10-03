-- Aiven-ready SQL dump for transparansi_desa
-- Notes:
-- - Primary keys are defined inline at CREATE TABLE (required by Aiven's sql_require_primary_key=ON)
-- - No DEFINER clauses in views; use SQL SECURITY INVOKER
-- - Default InnoDB + utf8mb4

SET time_zone = '+00:00';
SET NAMES utf8mb4;

START TRANSACTION;

-- ========== TABLES ==========

-- Admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- Desa (created before FKs)
CREATE TABLE IF NOT EXISTS `desa` (
  `id_desa` int NOT NULL AUTO_INCREMENT,
  `nama_desa` varchar(100) NOT NULL,
  `kecamatan` varchar(100) NOT NULL,
  `kepala_desa` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_desa`),
  KEY `idx_desa_kecamatan` (`kecamatan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Anggaran Jenis (reference list)
CREATE TABLE IF NOT EXISTS `anggaran_jenis` (
  `id_jenis` int NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(100) NOT NULL,
  `urutan` int DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_jenis`),
  UNIQUE KEY `nama_jenis` (`nama_jenis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Anggaran
CREATE TABLE IF NOT EXISTS `anggaran` (
  `id_anggaran` int NOT NULL AUTO_INCREMENT,
  `id_desa` int NOT NULL,
  `jenis_anggaran` varchar(100) NOT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `tahun` year NOT NULL,
  PRIMARY KEY (`id_anggaran`),
  KEY `id_desa` (`id_desa`),
  KEY `idx_anggaran_tahun` (`tahun`),
  CONSTRAINT `fk_anggaran_desa` FOREIGN KEY (`id_desa`) REFERENCES `desa` (`id_desa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Evaluasi
CREATE TABLE IF NOT EXISTS `evaluasi` (
  `id_evaluasi` int NOT NULL AUTO_INCREMENT,
  `id_desa` int NOT NULL,
  `laporan` text DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama` varchar(100) DEFAULT '',
  `kontak` varchar(100) DEFAULT '',
  `kategori` enum('Pelaporan Masalah','Pemantauan Proyek','Saran Perbaikan','Aspirasi Masyarakat') DEFAULT 'Pelaporan Masalah',
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_evaluasi`),
  KEY `id_desa` (`id_desa`),
  KEY `idx_evaluasi_status` (`status`),
  KEY `idx_evaluasi_tanggal` (`tanggal`),
  CONSTRAINT `fk_evaluasi_desa` FOREIGN KEY (`id_desa`) REFERENCES `desa` (`id_desa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Pembangunan
CREATE TABLE IF NOT EXISTS `pembangunan` (
  `id_pembangunan` int NOT NULL AUTO_INCREMENT,
  `id_desa` int NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `realisasi` decimal(15,2) DEFAULT NULL,
  `kegiatan` text DEFAULT NULL,
  `tahun` year NOT NULL,
  PRIMARY KEY (`id_pembangunan`),
  KEY `id_desa` (`id_desa`),
  KEY `idx_pembangunan_tahun` (`tahun`),
  CONSTRAINT `fk_pembangunan_desa` FOREIGN KEY (`id_desa`) REFERENCES `desa` (`id_desa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ========== STATIC DATA ==========

-- anggaran_jenis
INSERT INTO `anggaran_jenis` (`id_jenis`, `nama_jenis`, `urutan`, `keterangan`, `aktif`, `created_at`) VALUES
(1, 'Dana Desa', 1, 'Dana yang bersumber dari APBN yang diperuntukkan bagi desa', 1, '2025-10-02 15:22:05'),
(2, 'Alokasi Dana Desa', 2, 'Dana perimbangan yang diterima kabupaten/kota dalam APBD', 1, '2025-10-02 15:22:05'),
(3, 'Bantuan Keuangan Provinsi', 3, 'Bantuan keuangan dari pemerintah provinsi', 1, '2025-10-02 15:22:05'),
(4, 'Swadaya Masyarakat', 4, 'Kontribusi masyarakat berupa uang, barang, atau tenaga', 1, '2025-10-02 15:22:05'),
(5, 'Bagi Hasil Pajak', 5, 'Bagi hasil pajak daerah dan retribusi daerah', 1, '2025-10-02 15:22:05'),
(6, 'Pendapatan Asli Desa', 6, 'Pendapatan yang berasal dari kewenangan desa', 1, '2025-10-02 15:22:05'),
(7, 'Lain-lain', 7, 'Sumber pendapatan lain yang sah', 1, '2025-10-02 15:22:05')
ON DUPLICATE KEY UPDATE `nama_jenis`=VALUES(`nama_jenis`);

-- desa
INSERT INTO `desa` (`id_desa`, `nama_desa`, `kecamatan`, `kepala_desa`) VALUES
(1, 'Suka Damai', 'Ujung Batu', 'Belum Diisi'),
(2, 'Ngaso', 'Ujung Batu', 'Belum Diisi'),
(3, 'Ujung Batu Timur', 'Ujung Batu', 'Belum Diisi'),
(4, 'Pematang Tebih', 'Ujung Batu', 'Belum Diisi'),
(5, 'Cipang Kanan', 'Rokan IV Koto', 'Belum Diisi'),
(6, 'Cipang Kiri Hulu', 'Rokan IV Koto', 'Belum Diisi'),
(7, 'Cipang Kiri Hilir', 'Rokan IV Koto', 'Belum Diisi'),
(8, 'Tanjung Medan', 'Rokan IV Koto', 'Belum Diisi'),
(9, 'Lubuk Bendahara Timur', 'Rokan IV Koto', 'Belum Diisi'),
(10, 'Lubuk Bendahara', 'Rokan IV Koto', 'Belum Diisi'),
(11, 'Sikebau Jaya', 'Rokan IV Koto', 'Belum Diisi'),
(12, 'Rokan Koto Ruang', 'Rokan IV Koto', 'Belum Diisi'),
(13, 'Rokan Timur', 'Rokan IV Koto', 'Belum Diisi'),
(14, 'Lubuk Betung', 'Rokan IV Koto', 'Belum Diisi'),
(15, 'Pemandang', 'Rokan IV Koto', 'Belum Diisi'),
(16, 'Alahan', 'Rokan IV Koto', 'Belum Diisi'),
(17, 'Tibawan', 'Rokan IV Koto', 'Belum Diisi'),
(18, 'Rambah Tengah Utara', 'Rambah', 'Belum Diisi'),
(19, 'Rambah Tengah Hilir', 'Rambah', 'Belum Diisi'),
(20, 'Rambah Tengah Hulu', 'Rambah', 'Belum Diisi'),
(21, 'Rambah Tengah Barat', 'Rambah', 'Belum Diisi'),
(22, 'Menaming', 'Rambah', 'Belum Diisi'),
(23, 'Pasir Baru', 'Rambah', 'Belum Diisi'),
(24, 'Sialang Jaya', 'Rambah', 'Belum Diisi'),
(25, 'Tanjung Belit', 'Rambah', 'Belum Diisi'),
(26, 'Koto Tinggi', 'Rambah', 'Belum Diisi'),
(27, 'Suka Maju', 'Rambah', 'Belum Diisi'),
(28, 'Pematang Berangan', 'Rambah', 'Belum Diisi'),
(29, 'Babussalam', 'Rambah', 'Belum Diisi'),
(30, 'Pasir Maju', 'Rambah', 'Belum Diisi'),
(31, 'Tambusai Barat', 'Tambusai', 'Belum Diisi'),
(32, 'Tambusai Timur', 'Tambusai', 'Belum Diisi'),
(33, 'Batas', 'Tambusai', 'Belum Diisi'),
(34, 'Talikumain', 'Tambusai', 'Belum Diisi'),
(35, 'Rantau Panjang', 'Tambusai', 'Belum Diisi'),
(36, 'Sungai Kumango', 'Tambusai', 'Belum Diisi'),
(37, 'Batang Kumu', 'Tambusai', 'Belum Diisi'),
(38, 'Sialang Rindang', 'Tambusai', 'Belum Diisi'),
(39, 'Suka Maju', 'Tambusai', 'Belum Diisi'),
(40, 'Lubuk Soting', 'Tambusai', 'Belum Diisi'),
(41, 'Tingkok', 'Tambusai', 'Belum Diisi'),
(42, 'Kepenuhan Barat', 'Kepenuhan', 'Belum Diisi'),
(43, 'Kepenuhan Hilir', 'Kepenuhan', 'Belum Diisi'),
(44, 'Kepenuhan Timur', 'Kepenuhan', 'Belum Diisi'),
(45, 'Kepenuhan Raya', 'Kepenuhan', 'Belum Diisi'),
(46, 'Kepenuhan Baru', 'Kepenuhan', 'Belum Diisi'),
(47, 'Kepenuhan Barat Mulya', 'Kepenuhan', 'Belum Diisi'),
(48, 'Ulak Patian', 'Kepenuhan', 'Belum Diisi'),
(49, 'Rantau Binuang Sakti', 'Kepenuhan', 'Belum Diisi'),
(50, 'Kota Intan', 'Kunto Darussalam', 'Belum Diisi'),
(51, 'Muara Dilam', 'Kunto Darussalam', 'Belum Diisi'),
(52, 'Kota Raya', 'Kunto Darussalam', 'Belum Diisi'),
(53, 'Kota Baru', 'Kunto Darussalam', 'Belum Diisi'),
(54, 'Sungai Kuti', 'Kunto Darussalam', 'Belum Diisi'),
(55, 'Pasir Indah', 'Kunto Darussalam', 'Belum Diisi'),
(56, 'Pasir Luhur', 'Kunto Darussalam', 'Belum Diisi'),
(57, 'Bukit Intan Makmur', 'Kunto Darussalam', 'Belum Diisi'),
(58, 'Bagan Tujuh', 'Kunto Darussalam', 'Belum Diisi'),
(59, 'Rambah Samo', 'Rambah Samo', 'Belum Diisi'),
(60, 'Rambah Samo Barat', 'Rambah Samo', 'Belum Diisi'),
(61, 'Rambah Baru', 'Rambah Samo', 'Belum Diisi'),
(62, 'Rambah Utama', 'Rambah Samo', 'Belum Diisi'),
(63, 'Pasir Makmur', 'Rambah Samo', 'Belum Diisi'),
(64, 'Karya Mulya', 'Rambah Samo', 'Belum Diisi'),
(65, 'Marga Mulya', 'Rambah Samo', 'Belum Diisi'),
(66, 'Langkitin', 'Rambah Samo', 'Belum Diisi'),
(67, 'Masda Makmur', 'Rambah Samo', 'Belum Diisi'),
(68, 'Lubuk Napal', 'Rambah Samo', 'Belum Diisi'),
(69, 'Teluk Aur', 'Rambah Samo', 'Belum Diisi'),
(70, 'Sei Salak', 'Rambah Samo', 'Belum Diisi'),
(71, 'Sei Kuning', 'Rambah Samo', 'Belum Diisi'),
(72, 'Lubuk Bilang', 'Rambah Samo', 'Belum Diisi'),
(73, 'Rambah Hilir', 'Rambah Hilir', 'Belum Diisi'),
(74, 'Rambah Hilir Tengah', 'Rambah Hilir', 'Belum Diisi'),
(75, 'Rambah Hilir Timur', 'Rambah Hilir', 'Belum Diisi'),
(76, 'Pasir Utama', 'Rambah Hilir', 'Belum Diisi'),
(77, 'Pasir Jaya', 'Rambah Hilir', 'Belum Diisi'),
(78, 'Rambah Muda', 'Rambah Hilir', 'Belum Diisi'),
(79, 'Sungai Sitolang', 'Rambah Hilir', 'Belum Diisi'),
(80, 'Lubuk Kerapat', 'Rambah Hilir', 'Belum Diisi'),
(81, 'Ramban', 'Rambah Hilir', 'Belum Diisi'),
(82, 'Serombou Indah', 'Rambah Hilir', 'Belum Diisi'),
(83, 'Sungai Dua Indah', 'Rambah Hilir', 'Belum Diisi'),
(84, 'Muara Musu', 'Rambah Hilir', 'Belum Diisi'),
(85, 'Sejati', 'Rambah Hilir', 'Belum Diisi'),
(86, 'Tambusai Utara', 'Tambusai Utara', 'Belum Diisi'),
(87, 'Manato', 'Tambusai Utara', 'Belum Diisi'),
(88, 'Bangun Jaya', 'Tambusai Utara', 'Belum Diisi'),
(89, 'Simpang Harapan', 'Tambusai Utara', 'Belum Diisi'),
(90, 'Pagar Mayang', 'Tambusai Utara', 'Belum Diisi'),
(91, 'Payung Sekaki', 'Tambusai Utara', 'Belum Diisi'),
(92, 'Mekar Jaya', 'Tambusai Utara', 'Belum Diisi'),
(93, 'Tanjung Medan', 'Tambusai Utara', 'Belum Diisi'),
(94, 'Suka Damai', 'Tambusai Utara', 'Belum Diisi'),
(95, 'Rantau Sakti', 'Tambusai Utara', 'Belum Diisi'),
(96, 'Manato Sakti', 'Tambusai Utara', 'Belum Diisi'),
(97, 'Pasir Agung', 'Bangun Purba', 'Belum Diisi'),
(98, 'Pasir Intan', 'Bangun Purba', 'Belum Diisi'),
(99, 'Rambah Jaya', 'Bangun Purba', 'Belum Diisi'),
(100, 'Bangun Purba', 'Bangun Purbaa', 'Belum Diisi'),
(101, 'Bangun Purba Timur Jaya', 'Bangun Purba', 'Belum Diisi'),
(102, 'Bangun Purba Barat', 'Bangun Purba', 'Belum Diisi'),
(103, 'Tangun', 'Bangun Purba', 'Belum Diisi'),
(104, 'Tandun', 'Tandun', 'Belum Diisi'),
(105, 'Kumain', 'Tandun', 'Belum Diisi'),
(106, 'Bono Tapung', 'Tandun', 'Belum Diisi'),
(107, 'Dayo', 'Tandun', 'Belum Diisi'),
(108, 'Tapung Jaya', 'Tandun', 'Belum Diisi'),
(109, 'Puo Raya', 'Tandun', 'Belum Diisi'),
(110, 'Sei Kuning', 'Tandun', 'Belum Diisi'),
(111, 'Koto Tandun', 'Tandun', 'Belum Diisi'),
(112, 'Tandun Barat', 'Tandun', 'Belum Diisi'),
(113, 'Kabun', 'Kabun', 'Belum Diisi'),
(114, 'Aliantan', 'Kabun', 'Belum Diisi'),
(115, 'Kota Ranah', 'Kabun', 'Belum Diisi'),
(116, 'Boncah Kesuma', 'Kabun', 'Belum Diisi'),
(117, 'Batu Langkah Besar', 'Kabun', 'Belum Diisi'),
(118, 'Giti', 'Kabun', 'Belum Diisi'),
(119, 'Teluk Sono', 'Bonai Darussalam', 'Belum Diisi'),
(120, 'Sontang', 'Bonai Darussalam', 'Belum Diisi'),
(121, 'Bonai', 'Bonai Darussalam', 'Belum Diisi'),
(122, 'Rawa Makmur', 'Bonai Darussalam', 'Belum Diisi'),
(123, 'Pauh', 'Bonai Darussalam', 'Belum Diisi'),
(124, 'Kasang Padang', 'Bonai Darussalam', 'Belum Diisi'),
(125, 'Kasang Mungkal', 'Bonai Darussalam', 'Belum Diisi'),
(126, 'Pagaran Tapah', 'Pagaran Tapah Darussalam', 'Belum Diisi'),
(127, 'Kembang Damai', 'Pagaran Tapah Darussalam', 'Belum Diisi'),
(128, 'Sangkir Indah', 'Pagaran Tapah Darussalam', 'Belum Diisi'),
(129, 'Kepenuhan Hulu', 'Kepenuhan Hulu', 'Belum Diisi'),
(130, 'Pekan Tebih', 'Kepenuhan Hulu', 'Belum Diisi'),
(131, 'Kepayang', 'Kepenuhan Hulu', 'Belum Diisi'),
(132, 'Muara Jaya', 'Kepenuhan Hulu', 'Belum Diisi'),
(133, 'Kepenuhan Jaya', 'Kepenuhan Hulu', 'Belum Diisi'),
(134, 'Pendalian', 'Pendalian IV Koto', 'Belum Diisi'),
(135, 'Bengkolan Salak', 'Pendalian IV Koto', 'Belum Diisi'),
(136, 'Suligi', 'Pendalian IV Koto', 'Belum Diisi'),
(137, 'Air Panas', 'Pendalian IV Koto', 'Belum Diisi'),
(138, 'Sei Kandis', 'Pendalian IV Koto', 'Belum Diisi')
ON DUPLICATE KEY UPDATE `nama_desa`=VALUES(`nama_desa`), `kecamatan`=VALUES(`kecamatan`);

-- ========== VIEWS ==========

DROP VIEW IF EXISTS `v_anggaran_lengkap`;
CREATE SQL SECURITY INVOKER VIEW `v_anggaran_lengkap` AS
SELECT a.id_anggaran, d.kecamatan, d.nama_desa, d.kepala_desa,
       a.jenis_anggaran, a.jumlah, a.tahun
FROM anggaran a
JOIN desa d ON a.id_desa = d.id_desa
ORDER BY d.kecamatan ASC, d.nama_desa ASC, a.tahun DESC;

DROP VIEW IF EXISTS `v_evaluasi_lengkap`;
CREATE SQL SECURITY INVOKER VIEW `v_evaluasi_lengkap` AS
SELECT e.id_evaluasi, d.kecamatan, d.nama_desa, e.nama, e.kontak,
       e.kategori, e.laporan, e.status, e.tanggal
FROM evaluasi e
JOIN desa d ON e.id_desa = d.id_desa
ORDER BY e.tanggal DESC;

DROP VIEW IF EXISTS `v_pembangunan_lengkap`;
CREATE SQL SECURITY INVOKER VIEW `v_pembangunan_lengkap` AS
SELECT p.id_pembangunan, d.kecamatan, d.nama_desa, p.kegiatan, p.lokasi, p.realisasi, p.tahun
FROM pembangunan p
JOIN desa d ON p.id_desa = d.id_desa
ORDER BY d.kecamatan ASC, d.nama_desa ASC, p.tahun DESC;

COMMIT;
