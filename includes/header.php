<?php require_once __DIR__ . '/session.php'; ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?>Sistem Informasi Transparansi Desa</title>
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
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    backgroundSize: {
                        '300%': '300%',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(-45deg, #08D9D6, #252A34, #FF2E63, #08D9D6);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        
        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light text-dark-800 overflow-x-hidden">
    <!-- Modern Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect animate-slide-down" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <a href="index.php" class="flex items-center space-x-2 group">
                            <div class="bg-gradient-to-br from-primary to-primary-600 p-2 rounded-xl shadow-lg group-hover:shadow-primary/25 transition-all duration-300 animate-float">
                                <i class="fas fa-shield-alt text-white text-lg"></i>
                            </div>
                            <span class="text-xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                                Transparansi Desa
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="index.php" class="text-dark-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-primary/10 flex items-center space-x-1">
                            <i class="fas fa-home"></i>
                            <span>Beranda</span>
                        </a>
                        
                        <!-- Dropdown Data Desa -->
                        <div class="relative group">
                            <button id="dropdown-data-btn" class="text-dark-700 hover:text-primary px-3 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:bg-primary/10 flex items-center space-x-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Data Desa</span>
                                <i class="fas fa-chevron-down text-xs ml-1 group-hover:rotate-180 transition-transform duration-300"></i>
                            </button>
                            <div id="dropdown-data-menu" class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 border border-primary/10 z-50">
                                <div class="py-2">
                                    <a href="index.php#cari-desa" class="block px-4 py-2 text-sm text-dark-700 hover:bg-primary/5 hover:text-primary transition-all duration-200 flex items-center space-x-2">
                                        <i class="fas fa-search text-primary"></i>
                                        <span>Cari Desa</span>
                                    </a>
                                    <hr class="my-1 border-primary/10">
                                    <a href="evaluasi.php" class="block px-4 py-2 text-sm text-dark-700 hover:bg-primary/5 hover:text-primary transition-all duration-200 flex items-center space-x-2">
                                        <i class="fas fa-comments text-accent"></i>
                                        <span>Kirim Evaluasi</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <a href="<?= isset($_SESSION['admin']) ? 'admin/dashboard.php' : 'admin/login.php' ?>" class="bg-gradient-to-r from-accent to-accent-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 hover:shadow-lg hover:shadow-accent/25 hover:scale-105 flex items-center space-x-1">
                            <i class="fas fa-user-shield"></i>
                            <span><?= isset($_SESSION['admin']) ? 'Dashboard Admin' : 'Admin' ?></span>
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-dark-700 hover:text-primary p-2 rounded-lg hover:bg-primary/10 transition-all duration-300" id="mobile-menu-button" onclick="window.__toggleMobileMenu && window.__toggleMobileMenu(event)">
                        <i class="fas fa-bars text-lg" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden fixed top-16 left-0 right-0 z-40 opacity-0 invisible transform -translate-y-full transition-all duration-300 pointer-events-none" id="mobile-menu">
            <div class="mx-4 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="py-2">
                    <a href="index.php" class="text-dark-700 hover:text-primary block px-4 py-3 text-base font-medium transition-all duration-300 hover:bg-primary/10 border-b border-gray-100 flex items-center">
                        <i class="fas fa-home mr-3 text-primary"></i>Beranda
                    </a>

                    <!-- Collapsible: Data Desa -->
                    <button type="button" id="mobile-data-btn" class="w-full flex items-center justify-between px-4 py-3 text-dark-700 hover:text-primary transition-all duration-300 hover:bg-primary/10 border-b border-gray-100">
                        <span class="flex items-center"><i class="fas fa-map-marker-alt mr-3 text-primary"></i>Data Desa</span>
                        <i class="fas fa-chevron-down text-xs" id="mobile-data-chevron"></i>
                    </button>
                    <div id="mobile-data-menu" class="hidden">
                        <a href="index.php#cari-desa" class="block pl-12 pr-4 py-3 text-dark-700 hover:text-primary transition-colors duration-200 hover:bg-primary/5">
                            <i class="fas fa-search mr-2 text-primary"></i>Cari Desa
                        </a>
                        <a href="evaluasi.php" class="block pl-12 pr-4 py-3 text-dark-700 hover:text-primary transition-colors duration-200 hover:bg-primary/5 border-b border-gray-100">
                            <i class="fas fa-comments mr-2 text-accent"></i>Kirim Evaluasi
                        </a>
                    </div>

                    <a href="<?= isset($_SESSION['admin']) ? 'admin/dashboard.php' : 'admin/login.php' ?>" class="bg-gradient-to-r from-accent to-accent-600 text-white block px-4 py-3 text-base font-medium transition-all duration-300 hover:shadow-lg flex items-center rounded-b-xl">
                        <i class="fas fa-user-shield mr-3"></i><?= isset($_SESSION['admin']) ? 'Dashboard Admin' : 'Admin' ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div class="md:hidden fixed inset-0 bg-black bg-opacity-25 z-30 opacity-0 invisible transition-all duration-300 pointer-events-none" id="mobile-overlay"></div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16"> <!-- Add padding-top to account for fixed navbar -->

    <!-- Mobile Menu JavaScript -->
    <script>
        // Fallback: make toggle available immediately for inline onclick
        window.__toggleMobileMenu = function(e){
            try {
                if (e) e.stopPropagation();
                const mobileMenu = document.getElementById('mobile-menu');
                const mobileOverlay = document.getElementById('mobile-overlay');
                const menuIcon = document.getElementById('menu-icon');
                if (!mobileMenu || !mobileOverlay) return;
                const isHidden = mobileMenu.classList.contains('opacity-0') || mobileMenu.classList.contains('invisible');
                if (isHidden) {
                    mobileMenu.classList.remove('opacity-0','invisible','-translate-y-full','pointer-events-none');
                    mobileMenu.classList.add('opacity-100','visible','translate-y-0','pointer-events-auto');
                    mobileOverlay.classList.remove('opacity-0','invisible','pointer-events-none');
                    mobileOverlay.classList.add('opacity-100','visible','pointer-events-auto');
                    if (menuIcon){ menuIcon.classList.remove('fa-bars'); menuIcon.classList.add('fa-times'); }
                    document.body.style.overflow = 'hidden';
                } else {
                    mobileMenu.classList.remove('opacity-100','visible','translate-y-0','pointer-events-auto');
                    mobileMenu.classList.add('opacity-0','invisible','-translate-y-full','pointer-events-none');
                    mobileOverlay.classList.remove('opacity-100','visible','pointer-events-auto');
                    mobileOverlay.classList.add('opacity-0','invisible','pointer-events-none');
                    if (menuIcon){ menuIcon.classList.remove('fa-times'); menuIcon.classList.add('fa-bars'); }
                    document.body.style.overflow = '';
                }
            } catch(_){}
        };

        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileOverlay = document.getElementById('mobile-overlay');
            const menuIcon = document.getElementById('menu-icon');
            const dropdownBtn = document.getElementById('dropdown-data-btn');
            const dropdownMenu = document.getElementById('dropdown-data-menu');

            // Function to show mobile menu
            function showMobileMenu() {
                mobileMenu.classList.remove('opacity-0', 'invisible', '-translate-y-full');
                mobileMenu.classList.remove('pointer-events-none');
                mobileMenu.classList.add('opacity-100', 'visible', 'translate-y-0', 'pointer-events-auto');
                mobileOverlay.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                mobileOverlay.classList.add('opacity-100', 'visible', 'pointer-events-auto');
                
                if (menuIcon) {
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                }
                
                // Prevent body scrolling
                document.body.style.overflow = 'hidden';
            }

            // Function to hide mobile menu
            function hideMobileMenu() {
                mobileMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                mobileMenu.classList.add('opacity-0', 'invisible', '-translate-y-full', 'pointer-events-none');
                mobileOverlay.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
                mobileOverlay.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                
                if (menuIcon) {
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
                
                // Restore body scrolling
                document.body.style.overflow = '';
            }

            // Global toggle for inline onclick fallback
            window.__toggleMobileMenu = function(e){
                if (e) e.stopPropagation();
                if (mobileMenu.classList.contains('opacity-0') || mobileMenu.classList.contains('invisible')) {
                    showMobileMenu();
                } else {
                    hideMobileMenu();
                }
            };

            // Toggle menu on button click
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', window.__toggleMobileMenu);
            }

            // Close menu when clicking overlay
            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', function() {
                    hideMobileMenu();
                });
            }

            // Close menu when clicking menu links
            if (mobileMenu) {
                const mobileMenuLinks = mobileMenu.querySelectorAll('a');
                mobileMenuLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        hideMobileMenu();
                    });
                });
            }

            // Close menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) { // md breakpoint
                    hideMobileMenu();
                }
            });

            // Close on escape
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') hideMobileMenu();
            });

            // Desktop dropdown (click support for touch devices)
            if (dropdownBtn && dropdownMenu) {
                function hideDropdown() {
                    dropdownMenu.classList.add('opacity-0', 'invisible');
                    dropdownMenu.classList.remove('opacity-100', 'visible');
                }
                function showDropdown() {
                    dropdownMenu.classList.remove('opacity-0', 'invisible');
                    dropdownMenu.classList.add('opacity-100', 'visible');
                }
                // Toggle on click
                dropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (dropdownMenu.classList.contains('invisible')) {
                        showDropdown();
                    } else {
                        hideDropdown();
                    }
                });
                // Click outside closes
                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        hideDropdown();
                    }
                });
                // Hide on resize to mobile (since desktop nav hidden)
                window.addEventListener('resize', function() {
                    if (window.innerWidth < 768) hideDropdown();
                });
            }

            // Navbar scroll effect
            const navbar = document.getElementById('navbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('bg-white/95', 'shadow-lg');
                        navbar.classList.remove('glass-effect');
                    } else {
                        navbar.classList.remove('bg-white/95', 'shadow-lg');
                        navbar.classList.add('glass-effect');
                    }
                });
            }

            // Mobile: collapsible Data Desa submenu
            const mobileDataBtn = document.getElementById('mobile-data-btn');
            const mobileDataMenu = document.getElementById('mobile-data-menu');
            const mobileChevron = document.getElementById('mobile-data-chevron');
            if (mobileDataBtn && mobileDataMenu) {
                mobileDataBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileDataMenu.classList.toggle('hidden');
                    if (mobileChevron) mobileChevron.classList.toggle('rotate-180');
                });
            }
        });
    </script>
