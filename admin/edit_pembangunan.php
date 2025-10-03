<?php
include "../includes/db.php";
include "../includes/auth.php";

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT p.*, d.nama_desa, d.kecamatan FROM pembangunan p JOIN desa d ON p.id_desa = d.id_desa WHERE id_pembangunan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD']=="POST") {
    $tahun     = (int)$_POST['tahun'];
    $kegiatan  = trim($_POST['kegiatan']);
    $lokasi    = trim($_POST['lokasi']);
    $realisasi = (float)$_POST['realisasi'];

    $stmt = $conn->prepare("UPDATE pembangunan SET tahun=?, kegiatan=?, lokasi=?, realisasi=? WHERE id_pembangunan=?");
    $stmt->bind_param("issdi", $tahun, $kegiatan, $lokasi, $realisasi, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembangunan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#08D9D6', accent: '#FF2E63', dark: '#252A34', 'primary-600':'#06b0ad', 'accent-600':'#d11149', 'dark-800':'#1f2430' } } } }
    </script>
    <style>
        .glass-effect{backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px)}
        @keyframes fadeIn{0%{opacity:0;transform:translateY(8px)}100%{opacity:1;transform:translateY(0)}}
        .animate-fade-in{animation:fadeIn .4s ease-out}
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-6">
        <div class="max-w-2xl mx-auto animate-fade-in">
            <div class="bg-gradient-to-r from-dark to-dark-800 text-white rounded-2xl p-5 shadow-lg flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-hammer"></i></div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Edit Pembangunan: <?= htmlspecialchars($data['kecamatan']) ?> - <?= htmlspecialchars($data['nama_desa']) ?></h1>
                    <p class="text-white/80 text-sm">Perbarui data pembangunan</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mt-4 glass-effect">
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
                        <input type="text" value="<?= htmlspecialchars($data['kecamatan']) ?> - <?= htmlspecialchars($data['nama_desa']) ?>" readonly class="w-full rounded-xl border border-gray-200 px-3 py-2 bg-gray-50 text-gray-600" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <input type="number" name="tahun" min="2020" max="2030" value="<?= $data['tahun'] ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-dark/30 focus:border-dark transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Realisasi (Rp)</label>
                            <input type="number" name="realisasi" min="0" step="0.01" value="<?= $data['realisasi'] ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-dark/30 focus:border-dark transition-all" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan Pembangunan</label>
                        <textarea name="kegiatan" rows="3" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-dark/30 focus:border-dark transition-all"><?= htmlspecialchars($data['kegiatan']) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-dark/30 focus:border-dark transition-all" />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-dark text-white px-4 py-2 rounded-xl hover:opacity-90 transition-all"><i class="fas fa-save"></i><span>Update</span></button>
                        <a href="dashboard.php" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition-all"><i class="fas fa-arrow-left"></i><span>Kembali</span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
