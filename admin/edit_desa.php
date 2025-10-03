<?php
include "../includes/db.php";
include "../includes/auth.php";

$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM desa WHERE id_desa = ?");
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
    $nama = trim($_POST['nama_desa']);
    $kec  = trim($_POST['kecamatan']);
    $kep  = trim($_POST['kepala_desa']);
    
    $stmt = $conn->prepare("UPDATE desa SET nama_desa=?, kecamatan=?, kepala_desa=? WHERE id_desa=?");
    $stmt->bind_param("sssi", $nama, $kec, $kep, $id);
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
    <title>Edit Desa - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#08D9D6', accent: '#FF2E63', dark: '#252A34' } } }
        }
    </script>
    <style>
        .glass-effect { backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
        @keyframes fadeIn { 0% { opacity:.0; transform: translateY(8px);} 100% { opacity:1; transform: translateY(0);} }
        .animate-fade-in { animation: fadeIn .4s ease-out; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-6">
        <div class="max-w-xl mx-auto animate-fade-in">
            <div class="bg-gradient-to-r from-primary to-primary-600 text-white rounded-2xl p-5 shadow-lg flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Edit Desa: <?= htmlspecialchars($data['nama_desa']) ?></h1>
                    <p class="text-white/80 text-sm">Perbarui data desa dengan benar</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mt-4 glass-effect">
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Desa</label>
                        <input type="text" name="nama_desa" value="<?= htmlspecialchars($data['nama_desa']) ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" value="<?= htmlspecialchars($data['kecamatan']) ?>" required class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Desa</label>
                        <input type="text" name="kepala_desa" value="<?= htmlspecialchars($data['kepala_desa']) ?>" class="w-full rounded-xl border border-gray-200 px-3 py-2 focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all" />
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl hover:opacity-90 transition-all">
                            <i class="fas fa-save"></i><span>Update</span>
                        </button>
                        <a href="dashboard.php" class="inline-flex items-center gap-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl hover:bg-gray-200 transition-all">
                            <i class="fas fa-arrow-left"></i><span>Kembali</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
