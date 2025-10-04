<?php
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/flash.php";

$flash = function_exists('flash_get') ? flash_get() : [];

// Statistik dashboard
$total_desa = $conn->query("SELECT COUNT(*) as total FROM desa")->fetch_assoc()['total'];
$total_anggaran = $conn->query("SELECT SUM(jumlah) as total FROM anggaran")->fetch_assoc()['total'];
$total_pembangunan = $conn->query("SELECT COUNT(*) as total FROM pembangunan")->fetch_assoc()['total'];
$total_evaluasi_pending = $conn->query("SELECT COUNT(*) as total FROM evaluasi WHERE status='pending'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Transparansi Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#08D9D6',
                        dark: '#252A34', 
                        accent: '#FF2E63',
                        light: '#EAEAEA',
                        'primary-50': '#f0fdfc',
                        'primary-100': '#ccfbf9',
                        'primary-200': '#99f6f3',
                        'primary-300': '#5eede9',
                        'primary-400': '#2dd9d6',
                        'primary-500': '#08D9D6',
                        'primary-600': '#06b0ad',
                        'primary-700': '#0a8c8a',
                        'primary-800': '#0f6f6e',
                        'primary-900': '#135b5b',
                        'dark-50': '#f8f9fa',
                        'dark-100': '#e9ecef',
                        'dark-200': '#dee2e6',
                        'dark-300': '#ced4da',
                        'dark-400': '#adb5bd',
                        'dark-500': '#6c757d',
                        'dark-600': '#495057',
                        'dark-700': '#343a40',
                        'dark-800': '#252A34',
                        'dark-900': '#1a1d23',
                        'accent-50': '#fef2f4',
                        'accent-100': '#fde2e7',
                        'accent-200': '#fbcad4',
                        'accent-300': '#f8a5b6',
                        'accent-400': '#f37394',
                        'accent-500': '#ec4371',
                        'accent-600': '#FF2E63',
                        'accent-700': '#d11149',
                        'accent-800': '#b10e3e',
                        'accent-900': '#961039',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'slide-down': 'slideDown 0.6s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                        'gradient': 'gradient 6s ease infinite',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-30px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        gradient: {
                            '0%, 100%': { backgroundSize: '200% 200%', backgroundPosition: 'left center' },
                            '50%': { backgroundSize: '200% 200%', backgroundPosition: 'right center' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translatey(0px)' },
                            '50%': { transform: 'translatey(-20px)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #252A34 0%, #08D9D6 50%, #FF2E63 100%);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-btn" class="bg-white/90 backdrop-blur-lg p-3 rounded-xl shadow-lg border border-gray-200">
            <i class="fas fa-bars text-dark-800"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 gradient-bg transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg">Admin Panel</h2>
                        <p class="text-white/60 text-sm">Dashboard Kontrol</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-6 space-y-2">
                <a href="#dashboard" onclick="showTab('dashboard')" id="nav-dashboard" class="nav-item active flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#desa" onclick="showTab('desa')" id="nav-desa" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Kelola Desa</span>
                </a>
                <a href="#anggaran" onclick="showTab('anggaran')" id="nav-anggaran" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Kelola Anggaran</span>
                </a>
                <a href="#pembangunan" onclick="showTab('pembangunan')" id="nav-pembangunan" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-hammer"></i>
                    <span>Kelola Pembangunan</span>
                </a>
                <a href="#evaluasi" onclick="showTab('evaluasi')" id="nav-evaluasi" class="nav-item flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-comments"></i>
                    <span>Kelola Evaluasi</span>
                    <?php if($total_evaluasi_pending > 0): ?>
                        <span class="bg-accent text-white text-xs px-2 py-1 rounded-full"><?= $total_evaluasi_pending ?></span>
                    <?php endif; ?>
                </a>
                
                <div class="border-t border-white/10 my-4"></div>
                
                <!-- Link Import Data CSV dihapus sesuai revisi klien -->
                <a href="../index.php" target="_blank" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-all duration-200">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat Situs</span>
                </a>
                <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-red-200 hover:text-white hover:bg-red-500/20 transition-all duration-200">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-4 py-3 md:px-6 md:py-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-primary to-accent rounded-xl flex items-center justify-center">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-dark-800">Dashboard Administrator</h1>
                            <p class="text-dark-500 text-sm md:text-base">Kelola sistem transparansi desa</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:block bg-gradient-to-r from-primary/10 to-accent/10 px-3 py-1.5 md:px-4 md:py-2 rounded-xl border border-primary/20">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-primary"></i>
                                <span class="text-dark-700 font-medium text-sm md:text-base">Selamat datang, <?= htmlspecialchars($_SESSION['admin']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <?php if (!empty($flash)): ?>
        <!-- Flash Modal -->
        <div id="flash-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-[90%] p-6 border" role="dialog" aria-modal="true">
                <?php
                    $t = $flash['type'] ?? 'info';
                    $title = htmlspecialchars($flash['title'] ?? 'Info');
                    $msg = htmlspecialchars($flash['message'] ?? '');
                    $ico = $t==='success' ? 'fa-check-circle text-green-600' : ($t==='error' ? 'fa-times-circle text-red-600' : ($t==='warning' ? 'fa-exclamation-triangle text-yellow-600' : 'fa-info-circle text-blue-600'));
                    $bar = $t==='success' ? 'bg-green-500' : ($t==='error' ? 'bg-red-500' : ($t==='warning' ? 'bg-yellow-500' : 'bg-blue-500'));
                ?>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-50 border">
                        <i class="fas <?= $ico ?> text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-dark-800"><?= $title ?></h3>
                </div>
                <p class="text-dark-600 mb-4"><?= $msg ?></p>
                <div class="flex justify-end">
                    <button id="flash-close" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white <?= $bar ?> hover:opacity-90">
                        <i class="fas fa-check"></i>
                        <span>Tutup</span>
                    </button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                const btn = document.getElementById('flash-close');
                const m = document.getElementById('flash-modal');
                if(btn && m){ btn.addEventListener('click', ()=> m.remove()); }
            });
        </script>
        <?php endif; ?>

        <!-- Content -->
        <main class="p-4 md:p-6">
            <!-- Dashboard Tab -->
            <div id="content-dashboard" class="tab-content-section">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Desa -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-dark-500 text-sm font-medium">Total Desa</p>
                                <p class="text-2xl md:text-3xl font-bold text-primary"><?= number_format($total_desa) ?></p>
                            </div>
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-primary to-primary-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-500 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>
                                Data terdaftar
                            </span>
                        </div>
                    </div>

                    <!-- Total Anggaran -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-dark-500 text-sm font-medium">Total Anggaran</p>
                                <p class="text-2xl md:text-3xl font-bold text-accent">Rp <?= number_format(($total_anggaran ?: 0) / 1000000000, 1) ?>M</p>
                            </div>
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-accent to-accent-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-blue-500 flex items-center">
                                <i class="fas fa-chart-line mr-1"></i>
                                Dalam milyar
                            </span>
                        </div>
                    </div>

                    <!-- Total Pembangunan -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-dark-500 text-sm font-medium">Proyek Pembangunan</p>
                                <p class="text-2xl md:text-3xl font-bold text-dark-800"><?= number_format($total_pembangunan) ?></p>
                            </div>
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-dark-600 to-dark-800 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-hammer text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-purple-500 flex items-center">
                                <i class="fas fa-tools mr-1"></i>
                                Proyek aktif
                            </span>
                        </div>
                    </div>

                    <!-- Evaluasi Pending -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-dark-500 text-sm font-medium">Evaluasi Menunggu</p>
                                <p class="text-2xl md:text-3xl font-bold text-orange-500"><?= number_format($total_evaluasi_pending) ?></p>
                            </div>
                            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-clock text-white text-2xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-orange-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Perlu review
                            </span>
                        </div>
                    </div>
                
                </div>
                <!-- Aksi Cepat dihapus sesuai revisi klien -->
            </div>

            <!-- Other Tab Contents -->
            <div id="content-desa" class="tab-content-section hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary to-primary-600 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Kelola Data Desa
                        </h2>
                        <p class="text-primary-100 mt-2">Tambah, edit, dan hapus data desa</p>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-dark-800">Data Desa</h3>
                            <a href="tambah_desa.php" class="inline-flex items-center space-x-2 bg-primary text-white px-4 py-2 rounded-xl text-sm hover:opacity-90">
                                <i class="fas fa-plus"></i><span>Tambah Desa</span>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden sm:table-cell">No</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kecamatan</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Nama Desa</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kepala Desa</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php 
                                    $stmt = $conn->prepare("SELECT id_desa, kecamatan, nama_desa, kepala_desa FROM desa ORDER BY kecamatan, nama_desa LIMIT 100");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $no = 1;
                                    if ($result && $result->num_rows > 0):
                                        while($d = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden sm:table-cell"><?= $no++ ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($d['kecamatan']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($d['nama_desa']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-500 hidden md:table-cell"><?= htmlspecialchars($d['kepala_desa'] ?: 'Belum diisi') ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm">
                                                    <div class="flex items-center md:flex-nowrap flex-wrap gap-2 md:gap-0 md:space-x-2">
                                                        <a href="edit_desa.php?id=<?= $d['id_desa'] ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-primary text-white text-xs"><i class="fas fa-edit mr-1"></i>Edit</a>
                                                        <a href="hapus_desa.php?id=<?= $d['id_desa'] ?>" onclick="return confirm('Yakin hapus desa ini?')" class="inline-flex items-center px-3 py-1 rounded-lg js-loading bg-red-500 text-white text-xs"><i class="fas fa-trash mr-1"></i>Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; $stmt->close(); else: ?>
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada data desa.</td>
                                            </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-anggaran" class="tab-content-section hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-accent to-accent-600 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-money-bill-wave mr-3"></i>
                            Kelola Data Anggaran
                        </h2>
                        <p class="text-accent-100 mt-2">Input dan kelola anggaran desa</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-dark-800">Data Anggaran</h3>
                            <a href="tambah_anggaran.php" class="inline-flex items-center space-x-2 bg-accent text-white px-4 py-2 rounded-xl text-sm hover:opacity-90">
                                <i class="fas fa-plus"></i><span>Tambah Anggaran</span>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Tahun</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kecamatan</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Desa</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Jenis Anggaran</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php 
                                    $stmt = $conn->prepare("SELECT a.id_anggaran, a.tahun, a.jenis_anggaran, a.jumlah, d.nama_desa, d.kecamatan FROM anggaran a JOIN desa d ON a.id_desa=d.id_desa ORDER BY a.tahun DESC, d.kecamatan, d.nama_desa LIMIT 100");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result && $result->num_rows > 0):
                                        while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($row['tahun']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($row['kecamatan']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($row['nama_desa']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($row['jenis_anggaran']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700">Rp <?= number_format($row['jumlah']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm">
                                                    <div class="flex items-center md:flex-nowrap flex-wrap gap-2 md:gap-0 md:space-x-2">
                                                        <a href="edit_anggaran.php?id=<?= $row['id_anggaran'] ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-primary text-white text-xs"><i class="fas fa-edit mr-1"></i>Edit</a>
                                                        <a href="hapus_anggaran.php?id=<?= $row['id_anggaran'] ?>" onclick="return confirm('Yakin hapus anggaran ini?')" class="inline-flex items-center px-3 py-1 rounded-lg js-loading bg-red-500 text-white text-xs"><i class="fas fa-trash mr-1"></i>Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; $stmt->close(); else: ?>
                                            <tr>
                                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada data anggaran.</td>
                                            </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-pembangunan" class="tab-content-section hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-dark-600 to-dark-800 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-hammer mr-3"></i>
                            Kelola Data Pembangunan
                        </h2>
                        <p class="text-gray-300 mt-2">Input dan monitor proyek pembangunan</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-dark-800">Data Pembangunan</h3>
                            <a href="tambah_pembangunan.php" class="inline-flex items-center space-x-2 bg-dark-700 text-white px-3 py-1.5 md:px-4 md:py-2 rounded-lg md:rounded-xl text-xs md:text-sm hover:opacity-90 shrink-0">
                                <i class="fas fa-plus"></i><span>Tambah Pembangunan</span>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Tahun</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kecamatan</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Desa</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Kegiatan</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Lokasi</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Realisasi</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php 
                                    $stmt = $conn->prepare("SELECT p.id_pembangunan, p.tahun, p.kegiatan, p.lokasi, p.realisasi, d.nama_desa, d.kecamatan FROM pembangunan p JOIN desa d ON p.id_desa=d.id_desa ORDER BY p.tahun DESC, d.kecamatan, d.nama_desa LIMIT 100");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result && $result->num_rows > 0):
                                        while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($row['tahun']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($row['kecamatan']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($row['nama_desa']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($row['kegiatan']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell whitespace-normal break-words"><?= htmlspecialchars($row['lokasi']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700">Rp <?= number_format($row['realisasi']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm">
                                                    <div class="flex items-center md:flex-nowrap flex-wrap gap-2 md:gap-0 md:space-x-2">
                                                        <a href="edit_pembangunan.php?id=<?= $row['id_pembangunan'] ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-primary text-white text-xs"><i class="fas fa-edit mr-1"></i>Edit</a>
                                                        <a href="hapus_pembangunan.php?id=<?= $row['id_pembangunan'] ?>" onclick="return confirm('Yakin hapus data pembangunan ini?')" class="inline-flex items-center px-3 py-1 rounded-lg js-loading bg-red-500 text-white text-xs"><i class="fas fa-trash mr-1"></i>Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; $stmt->close(); else: ?>
                                            <tr>
                                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data pembangunan.</td>
                                            </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="content-evaluasi" class="tab-content-section hidden">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-comments mr-3"></i>
                            Kelola Evaluasi Masyarakat
                            <?php if($total_evaluasi_pending > 0): ?>
                                <span class="bg-white text-orange-600 text-sm px-3 py-1 rounded-full ml-3"><?= $total_evaluasi_pending ?> Menunggu</span>
                            <?php endif; ?>
                        </h2>
                        <p class="text-orange-100 mt-2">Review dan kelola feedback masyarakat</p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-dark-800">Data Evaluasi</h3>
                            <a href="tambah_evaluasi.php" class="inline-flex items-center space-x-2 bg-orange-500 text-white px-4 py-2 rounded-xl text-sm hover:opacity-90">
                                <i class="fas fa-plus"></i><span>Tambah Evaluasi</span>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kecamatan</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Desa</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Pelapor</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase hidden md:table-cell">Kategori</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Status</th>
                                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-[11px] md:text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php 
                                    $stmt = $conn->prepare("SELECT e.id_evaluasi, e.tanggal, e.nama, e.kategori, e.status, d.nama_desa, d.kecamatan FROM evaluasi e JOIN desa d ON e.id_desa=d.id_desa ORDER BY e.tanggal DESC LIMIT 100");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    if ($result && $result->num_rows > 0):
                                        while($row = $result->fetch_assoc()): 
                                            $status = $row['status'] ?? 'pending';
                                            $badge = $status==='approved' ? 'bg-green-100 text-green-700' : ($status==='rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700');
                                        ?>
                                            <tr>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700"><?= date('d/m/Y H:i', strtotime($row['tanggal'])) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell"><?= htmlspecialchars($row['kecamatan']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 whitespace-normal break-words"><?= htmlspecialchars($row['nama_desa']) ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell whitespace-normal break-words"><?= htmlspecialchars($row['nama'] ?: 'Anonim') ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm text-gray-700 hidden md:table-cell whitespace-normal break-words"><?= htmlspecialchars($row['kategori'] ?: 'Umum') ?></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm"><span class="px-2 py-1 rounded-lg text-xs <?= $badge ?>"><?= ucfirst($status) ?></span></td>
                                                <td class="px-3 py-2 md:px-4 md:py-3 text-xs md:text-sm">
                                                    <div class="flex items-center md:flex-nowrap flex-wrap gap-2 md:gap-0 md:space-x-2">
                                                        <a href="edit_evaluasi.php?id=<?= $row['id_evaluasi'] ?>" class="inline-flex items-center px-3 py-1 rounded-lg bg-primary text-white text-xs"><i class="fas fa-edit mr-1"></i>Edit</a>
                                                        <a href="hapus_evaluasi.php?id=<?= $row['id_evaluasi'] ?>" onclick="return confirm('Yakin hapus evaluasi ini?')" class="inline-flex items-center px-3 py-1 rounded-lg js-loading bg-red-500 text-white text-xs"><i class="fas fa-trash mr-1"></i>Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; $stmt->close(); else: ?>
                                            <tr>
                                                <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data evaluasi.</td>
                                            </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Overlay for mobile menu -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <!-- JavaScript -->
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            const hidden = sidebarOverlay.classList.toggle('hidden');
            // Lock body scroll when sidebar is open on mobile
            document.body.style.overflow = hidden ? '' : 'hidden';
        });

        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        });

        // Close with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Reset on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Tab functionality (global function)
        window.showTab = function(tabName) {
            // Hide all tab content sections
            const tabSections = document.querySelectorAll('.tab-content-section');
            tabSections.forEach(section => {
                section.classList.add('hidden');
            });

            // Remove active class from all nav items
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.classList.remove('active');
                item.classList.remove('bg-white/20', 'text-white');
                item.classList.add('text-white/80');
            });

            // Show selected tab content
            const selectedTab = document.getElementById(`content-${tabName}`);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }

            // Add active class to selected nav item
            const selectedNav = document.getElementById(`nav-${tabName}`);
            if (selectedNav) {
                selectedNav.classList.add('active');
                selectedNav.classList.add('bg-white/20', 'text-white');
                selectedNav.classList.remove('text-white/80');
            }

            // Close mobile menu after selection
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            if (window.innerWidth < 1024) {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                // Restore body scroll so konten bisa digulir ke bawah
                document.body.style.overflow = '';
            }
        }

        // Initialize dashboard tab (respect URL hash)
        document.addEventListener('DOMContentLoaded', function() {
            const initial = (location.hash || '#dashboard').replace('#','');
            window.showTab(initial);
        });

        // Ensure sidebar nav clicks always trigger tab switch
        document.querySelectorAll('.nav-item').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                const tab = this.id?.replace('nav-','') || 'dashboard';
                window.showTab(tab);
                // Update hash for deep-linking
                history.replaceState(null, '', `#${tab}`);
            });
        });

        // Add smooth scrolling (only for links with .scroll-link to avoid interfering with nav tabs)
        document.querySelectorAll('a.scroll-link[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
<?php include "../includes/ui.php"; ?>
</body>
</html>

