<?php 
include "includes/db.php";

$id_desa = isset($_GET['id_desa']) ? (int)$_GET['id_desa'] : 0;

// Validasi ID desa
if ($id_desa <= 0) {
    header("Location: index.php");
    exit;
}

// Ambil data desa
$stmt = $conn->prepare("SELECT * FROM desa WHERE id_desa = ?");
$stmt->bind_param("i", $id_desa);
$stmt->execute();
$result = $stmt->get_result();
$desa = $result->fetch_assoc();
$stmt->close();

if (!$desa) {
    header("Location: index.php");
    exit;
}

// Ambil data anggaran sesuai urutan prioritas
$anggaran_list = [];
$jenis_prioritas = ['Dana Desa', 'Alokasi Dana Desa', 'Bantuan Keuangan Provinsi', 'Swadaya Masyarakat', 'Bagi Hasil Pajak', 'Pendapatan Asli Desa', 'Lain-lain'];

$stmt = $conn->prepare("SELECT * FROM anggaran WHERE id_desa = ? ORDER BY tahun DESC, jenis_anggaran");
$stmt->bind_param("i", $id_desa);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $anggaran_list[] = $row;
}
$stmt->close();

// Group anggaran by tahun untuk tabel yang rapi
$anggaran_by_year = [];
foreach ($anggaran_list as $ang) {
    $anggaran_by_year[$ang['tahun']][] = $ang;
}

// Ambil data pembangunan
$stmt = $conn->prepare("SELECT * FROM pembangunan WHERE id_desa = ? ORDER BY tahun DESC");
$stmt->bind_param("i", $id_desa);
$stmt->execute();
$result = $stmt->get_result();
$pembangunan_list = [];
while ($row = $result->fetch_assoc()) {
    $pembangunan_list[] = $row;
}
$stmt->close();

// Ambil evaluasi yang sudah approved
$stmt = $conn->prepare("SELECT * FROM evaluasi WHERE id_desa = ? AND status = 'approved' ORDER BY tanggal DESC");
$stmt->bind_param("i", $id_desa);
$stmt->execute();
$result = $stmt->get_result();
$evaluasi_list = [];
while ($row = $result->fetch_assoc()) {
    $evaluasi_list[] = $row;
}
$stmt->close();

// Hitung total anggaran
$total_anggaran = array_sum(array_column($anggaran_list, 'jumlah'));
$total_pembangunan = array_sum(array_column($pembangunan_list, 'realisasi'));

$page_title = "Desa " . $desa['nama_desa'];
include "includes/header.php";
?>

