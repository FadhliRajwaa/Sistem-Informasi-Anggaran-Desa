<?php 
include "includes/db.php";

$id_desa = isset($_GET['id_desa']) ? (int)$_GET['id_desa'] : 0;
$message = '';

// Ambil data desa jika ada
$desa_info = null;
if ($id_desa > 0) {
    $stmt = $conn->prepare("SELECT * FROM desa WHERE id_desa = ?");
    $stmt->bind_param("i", $id_desa);
    $stmt->execute();
    $result = $stmt->get_result();
    $desa_info = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submit evaluasi baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_evaluasi'])) {
    $nama = trim($_POST['nama']);
    $kontak = trim($_POST['kontak']);
    $kategori = trim($_POST['kategori']);
    $laporan = trim($_POST['laporan']);
    $id_desa_form = (int)$_POST['id_desa'];
    
    if (!empty($laporan) && $id_desa_form > 0) {
        $stmt = $conn->prepare("INSERT INTO evaluasi (id_desa, nama, kontak, kategori, laporan, status, tanggal) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("issss", $id_desa_form, $nama, $kontak, $kategori, $laporan);
        
        if ($stmt->execute()) {
            $message = 'success';
        } else {
            $message = 'error';
        }
        $stmt->close();
    } else {
        $message = 'warning';
    }
}

// Ambil evaluasi yang sudah approved untuk desa ini
$evaluasi_list = [];
if ($id_desa > 0) {
    $stmt = $conn->prepare("SELECT * FROM evaluasi WHERE id_desa = ? AND status = 'approved' ORDER BY tanggal DESC LIMIT 10");
    $stmt->bind_param("i", $id_desa);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $evaluasi_list[] = $row;
    }
    $stmt->close();
}

$page_title = "Evaluasi & Aspirasi";
include "includes/header.php";
?>

<!-- Hero Section -->
<section class="relative min-h-screen gradient-bg flex items-center justify-center overflow-hidden">
    <!-- Floating Elements Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-accent/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <!-- Header -->
        <div class="text-center mb-12 animate-slide-up">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/30 animate-float">
                <i class="fas fa-comments text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                Evaluasi & Aspirasi
            </h1>
            <?php if ($desa_info): ?>
                <p class="text-lg sm:text-xl text-gray-200 leading-relaxed mb-8 max-w-2xl mx-auto">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    Desa <?= htmlspecialchars($desa_info['nama_desa']) ?>, Kecamatan <?= htmlspecialchars($desa_info['kecamatan']) ?>
                </p>
            <?php else: ?>
                <p class="text-lg sm:text-xl text-gray-200 leading-relaxed mb-8 max-w-2xl mx-auto">
                    Sampaikan aspirasi dan evaluasi Anda untuk pembangunan desa yang lebih baik
                </p>
            <?php endif; ?>
            
            <a href="index.php" class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-lg text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/20">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
        
        <?php if ($desa_info): ?>
            <div class="text-center mt-8">
                <a href="desa.php?id_desa=<?= $id_desa ?>" class="inline-flex items-center space-x-2 bg-accent/20 backdrop-blur-lg text-white px-6 py-3 rounded-xl font-semibold hover:bg-accent/30 transition-all duration-300 border border-accent/30">
                    <i class="fas fa-home"></i>
                    <span>Lihat Profil Desa</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Form Section -->
