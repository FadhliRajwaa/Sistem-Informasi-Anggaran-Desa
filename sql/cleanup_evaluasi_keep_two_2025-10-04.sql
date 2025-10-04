-- Cleanup Evaluasi: keep only 'Sei Rokan Jaya' and 'Sei Kandis'
-- Works on Aiven MySQL and local MariaDB

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Ensure the target database is selected (adjust if different)
USE `transparansi_desa`;

START TRANSACTION;

-- Safety backup (idempotent)
DROP TABLE IF EXISTS `evaluasi_backup_20251004`;
CREATE TABLE `evaluasi_backup_20251004` LIKE `evaluasi`;
INSERT INTO `evaluasi_backup_20251004` SELECT * FROM `evaluasi`;

-- Delete all evaluasi except for the two villages below
DELETE e
FROM `evaluasi` e
JOIN `desa` d ON e.id_desa = d.id_desa
WHERE d.`nama_desa` NOT IN ('Sei Rokan Jaya', 'Sei Kandis');

COMMIT;

-- Optional verification
-- SELECT d.nama_desa, COUNT(*) AS total
-- FROM evaluasi e JOIN desa d ON e.id_desa=d.id_desa
-- GROUP BY d.nama_desa
-- ORDER BY d.nama_desa;