<!-- Hero Section -->
<section class="relative gradient-bg py-20 overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-1/4 left-1/4 w-80 h-80 bg-primary/20 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <!-- Village Info -->
            <div class="text-center lg:text-left animate-slide-up">
                <div class="flex items-center justify-center lg:justify-start space-x-3 mb-4">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-lg border border-white/30">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white">
                            Desa <?= htmlspecialchars($desa['nama_desa']) ?>
                        </h1>
                        <p class="text-primary-100 text-lg">Kecamatan <?= htmlspecialchars($desa['kecamatan']) ?></p>
                    </div>
                </div>
                
                <?php if ($desa['kepala_desa']): ?>
                <div class="bg-white/20 backdrop-blur-lg rounded-xl p-4 border border-white/30 mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-white text-lg"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-white/80 text-sm">Kepala Desa</p>
                            <p class="text-white font-semibold text-lg"><?= htmlspecialchars($desa['kepala_desa']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-end animate-slide-up" style="animation-delay: 0.3s">
                <a href="index.php" class="group bg-white/20 backdrop-blur-lg text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2 border border-white/30">
                    <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform duration-300"></i>
                    <span>Kembali</span>
                </a>
                <a href="evaluasi.php?id_desa=<?= $id_desa ?>" class="group bg-accent text-white px-6 py-3 rounded-xl font-semibold hover:bg-accent-600 transition-all duration-300 hover:scale-105 hover:shadow-lg hover:shadow-accent/25 flex items-center justify-center space-x-2">
                    <i class="fas fa-comments group-hover:rotate-12 transition-transform duration-300"></i>
                    <span>Kirim Evaluasi</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Cards -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            <!-- Total Anggaran -->
            <div class="group animate-slide-up hover-lift">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border border-primary/10 hover:border-primary/30 transition-all duration-300 h-full">
                    <div class="relative mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-primary/25 transition-all duration-300">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-4 h-4 bg-accent rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-1 group-hover:scale-110 transition-transform duration-300">
                        Rp <?= number_format($total_anggaran / 1000000, 1) ?>M
                    </h3>
                    <p class="text-dark-600 font-medium">Total Anggaran</p>
                </div>
            </div>
            
            <!-- Proyek Pembangunan -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.1s">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border border-accent/10 hover:border-accent/30 transition-all duration-300 h-full">
                    <div class="relative mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent to-accent-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-accent/25 transition-all duration-300">
                            <i class="fas fa-hammer text-white text-xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-4 h-4 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-accent mb-1 group-hover:scale-110 transition-transform duration-300">
                        <?= count($pembangunan_list) ?>
                    </h3>
                    <p class="text-dark-600 font-medium">Proyek Pembangunan</p>
                </div>
            </div>
            
            <!-- Realisasi Pembangunan -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.2s">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border border-dark-200 hover:border-dark-400 transition-all duration-300 h-full">
                    <div class="relative mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-dark-600 to-dark-800 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-dark-800/25 transition-all duration-300">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-4 h-4 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-dark-800 mb-1 group-hover:scale-110 transition-transform duration-300">
                        Rp <?= number_format($total_pembangunan / 1000000, 1) ?>M
                    </h3>
                    <p class="text-dark-600 font-medium">Realisasi Pembangunan</p>
                </div>
            </div>
            
            <!-- Evaluasi Publik -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.3s">
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center border border-primary/10 hover:border-primary/30 transition-all duration-300 h-full">
                    <div class="relative mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-accent rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-primary/25 transition-all duration-300">
                            <i class="fas fa-comments text-white text-xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-4 h-4 bg-accent rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-2xl font-bold text-primary mb-1 group-hover:scale-110 transition-transform duration-300">
                        <?= count($evaluasi_list) ?>
                    </h3>
                    <p class="text-dark-600 font-medium">Evaluasi Publik</p>
                </div>
            </div>
        </div>

        <!-- Modern Tab Navigation -->
        <div class="bg-white rounded-3xl shadow-xl border border-primary/10 overflow-hidden animate-fade-in">
            <!-- Tab Headers -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-2">
                <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-1">
                    <button onclick="showTab('profil')" id="tab-profil" class="tab-button active flex-1 px-6 py-4 rounded-xl font-semibold text-left transition-all duration-300 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-primary text-white">
                            <i class="fas fa-home"></i>
                        </div>
                        <div>
                            <p class="font-bold text-dark-800">Profil Desa</p>
                            <p class="text-sm text-dark-500">Informasi dasar</p>
                        </div>
                    </button>
                    <button onclick="showTab('anggaran')" id="tab-anggaran" class="tab-button flex-1 px-6 py-4 rounded-xl font-semibold text-left transition-all duration-300 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-accent text-white">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div>
                            <p class="font-bold text-dark-800">Jenis Anggaran</p>
                            <p class="text-sm text-dark-500">Detail anggaran</p>
                        </div>
                    </button>
                    <button onclick="showTab('pembangunan')" id="tab-pembangunan" class="tab-button flex-1 px-6 py-4 rounded-xl font-semibold text-left transition-all duration-300 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-dark-600 text-white">
                            <i class="fas fa-hammer"></i>
                        </div>
                        <div>
                            <p class="font-bold text-dark-800">Pembangunan</p>
                            <p class="text-sm text-dark-500">Proyek & realisasi</p>
                        </div>
                    </button>
                    <button onclick="showTab('evaluasi')" id="tab-evaluasi" class="tab-button flex-1 px-6 py-4 rounded-xl font-semibold text-left transition-all duration-300 flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-primary-600 text-white">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div>
                            <p class="font-bold text-dark-800">Evaluasi</p>
                            <p class="text-sm text-dark-500">Feedback masyarakat</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Profil Tab Content -->
                <div id="content-profil" class="tab-content-item animate-fade-in">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Village Information -->
                        <div class="lg:col-span-2">
                            <div class="bg-gradient-to-br from-primary/5 to-accent/5 rounded-2xl p-8 border border-primary/10">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                    <h3 class="text-2xl font-bold text-dark-800">Informasi Desa</h3>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Basic Info -->
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between py-3 border-b border-primary/10">
                                            <span class="font-semibold text-dark-600">Nama Desa</span>
                                            <span class="font-bold text-dark-800"><?= htmlspecialchars($desa['nama_desa']) ?></span>
                                        </div>
                                        <div class="flex items-center justify-between py-3 border-b border-primary/10">
                                            <span class="font-semibold text-dark-600">Kecamatan</span>
                                            <span class="font-bold text-dark-800"><?= htmlspecialchars($desa['kecamatan']) ?></span>
                                        </div>
                                        <div class="flex items-center justify-between py-3 border-b border-primary/10">
                                            <span class="font-semibold text-dark-600">Kepala Desa</span>
                                            <span class="font-bold text-dark-800"><?= htmlspecialchars($desa['kepala_desa'] ?: 'Belum diisi') ?></span>
                                        </div>
                                    </div>
                                    
                                    <!-- Summary Info -->
                                    <div class="space-y-6">
                                        <div class="flex items-center justify-between py-3 border-b border-accent/10">
                                            <span class="font-semibold text-dark-600">Total Anggaran</span>
                                            <span class="font-bold text-accent">Rp <?= number_format($total_anggaran / 1000000, 1) ?>M</span>
                                        </div>
                                        <div class="flex items-center justify-between py-3 border-b border-accent/10">
                                            <span class="font-semibold text-dark-600">Jumlah Proyek</span>
                                            <span class="font-bold text-accent"><?= count($pembangunan_list) ?> proyek</span>
                                        </div>
                                        <div class="flex items-center justify-between py-3 border-b border-accent/10">
                                            <span class="font-semibold text-dark-600">Total Realisasi</span>
                                            <span class="font-bold text-accent">Rp <?= number_format($total_pembangunan / 1000000, 1) ?>M</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Budget Summary Chart -->
                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 h-full">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center">
                                        <i class="fas fa-chart-pie text-white"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-dark-800">Ringkasan Anggaran</h4>
                                </div>
                                
                                <?php if (!empty($anggaran_list)): ?>
                                    <?php
                                    $anggaran_summary = [];
                                    foreach ($anggaran_list as $ang) {
                                        $anggaran_summary[$ang['jenis_anggaran']] = ($anggaran_summary[$ang['jenis_anggaran']] ?? 0) + $ang['jumlah'];
                                    }
                                    $colors = ['bg-primary', 'bg-accent', 'bg-dark-600', 'bg-primary-400', 'bg-accent-400', 'bg-dark-400', 'bg-gray-400'];
                                    $i = 0;
                                    ?>
                                    <div class="space-y-4">
                                        <?php foreach ($anggaran_summary as $jenis => $total): ?>
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="w-3 h-3 <?= $colors[$i % count($colors)] ?> rounded-full"></div>
                                                        <span class="text-sm font-medium text-dark-700"><?= htmlspecialchars($jenis) ?></span>
                                                    </div>
                                                    <span class="text-sm font-bold text-dark-800">Rp <?= number_format($total / 1000000, 1) ?>M</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="<?= $colors[$i % count($colors)] ?> h-2 rounded-full transition-all duration-500" style="width: <?= ($total / $total_anggaran) * 100 ?>%"></div>
                                                </div>
                                                <div class="text-xs text-dark-500 text-right"><?= number_format(($total / $total_anggaran) * 100, 1) ?>%</div>
                                            </div>
                                            <?php $i++; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-8">
                                        <i class="fas fa-chart-pie text-4xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500">Belum ada data anggaran</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anggaran Tab Content -->
                <div id="content-anggaran" class="tab-content-item hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-accent rounded-xl flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-dark-800">Data Anggaran Desa</h3>
                        </div>
                        <div class="bg-gradient-to-r from-accent to-accent-600 text-white px-6 py-2 rounded-xl font-semibold">
                            Total: Rp <?= number_format($total_anggaran / 1000000, 1) ?>M
                        </div>
                    </div>

                    <?php if (!empty($anggaran_by_year)): ?>
                        <div class="space-y-8">
                            <?php foreach ($anggaran_by_year as $tahun => $anggaran_tahun): ?>
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                                    <div class="bg-gradient-to-r from-primary to-primary-600 text-white p-6">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-calendar text-2xl"></i>
                                            <h4 class="text-xl font-bold">Tahun <?= $tahun ?></h4>
                                        </div>
                                    </div>
                                    
                                    <div class="p-6">
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr class="border-b border-gray-200">
                                                        <th class="text-left py-4 px-2 font-semibold text-dark-700">No</th>
                                                        <th class="text-left py-4 px-2 font-semibold text-dark-700">Jenis Anggaran</th>
                                                        <th class="text-right py-4 px-2 font-semibold text-dark-700">Jumlah (Rp)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $no = 1;
                                                    $total_tahun = 0;
                                                    foreach ($anggaran_tahun as $ang): 
                                                        $total_tahun += $ang['jumlah'];
                                                        $badge_color = match($ang['jenis_anggaran']) {
                                                            'Dana Desa' => 'bg-primary text-white',
                                                            'Alokasi Dana Desa' => 'bg-accent text-white', 
                                                            'Bantuan Keuangan Provinsi' => 'bg-blue-500 text-white',
                                                            'Swadaya Masyarakat' => 'bg-orange-500 text-white',
                                                            'Bagi Hasil Pajak' => 'bg-gray-500 text-white',
                                                            'Pendapatan Asli Desa' => 'bg-dark-600 text-white',
                                                            default => 'bg-gray-200 text-dark-800'
                                                        };
                                                    ?>
                                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                                        <td class="py-4 px-2 text-dark-700"><?= $no++ ?></td>
                                                        <td class="py-4 px-2">
                                                            <span class="<?= $badge_color ?> px-3 py-1 rounded-full text-sm font-medium">
                                                                <?= htmlspecialchars($ang['jenis_anggaran']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="py-4 px-2 text-right font-bold text-dark-800">
                                                            Rp <?= number_format($ang['jumlah']) ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="bg-gradient-to-r from-primary/10 to-accent/10 border-t-2 border-primary">
                                                        <th colspan="2" class="py-4 px-2 font-bold text-dark-800">Total Tahun <?= $tahun ?></th>
                                                        <th class="py-4 px-2 text-right font-bold text-primary text-lg">
                                                            Rp <?= number_format($total_tahun) ?>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-money-bill-wave text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-dark-600 mb-2">Belum Ada Data Anggaran</h4>
                            <p class="text-dark-400">Data anggaran untuk desa ini belum tersedia</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pembangunan Tab Content -->
                <div id="content-pembangunan" class="tab-content-item hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-dark-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hammer text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-dark-800">Data Pembangunan Desa</h3>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-2 rounded-xl font-semibold">
                            Realisasi: Rp <?= number_format($total_pembangunan / 1000000, 1) ?>M
                        </div>
                    </div>

                    <?php if (!empty($pembangunan_list)): ?>
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-dark-600 to-dark-800 text-white">
                                        <tr>
                                            <th class="text-left py-4 px-6 font-semibold">No</th>
                                            <th class="text-left py-4 px-6 font-semibold">Tahun</th>
                                            <th class="text-left py-4 px-6 font-semibold">Kegiatan</th>
                                            <th class="text-left py-4 px-6 font-semibold">Lokasi</th>
                                            <th class="text-right py-4 px-6 font-semibold">Realisasi (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        foreach ($pembangunan_list as $pemb): 
                                        ?>
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                            <td class="py-4 px-6 text-dark-700 font-medium"><?= $no++ ?></td>
                                            <td class="py-4 px-6">
                                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-medium">
                                                    <?= $pemb['tahun'] ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="font-semibold text-dark-800 mb-1"><?= htmlspecialchars($pemb['kegiatan']) ?></div>
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="flex items-center space-x-2 text-dark-600">
                                                    <i class="fas fa-map-marker-alt text-accent"></i>
                                                    <span><?= htmlspecialchars($pemb['lokasi']) ?></span>
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <div class="font-bold text-emerald-600 text-lg">
                                                    Rp <?= number_format($pemb['realisasi']) ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-hammer text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-dark-600 mb-2">Belum Ada Data Pembangunan</h4>
                            <p class="text-dark-400">Data pembangunan untuk desa ini belum tersedia</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Evaluasi Tab Content -->
                <div id="content-evaluasi" class="tab-content-item hidden">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-comments text-white"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-dark-800">Evaluasi & Aspirasi Masyarakat</h3>
                        </div>
                        <a href="evaluasi.php?id_desa=<?= $id_desa ?>" class="bg-gradient-to-r from-accent to-accent-600 text-white px-6 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Kirim Evaluasi</span>
                        </a>
                    </div>

                    <?php if (!empty($evaluasi_list)): ?>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <?php foreach ($evaluasi_list as $eval): ?>
                                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover-lift">
                                    <div class="border-l-4 <?= match($eval['kategori']) {
                                        'Pelaporan Masalah' => 'border-red-500',
                                        'Pemantauan Proyek' => 'border-yellow-500',
                                        'Saran Perbaikan' => 'border-blue-500',
                                        'Aspirasi Masyarakat' => 'border-green-500',
                                        default => 'border-gray-500'
                                    } ?> p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-dark-800"><?= htmlspecialchars($eval['nama'] ?: 'Anonim') ?></h4>
                                                    <p class="text-sm text-dark-500 flex items-center space-x-1">
                                                        <i class="fas fa-calendar"></i>
                                                        <span><?= date('d/m/Y H:i', strtotime($eval['tanggal'])) ?></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="<?= match($eval['kategori']) {
                                                'Pelaporan Masalah' => 'bg-red-100 text-red-800',
                                                'Pemantauan Proyek' => 'bg-yellow-100 text-yellow-800',
                                                'Saran Perbaikan' => 'bg-blue-100 text-blue-800',
                                                'Aspirasi Masyarakat' => 'bg-green-100 text-green-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            } ?> px-3 py-1 rounded-full text-xs font-medium">
                                                <?= htmlspecialchars($eval['kategori'] ?: 'Umum') ?>
                                            </span>
                                        </div>
                                        <p class="text-dark-700 leading-relaxed"><?= nl2br(htmlspecialchars($eval['laporan'])) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-center mt-8">
                            <a href="evaluasi.php?id_desa=<?= $id_desa ?>" class="inline-flex items-center space-x-2 text-primary hover:text-accent transition-colors duration-300 font-medium">
                                <i class="fas fa-eye"></i>
                                <span>Lihat Semua Evaluasi</span>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-comments text-4xl text-gray-400"></i>
                            </div>
                            <h4 class="text-xl font-semibold text-dark-600 mb-2">Belum Ada Evaluasi</h4>
                            <p class="text-dark-400 mb-6">Belum ada evaluasi untuk desa ini</p>
                            <a href="evaluasi.php?id_desa=<?= $id_desa ?>" class="bg-gradient-to-r from-primary to-primary-600 text-white px-8 py-3 rounded-xl font-semibold hover:shadow-lg transition-all duration-300 inline-flex items-center space-x-2">
                                <i class="fas fa-plus"></i>
                                <span>Jadi yang Pertama Mengevaluasi</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tab JavaScript -->
<script>
    // Tab functionality
    function showTab(tabName) {
        // Hide all tab contents
        const tabContents = document.querySelectorAll('.tab-content-item');
        tabContents.forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('animate-fade-in');
        });
        
        // Remove active class from all tab buttons
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.classList.remove('active');
            button.classList.add('text-dark-600', 'hover:text-dark-800', 'hover:bg-gray-100');
            button.classList.remove('bg-white', 'text-primary', 'shadow-lg');
        });
        
        // Show selected tab content
        const selectedContent = document.getElementById(`content-${tabName}`);
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
            selectedContent.classList.add('animate-fade-in');
        }
        
        // Add active class to selected tab button
        const selectedButton = document.getElementById(`tab-${tabName}`);
        if (selectedButton) {
            selectedButton.classList.add('active');
            selectedButton.classList.remove('text-dark-600', 'hover:text-dark-800', 'hover:bg-gray-100');
            selectedButton.classList.add('bg-white', 'text-primary', 'shadow-lg');
        }
    }

    // Initialize first tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check URL hash for specific tab
        const hash = window.location.hash.substring(1);
        const validTabs = ['profil', 'anggaran', 'pembangunan', 'evaluasi'];
        
        if (hash && validTabs.includes(hash)) {
            showTab(hash);
        } else {
            showTab('profil'); // Default tab
        }
        
        // Update URL hash when tab changes
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.id.replace('tab-', '');
                window.location.hash = tabName;
            });
        });
    });

    // Handle browser back/forward buttons
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.substring(1);
        const validTabs = ['profil', 'anggaran', 'pembangunan', 'evaluasi'];
        
        if (hash && validTabs.includes(hash)) {
            showTab(hash);
        }
    });
</script>

<?php include "includes/footer.php"; ?>
