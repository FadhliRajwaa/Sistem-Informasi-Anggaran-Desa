<?php
include "../includes/db.php";
include "../includes/auth.php";

$message = '';
$error = '';

// Handle upload file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $uploadedFile = $_FILES['csv_file'];
    
    if ($uploadedFile['error'] == UPLOAD_ERR_OK) {
        $tmpName = $uploadedFile['tmp_name'];
        $fileType = $_POST['file_type'];
        
        // Validasi ekstensi file
        $allowedExtensions = ['csv'];
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, $allowedExtensions)) {
            $csvData = array_map('str_getcsv', file($tmpName));
            $headers = array_shift($csvData); // Remove header row
            
            $imported = 0;
            $errors = [];
            
            foreach ($csvData as $rowIndex => $row) {
                try {
                    if ($fileType == 'desa') {
                        importDesa($conn, $row, $rowIndex);
                    } elseif ($fileType == 'anggaran') {
                        importAnggaran($conn, $row, $rowIndex);
                    } elseif ($fileType == 'pembangunan') {
                        importPembangunan($conn, $row, $rowIndex);
                    } elseif ($fileType == 'evaluasi') {
                        importEvaluasi($conn, $row, $rowIndex);
                    }
                    $imported++;
                } catch (Exception $e) {
                    $errors[] = "Baris " . ($rowIndex + 2) . ": " . $e->getMessage();
                }
            }
            
            $message = "Berhasil import $imported data.";
            if (!empty($errors)) {
                $error = "Terdapat " . count($errors) . " error:<br>" . implode("<br>", array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $error .= "<br>... dan " . (count($errors) - 10) . " error lainnya.";
                }
            }
        } else {
            $error = "Format file tidak didukung. Gunakan CSV.";
        }
    } else {
        $error = "Error upload file.";
    }
}

function importDesa($conn, $row, $index) {
    if (count($row) < 3) throw new Exception("Data tidak lengkap");
    
    $nama_desa = trim($row[0]);
    $kecamatan = trim($row[1]); 
    $kepala_desa = trim($row[2]);
    
    if (empty($nama_desa) || empty($kecamatan)) {
        throw new Exception("Nama desa dan kecamatan harus diisi");
    }
    
    $stmt = $conn->prepare("INSERT INTO desa (nama_desa, kecamatan, kepala_desa) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama_desa, $kecamatan, $kepala_desa);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal insert ke database: " . $stmt->error);
    }
    $stmt->close();
}

function importAnggaran($conn, $row, $index) {
    if (count($row) < 5) throw new Exception("Data tidak lengkap");
    
    $kecamatan = trim($row[0]);
    $nama_desa = trim($row[1]);
    $jenis_anggaran = trim($row[2]);
    $tahun = (int)$row[3];
    $jumlah = (float)str_replace([',', '.'], ['', ''], $row[4]);
    
    // Cari ID desa
    $stmt = $conn->prepare("SELECT id_desa FROM desa WHERE nama_desa = ? AND kecamatan = ?");
    $stmt->bind_param("ss", $nama_desa, $kecamatan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("Desa $nama_desa di $kecamatan tidak ditemukan");
    }
    
    $desa = $result->fetch_assoc();
    $id_desa = $desa['id_desa'];
    $stmt->close();
    
    // Insert anggaran
    $stmt = $conn->prepare("INSERT INTO anggaran (id_desa, jenis_anggaran, tahun, jumlah) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isid", $id_desa, $jenis_anggaran, $tahun, $jumlah);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal insert anggaran: " . $stmt->error);
    }
    $stmt->close();
}

function importPembangunan($conn, $row, $index) {
    if (count($row) < 6) throw new Exception("Data tidak lengkap");
    
    $kecamatan = trim($row[0]);
    $nama_desa = trim($row[1]);
    $tahun = (int)$row[2];
    $kegiatan = trim($row[3]);
    $lokasi = trim($row[4]);
    $realisasi = (float)str_replace([',', '.'], ['', ''], $row[5]);
    
    // Cari ID desa
    $stmt = $conn->prepare("SELECT id_desa FROM desa WHERE nama_desa = ? AND kecamatan = ?");
    $stmt->bind_param("ss", $nama_desa, $kecamatan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("Desa $nama_desa di $kecamatan tidak ditemukan");
    }
    
    $desa = $result->fetch_assoc();
    $id_desa = $desa['id_desa'];
    $stmt->close();
    
    // Insert pembangunan
    $stmt = $conn->prepare("INSERT INTO pembangunan (id_desa, tahun, kegiatan, lokasi, realisasi) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $id_desa, $tahun, $kegiatan, $lokasi, $realisasi);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal insert pembangunan: " . $stmt->error);
    }
    $stmt->close();
}

