<?php 
include "includes/db.php"; 
$page_title = "Beranda";

// Statistik untuk dashboard
$total_desa = $conn->query("SELECT COUNT(*) as total FROM desa")->fetch_assoc()['total'];
$total_kecamatan = $conn->query("SELECT COUNT(DISTINCT kecamatan) as total FROM desa")->fetch_assoc()['total'];
$total_anggaran = $conn->query("SELECT SUM(jumlah) as total FROM anggaran")->fetch_assoc()['total'] ?: 0;
$total_pembangunan = $conn->query("SELECT COUNT(*) as total FROM pembangunan")->fetch_assoc()['total'];

include "includes/header.php"; 
?>

<!-- Hero Section dengan Gradient Background -->
<section class="relative min-h-screen gradient-bg flex items-center justify-center overflow-hidden">
    <!-- Floating Elements Background -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute top-3/4 right-1/4 w-96 h-96 bg-primary/20 rounded-full blur-3xl animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-accent/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Content Section -->
            <div class="text-center lg:text-left animate-slide-up" data-aos="fade-right">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    <span class="block">Sistem Informasi</span>
                    <span class="block bg-gradient-to-r from-primary-200 via-white to-accent-200 bg-clip-text text-transparent">
                        Transparansi Desa
                    </span>
                </h1>
                
                <p class="text-lg sm:text-xl text-gray-200 leading-relaxed mb-8 max-w-2xl">
                    Platform digital untuk mengakses informasi transparan anggaran dan pembangunan desa. 
                    Mendukung akuntabilitas pemerintahan desa yang lebih baik.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#cari-desa" class="group bg-white text-dark-800 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-gray-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl flex items-center justify-center space-x-2">
                        <i class="fas fa-search group-hover:text-primary transition-colors duration-300"></i>
                        <span>Cari Desa</span>
                    </a>
                    <a href="evaluasi.php" class="group border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-dark-800 transition-all duration-300 hover:scale-105 hover:shadow-2xl flex items-center justify-center space-x-2">
                        <i class="fas fa-comments group-hover:text-accent transition-colors duration-300"></i>
                        <span>Kirim Evaluasi</span>
                    </a>
                </div>
            </div>
            
            <!-- Visual Section -->
            <div class="relative flex justify-center lg:justify-end animate-slide-up" data-aos="fade-left" style="animation-delay: 0.3s">
                <div class="relative">
                    <!-- Main Icon with Glow Effect -->
                    <div class="relative w-56 h-56 sm:w-72 sm:h-72 lg:w-80 lg:h-80 flex items-center justify-center mx-auto lg:mx-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary via-accent to-primary rounded-full opacity-30 blur-2xl animate-pulse-slow"></div>
                        <div class="relative bg-white/20 backdrop-blur-lg rounded-full p-12 sm:p-14 lg:p-16 border border-white/30">
                            <i class="fas fa-city text-white text-6xl sm:text-7xl lg:text-8xl animate-float"></i>
                        </div>
                    </div>
                    
                    <!-- Floating mini cards -->
                    <div class="absolute -top-4 -left-4 bg-white/90 backdrop-blur-lg rounded-lg p-3 shadow-xl animate-float" style="animation-delay: 0.5s">
                        <i class="fas fa-chart-line text-primary text-lg"></i>
                    </div>
                    <div class="absolute -bottom-4 -right-4 bg-white/90 backdrop-blur-lg rounded-lg p-3 shadow-xl animate-float" style="animation-delay: 1s">
                        <i class="fas fa-users text-accent text-lg"></i>
                    </div>
                    <div class="absolute top-1/2 -right-8 bg-white/90 backdrop-blur-lg rounded-lg p-3 shadow-xl animate-float" style="animation-delay: 1.5s">
                        <i class="fas fa-building text-primary text-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#statistics" class="text-white/70 hover:text-white transition-colors duration-300">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section id="statistics" class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, #08D9D6 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-dark-800 mb-4">
                Data <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">Transparansi</span>
            </h2>
            <p class="text-lg text-dark-600 max-w-3xl mx-auto leading-relaxed">
                Statistik real-time sistem informasi transparansi desa yang terintegrasi
            </p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Total Desa -->
            <div class="group animate-slide-up hover-lift">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center border border-primary/10 hover:border-primary/30 transition-all duration-300 h-full">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-primary/25 transition-all duration-300">
                            <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-primary mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?= number_format($total_desa) ?>
                    </h3>
                    <p class="text-dark-600 font-medium">Total Desa</p>
                    <p class="text-sm text-dark-400 mt-2">Desa terdaftar</p>
                </div>
            </div>
            
            <!-- Kecamatan -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.1s">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center border border-accent/10 hover:border-accent/30 transition-all duration-300 h-full">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-accent to-accent-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-accent/25 transition-all duration-300">
                            <i class="fas fa-building text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-accent mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?= number_format($total_kecamatan) ?>
                    </h3>
                    <p class="text-dark-600 font-medium">Kecamatan</p>
                    <p class="text-sm text-dark-400 mt-2">Kecamatan aktif</p>
                </div>
            </div>
            
            <!-- Total Anggaran -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.2s">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center border border-dark-200 hover:border-dark-400 transition-all duration-300 h-full">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-dark-600 to-dark-800 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-dark-800/25 transition-all duration-300">
                            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-dark-800 mb-2 group-hover:scale-110 transition-transform duration-300">
                        Rp <?= number_format($total_anggaran / 1000000000, 1) ?>M
                    </h3>
                    <p class="text-dark-600 font-medium">Total Anggaran</p>
                    <p class="text-sm text-dark-400 mt-2">Dalam milyar rupiah</p>
                </div>
            </div>
            
            <!-- Proyek Pembangunan -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.3s">
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center border border-primary/10 hover:border-primary/30 transition-all duration-300 h-full">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-accent rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-primary/25 transition-all duration-300">
                            <i class="fas fa-hammer text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-4xl font-bold text-primary mb-2 group-hover:scale-110 transition-transform duration-300">
                        <?= number_format($total_pembangunan) ?>
                    </h3>
                    <p class="text-dark-600 font-medium">Proyek Pembangunan</p>
                    <p class="text-sm text-dark-400 mt-2">Proyek terdaftar</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Search Section -->
