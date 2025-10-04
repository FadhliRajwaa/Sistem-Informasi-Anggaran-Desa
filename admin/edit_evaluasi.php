<?php
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/flash.php";

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT e.*, d.nama_desa, d.kecamatan FROM evaluasi e JOIN desa d ON e.id_desa = d.id_desa WHERE id_evaluasi = ?");
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
    $nama     = trim($_POST['nama']);
    $kontak   = trim($_POST['kontak']);
    $kategori = trim($_POST['kategori']);
    $laporan  = trim($_POST['laporan']);
    $status   = trim($_POST['status']);

    $stmt = $conn->prepare("UPDATE evaluasi SET nama=?, kontak=?, kategori=?, laporan=?, status=? WHERE id_evaluasi=?");
    $stmt->bind_param("sssssi", $nama, $kontak, $kategori, $laporan, $status, $id);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        flash_set('success', 'Berhasil', 'Perubahan evaluasi telah disimpan.');
    } else {
        flash_set('error', 'Gagal', 'Gagal menyimpan perubahan: ' . $err);
    }
    header("Location: dashboard.php#evaluasi");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Evaluasi - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        // Jangan override palette "orange" default agar kelas bg-orange-500/300/400 tetap tersedia
        tailwind.config = { theme: { extend: { colors: { primary:'#08D9D6', accent:'#FF2E63', dark:'#252A34', 'brand-orange':'#f97316' } } } }
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
            <div class="bg-orange-600 bg-gradient-to-r from-[#f97316] to-[#ea580c] text-white rounded-2xl p-5 shadow-lg flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center"><i class="fas fa-comments"></i></div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Edit Evaluasi: <?= htmlspecialchars($data['kecamatan']) ?> - <?= htmlspecialchars($data['nama_desa']) ?></h1>
                    <p class="text-white/80 text-sm">Perbarui data evaluasi</p>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelapor</label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                            <input type="text" name="kontak" value="<?= htmlspecialchars($data['kontak'] ?? '') ?>" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all">
                                <option value="Pelaporan Masalah" <?= ($data['kategori'] ?? '') == 'Pelaporan Masalah' ? 'selected' : '' ?>>Pelaporan Masalah</option>
                                <option value="Pemantauan Proyek" <?= ($data['kategori'] ?? '') == 'Pemantauan Proyek' ? 'selected' : '' ?>>Pemantauan Proyek</option>
                                <option value="Saran Perbaikan" <?= ($data['kategori'] ?? '') == 'Saran Perbaikan' ? 'selected' : '' ?>>Saran Perbaikan</option>
                                <option value="Aspirasi Masyarakat" <?= ($data['kategori'] ?? '') == 'Aspirasi Masyarakat' ? 'selected' : '' ?>>Aspirasi Masyarakat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all">
                                <option value="pending" <?= ($data['status'] ?? 'pending') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= ($data['status'] ?? '') == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= ($data['status'] ?? '') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Laporan/Evaluasi</label>
                        <textarea name="laporan" rows="5" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400 transition-all"><?= htmlspecialchars($data['laporan']) ?></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-xl hover:opacity-90 transition-all"><i class="fas fa-save"></i><span>Update</span></button>
                        <a href="dashboard.php#evaluasi" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition-all"><i class="fas fa-arrow-left"></i><span>Kembali</span></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php /* loading overlay */ include "../includes/ui.php"; ?>
</body>
</html>