<section class="min-h-screen bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success/Error Modal -->
        <?php if ($message): ?>
            <?php
                $t = $message;
                $title = $t==='success' ? 'Evaluasi Berhasil Dikirim!' : ($t==='error' ? 'Gagal Mengirim Evaluasi' : 'Data Tidak Lengkap');
                $text  = $t==='success' ? 'Evaluasi Anda berhasil dikirim dan sedang menunggu moderasi. Terima kasih.' : ($t==='error' ? 'Terjadi kesalahan saat mengirim evaluasi. Silakan coba lagi.' : 'Laporan harus diisi dan desa harus dipilih.');
                $ico   = $t==='success' ? 'fa-check-circle text-green-600' : ($t==='error' ? 'fa-times-circle text-red-600' : 'fa-exclamation-triangle text-yellow-600');
                $bar   = $t==='success' ? 'bg-green-500' : ($t==='error' ? 'bg-red-500' : 'bg-yellow-500');
            ?>
            <div id="public-flash" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-[90%] p-6 border" role="dialog" aria-modal="true">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-50 border">
                            <i class="fas <?= $ico ?> text-xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-dark-800"><?= htmlspecialchars($title) ?></h3>
                    </div>
                    <p class="text-dark-600 mb-4"><?= htmlspecialchars($text) ?></p>
                    <div class="flex justify-end">
                        <button id="public-flash-close" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white <?= $bar ?> hover:opacity-90">
                            <i class="fas fa-check"></i>
                            <span>Tutup</span>
                        </button>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const btn = document.getElementById('public-flash-close');
                    const m = document.getElementById('public-flash');
                    if(btn && m){ btn.addEventListener('click', ()=> m.remove()); }
                });
            </script>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden hover-lift">
                    <div class="bg-gradient-to-r from-primary to-accent p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-pencil-alt mr-3"></i>
                            Kirim Evaluasi & Aspirasi
                        </h2>
                        <p class="text-white/80 mt-2">Sampaikan masukan dan saran Anda untuk pembangunan desa</p>
                    </div>
                    
                    <div class="p-8">
                        <form method="post" class="space-y-6">
                            <input type="hidden" name="id_desa" value="<?= $id_desa ?>">
                            
                            <?php if (!$desa_info): ?>
                            <div class="space-y-2">
                                <label class="block text-dark-700 font-semibold flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                    <span>Pilih Desa</span>
                                </label>
                                <select name="id_desa" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" required>
                                    <option value="">-- Pilih Desa --</option>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM desa ORDER BY kecamatan, nama_desa");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while($d = $result->fetch_assoc()) {
                                        echo "<option value='".$d['id_desa']."'>".htmlspecialchars($d['kecamatan'])." - ".htmlspecialchars($d['nama_desa'])."</option>";
                                    }
                                    $stmt->close();
                                    ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-dark-700 font-semibold flex items-center space-x-2">
                                        <i class="fas fa-user text-primary"></i>
                                        <span>Nama (Opsional)</span>
                                    </label>
                                    <input type="text" name="nama" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" placeholder="Nama Anda">
                                    <p class="text-sm text-dark-500">Boleh dikosongkan jika ingin anonim</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-dark-700 font-semibold flex items-center space-x-2">
                                        <i class="fas fa-phone text-primary"></i>
                                        <span>Kontak (Opsional)</span>
                                    </label>
                                    <input type="text" name="kontak" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" placeholder="No. HP atau Email">
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-dark-700 font-semibold flex items-center space-x-2">
                                    <i class="fas fa-tag text-primary"></i>
                                    <span>Kategori</span>
                                </label>
                                <select name="kategori" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Pelaporan Masalah">Pelaporan Masalah</option>
                                    <option value="Pemantauan Proyek">Pemantauan Proyek</option>
                                    <option value="Saran Perbaikan">Saran Perbaikan</option>
                                    <option value="Aspirasi Masyarakat">Aspirasi Masyarakat</option>
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-dark-700 font-semibold flex items-center space-x-2">
                                    <i class="fas fa-edit text-primary"></i>
                                    <span>Laporan / Evaluasi / Aspirasi</span>
                                </label>
                                <textarea name="laporan" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300 resize-none" required placeholder="Tuliskan laporan, evaluasi, atau aspirasi Anda secara detail..."></textarea>
                            </div>
                            
                            <button type="submit" name="submit_evaluasi" class="w-full bg-gradient-to-r from-primary to-accent text-white font-bold py-4 px-6 rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2">
                                <i class="fas fa-paper-plane"></i>
                                <span>Kirim Evaluasi</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar Information -->
            <div class="space-y-6">
                <!-- Information Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-accent to-accent-600 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Penting
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-dark-600">Evaluasi akan ditinjau terlebih dahulu sebelum dipublikasikan</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-shield-alt text-blue-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-dark-600">Gunakan bahasa yang sopan dan konstruktif</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-dark-600">Berikan informasi yang akurat dan dapat dipertanggungjawabkan</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-eye text-purple-600 text-xs"></i>
                                </div>
                                <p class="text-sm text-dark-600">Evaluasi yang disetujui akan ditampilkan di halaman desa</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-dark-600 to-dark-800 p-4">
                        <h3 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-tags mr-2"></i>
                            Kategori Evaluasi
                        </h3>
                    </div>
                    <div class="p-6">
                            <div class="flex items-center space-x-2 p-3 bg-red-50 rounded-lg border border-red-200">
                                <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                                </div>
                                <span class="text-red-800 font-medium">Pelaporan Masalah</span>
                            </div>
                            <div class="flex items-center space-x-2 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-eye text-white text-sm"></i>
                                </div>
                                <span class="text-yellow-800 font-medium">Pemantauan Proyek</span>
                            </div>
                            <div class="flex items-center space-x-2 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-lightbulb text-white text-sm"></i>
                                </div>
                                <span class="text-blue-800 font-medium">Saran Perbaikan</span>
                            </div>
                            <div class="flex items-center space-x-2 p-3 bg-green-50 rounded-lg border border-green-200">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-heart text-white text-sm"></i>
                                </div>
                                <span class="text-green-800 font-medium">Aspirasi Masyarakat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluasi List -->
        <?php if ($desa_info && !empty($evaluasi_list)): ?>
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-dark-800 text-center mb-12">Evaluasi & Aspirasi Terbaru</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($evaluasi_list as $eval): 
                        $kategori_colors = [
                            'Pelaporan Masalah' => 'border-l-red-500 bg-red-50',
                            'Pemantauan Proyek' => 'border-l-yellow-500 bg-yellow-50', 
                            'Saran Perbaikan' => 'border-l-blue-500 bg-blue-50',
                            'Aspirasi Masyarakat' => 'border-l-green-500 bg-green-50'
                        ];
                        $color = $kategori_colors[$eval['kategori']] ?? 'border-l-gray-500 bg-gray-50';
                    ?>
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 border-l-4 <?= $color ?> overflow-hidden hover-lift">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <span class="font-semibold text-dark-800"><?= htmlspecialchars($eval['nama'] ?: 'Anonim') ?></span>
                                    </div>
                                    <span class="text-xs text-dark-500"><?= date('d/m/Y', strtotime($eval['tanggal'])) ?></span>
                                </div>
                                
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-white">
                                        <?= htmlspecialchars($eval['kategori']) ?>
                                    </span>
                                </div>
                                
                                <p class="text-dark-600 leading-relaxed"><?= nl2br(htmlspecialchars($eval['laporan'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include "includes/footer.php"; ?>