<section id="cari-desa" class="py-20 bg-gradient-to-br from-primary/5 to-accent/5 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-72 h-72 bg-primary/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-12 animate-fade-in">
            <h2 class="text-3xl sm:text-4xl font-bold text-dark-800 mb-4">
                <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">Cari</span> Informasi Desa
            </h2>
            <p class="text-lg text-dark-600 max-w-2xl mx-auto">
                Pilih kecamatan dan desa untuk mengakses informasi transparansi anggaran dan pembangunan
            </p>
        </div>
        
        <!-- Search Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-primary/10 overflow-hidden animate-slide-up">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-primary to-primary-600 p-8 text-center">
                <div class="flex items-center justify-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-search text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white">Portal Pencarian</h3>
                </div>
                <p class="text-primary-100">Temukan data transparansi desa dengan mudah</p>
            </div>
            
            <!-- Card Body -->
            <div class="p-8">
                <!-- Form Pilih Kecamatan -->
                <form method="get" class="mb-6">
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-dark-700">
                            <div class="flex items-center space-x-2 mb-3">
                                <div class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-building text-primary text-sm"></i>
                                </div>
                                <span>Pilih Kecamatan</span>
                            </div>
                        </label>
                        <select name="kecamatan" id="kecamatan" class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-dark-800 font-medium focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" onchange="this.form.submit()">
                            <option value="">-- Pilih Kecamatan --</option>
                            <?php
                            $stmt = $conn->prepare("SELECT DISTINCT kecamatan FROM desa ORDER BY kecamatan");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while ($row = $result->fetch_assoc()) {
                                $selected = (isset($_GET['kecamatan']) && $_GET['kecamatan'] == $row['kecamatan']) ? "selected" : "";
                                echo "<option $selected value='".htmlspecialchars($row['kecamatan'])."'>".htmlspecialchars($row['kecamatan'])."</option>";
                            }
                            $stmt->close();
                            ?>
                        </select>
                    </div>
                </form>

                <!-- Form Pilih Desa (tampil jika kecamatan sudah dipilih) -->
                <?php if (isset($_GET['kecamatan']) && !empty($_GET['kecamatan'])): ?>
                    <!-- Success Alert -->
                    <div class="bg-gradient-to-r from-primary/10 to-accent/10 border border-primary/20 rounded-xl p-4 mb-6 animate-fade-in">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-dark-800">Kecamatan Terpilih</p>
                                <p class="text-primary font-bold"><?= htmlspecialchars($_GET['kecamatan']) ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="desa.php" method="get" class="space-y-6">
                        <input type="hidden" name="kecamatan" value="<?= htmlspecialchars($_GET['kecamatan']) ?>">
                        
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-dark-700">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-accent text-sm"></i>
                                    </div>
                                    <span>Pilih Desa</span>
                                </div>
                            </label>
                            <select name="id_desa" id="id_desa" class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl text-dark-800 font-medium focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300" required>
                                <option value="">-- Pilih Desa --</option>
                                <?php
                                $kecamatan = $_GET['kecamatan'];
                                $stmt = $conn->prepare("SELECT * FROM desa WHERE kecamatan = ? ORDER BY nama_desa");
                                $stmt->bind_param("s", $kecamatan);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($d = $result->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($d['id_desa'])."'>".htmlspecialchars($d['nama_desa'])."</option>";
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-accent to-accent-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:shadow-lg hover:shadow-accent/25 transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2">
                            <i class="fas fa-eye"></i>
                            <span>Lihat Informasi Desa</span>
                        </button>
                        
                        <!-- Reset Button -->
                        <div class="text-center">
                            <a href="index.php" class="inline-flex items-center space-x-2 text-dark-600 hover:text-primary transition-colors duration-300 font-medium">
                                <i class="fas fa-redo text-sm"></i>
                                <span>Pilih Ulang Kecamatan</span>
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 animate-fade-in">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-dark-800 mb-4">
                Fitur <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">Unggulan</span>
            </h2>
            <p class="text-lg text-dark-600 max-w-3xl mx-auto leading-relaxed">
                Platform lengkap untuk transparansi dan akuntabilitas pemerintahan desa
            </p>
        </div>
        
        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Transparansi Anggaran -->
            <div class="group animate-slide-up hover-lift">
                <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl p-8 h-full border border-primary/10 hover:border-primary/30 transition-all duration-300">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-primary to-primary-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-primary/25 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-chart-bar text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-accent rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-xl font-bold text-dark-800 mb-4 group-hover:text-primary transition-colors duration-300">
                        Transparansi Anggaran
                    </h3>
                    <p class="text-dark-600 leading-relaxed">
                        Akses informasi detail mengenai anggaran desa dari berbagai sumber pendanaan seperti Dana Desa, ADD, dan sumber lainnya dengan visualisasi yang mudah dipahami.
                    </p>
                </div>
            </div>
            
            <!-- Data Pembangunan -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.1s">
                <div class="bg-gradient-to-br from-accent/5 to-accent/10 rounded-2xl p-8 h-full border border-accent/10 hover:border-accent/30 transition-all duration-300">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-accent to-accent-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-accent/25 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-tools text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-xl font-bold text-dark-800 mb-4 group-hover:text-accent transition-colors duration-300">
                        Data Pembangunan
                    </h3>
                    <p class="text-dark-600 leading-relaxed">
                        Informasi lengkap tentang proyek pembangunan desa, lokasi pelaksanaan, timeline proyek, dan realisasi anggaran yang telah digunakan secara real-time.
                    </p>
                </div>
            </div>
            
            <!-- Evaluasi Masyarakat -->
            <div class="group animate-slide-up hover-lift" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-br from-dark-100 to-dark-200 rounded-2xl p-8 h-full border border-dark-200 hover:border-dark-400 transition-all duration-300">
                    <div class="relative mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-dark-600 to-dark-800 rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-dark-800/25 transition-all duration-300 group-hover:scale-110">
                            <i class="fas fa-comments text-white text-2xl"></i>
                        </div>
                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-primary rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 animate-pulse"></div>
                    </div>
                    <h3 class="text-xl font-bold text-dark-800 mb-4 group-hover:text-dark-800 transition-colors duration-300">
                        Evaluasi Masyarakat
                    </h3>
                    <p class="text-dark-600 leading-relaxed">
                        Platform interaktif untuk masyarakat menyampaikan aspirasi, laporan, dan evaluasi terhadap pengelolaan desa dengan sistem moderasi yang transparan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA section dihapus sesuai revisi klien -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<?php include "includes/footer.php"; ?>