function importEvaluasi($conn, $row, $index) {
    if (count($row) < 5) throw new Exception("Data tidak lengkap");
    
    $kecamatan = trim($row[0]);
    $nama_desa = trim($row[1]);
    $nama = trim($row[2]);
    $kategori = trim($row[3]);
    $laporan = trim($row[4]);
    $kontak = isset($row[5]) ? trim($row[5]) : '';
    
    // Cari ID desa
    $stmt = $conn->prepare("SELECT id_desa FROM desa WHERE nama_desa = ? AND kecamatan = ?");
    $stmt->bind_param("ss", $nama_desa, $kecamatan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        throw new Exception("Desa $nama_desa di $kecamatan tidak ditemukan");
    }
    
    $desa = $result->fetch_assoc();
    $id_desa = $desa['id_desa'];
    $stmt->close();
    
    // Insert evaluasi
    $stmt = $conn->prepare("INSERT INTO evaluasi (id_desa, nama, kontak, kategori, laporan, status, tanggal) VALUES (?, ?, ?, ?, ?, 'approved', NOW())");
    $stmt->bind_param("issss", $id_desa, $nama, $kontak, $kategori, $laporan);
    
    if (!$stmt->execute()) {
        throw new Exception("Gagal insert evaluasi: " . $stmt->error);
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data - Admin Transparansi Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary:'#08D9D6', accent:'#FF2E63', dark:'#252A34' } } } }
    </script>
    <style>
        .glass-effect{backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}
        @keyframes fadeIn{0%{opacity:0;transform:translateY(8px)}100%{opacity:1;transform:translateY(0)}}
        .animate-fade-in{animation:fadeIn .4s ease-out}
    </style>
</head>
<body class="bg-gray-50">
<div class="min-h-screen py-6">
    <div class="max-w-5xl mx-auto animate-fade-in">
        <div class="flex items-center justify-between gap-3">
            <div class="bg-gradient-to-r from-primary to-primary-600 text-white rounded-2xl p-5 shadow-lg flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-upload"></i></div>
                <div>
                    <h2 class="text-xl font-bold leading-tight">Import Data CSV</h2>
                    <p class="text-white/80 text-sm">Unggah data sesuai template untuk diimpor</p>
                </div>
            </div>
            <a href="dashboard.php" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i><span>Kembali ke Dashboard</span>
            </a>
        </div>

        <?php if ($message): ?>
            <div class="mt-4 rounded-xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 flex items-start gap-2">
                <i class="fas fa-check mt-0.5"></i>
                <div><?= $message ?></div>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 flex items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <div><?= $error ?></div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-gray-100 p-5 glass-effect">
                <h5 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-file-upload"></i><span>Upload File CSV</span></h5>
                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Data</label>
                        <select name="file_type" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all">
                            <option value="">-- Pilih Jenis Data --</option>
                            <option value="desa">Data Desa</option>
                            <option value="anggaran">Data Anggaran</option>
                            <option value="pembangunan">Data Pembangunan</option>
                            <option value="evaluasi">Data Evaluasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File CSV</label>
                        <input type="file" name="csv_file" accept=".csv" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-primary/40 focus:border-primary transition-all bg-white" />
                        <p class="text-xs text-gray-500 mt-1">Format file: .csv (maksimal 10MB)</p>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl hover:opacity-90 transition-all"><i class="fas fa-upload"></i><span>Import Data</span></button>
                </form>
            </div>
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 glass-effect">
                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-download"></i><span>Template CSV</span></h6>
                    <p class="text-sm text-gray-600 mb-3">Download template untuk format yang benar:</p>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="templates/template_desa.csv" class="inline-flex items-center justify-between rounded-xl border px-3 py-2 text-sm hover:bg-blue-50 border-blue-200 text-blue-700" download>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-file-csv"></i>Template Desa</span>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="templates/template_anggaran.csv" class="inline-flex items-center justify-between rounded-xl border px-3 py-2 text-sm hover:bg-green-50 border-green-200 text-green-700" download>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-file-csv"></i>Template Anggaran</span>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="templates/template_pembangunan.csv" class="inline-flex items-center justify-between rounded-xl border px-3 py-2 text-sm hover:bg-amber-50 border-amber-200 text-amber-700" download>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-file-csv"></i>Template Pembangunan</span>
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="templates/template_evaluasi.csv" class="inline-flex items-center justify-between rounded-xl border px-3 py-2 text-sm hover:bg-cyan-50 border-cyan-200 text-cyan-700" download>
                            <span class="inline-flex items-center gap-2"><i class="fas fa-file-csv"></i>Template Evaluasi</span>
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 glass-effect">
                    <h6 class="font-semibold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-info-circle"></i><span>Petunjuk</span></h6>
                    <ol class="list-decimal pl-5 space-y-1 text-sm text-gray-700">
                        <li>Download template CSV sesuai jenis data</li>
                        <li>Isi data sesuai format yang ada</li>
                        <li>Simpan dalam format CSV (UTF-8)</li>
                        <li>Upload file melalui form di sebelah</li>
                        <li>Pastikan data desa sudah ada sebelum import anggaran/pembangunan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
