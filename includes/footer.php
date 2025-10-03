    </div> <!-- Close main content wrapper -->

    <!-- Modern Footer -->
    <footer class="bg-dark-800 text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0 bg-gradient-to-br from-primary via-accent to-primary opacity-20"></div>
        </div>
        
        <div class="relative z-10">
            <!-- Main Footer Content -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Brand Section -->
                    <div class="lg:col-span-2 animate-fade-in">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-gradient-to-br from-primary to-primary-600 p-3 rounded-xl shadow-lg">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                                    Transparansi Desa
                                </h3>
                                <p class="text-dark-300 text-sm">Sistem Informasi Transparansi</p>
                            </div>
                        </div>
                        <p class="text-dark-200 leading-relaxed mb-6">
                            Platform digital untuk meningkatkan transparansi anggaran dan pembangunan desa, 
                            mendukung akuntabilitas pemerintahan desa yang lebih baik.
                        </p>
                        
                        <!-- Social Links -->
                        <div class="flex space-x-4">
                            <a href="#" class="w-10 h-10 bg-primary/20 hover:bg-primary text-primary hover:text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-primary/20 hover:bg-primary text-primary hover:text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-primary/20 hover:bg-primary text-primary hover:text-white rounded-full flex items-center justify-center transition-all duration-300 hover:scale-110">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="animate-slide-up">
                        <h4 class="text-lg font-semibold mb-4 text-primary">Menu Utama</h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="index.php" class="text-dark-200 hover:text-primary transition-colors duration-300 flex items-center space-x-2 group">
                                    <i class="fas fa-home group-hover:text-primary transition-colors duration-300"></i>
                                    <span>Beranda</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.php" class="text-dark-200 hover:text-primary transition-colors duration-300 flex items-center space-x-2 group">
                                    <i class="fas fa-search group-hover:text-primary transition-colors duration-300"></i>
                                    <span>Cari Desa</span>
                                </a>
                            </li>
                            <li>
                                <a href="evaluasi.php" class="text-dark-200 hover:text-primary transition-colors duration-300 flex items-center space-x-2 group">
                                    <i class="fas fa-comments group-hover:text-accent transition-colors duration-300"></i>
                                    <span>Kirim Evaluasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="admin/login.php" class="text-dark-200 hover:text-accent transition-colors duration-300 flex items-center space-x-2 group">
                                    <i class="fas fa-user-shield group-hover:text-accent transition-colors duration-300"></i>
                                    <span>Login Admin</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Information -->
                    <div class="animate-slide-up" style="animation-delay: 0.2s">
                        <h4 class="text-lg font-semibold mb-4 text-accent">Informasi</h4>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="bg-primary/20 p-2 rounded-lg flex-shrink-0 mt-1">
                                    <i class="fas fa-info-circle text-primary text-sm"></i>
                                </div>
                                <p class="text-dark-200 text-sm leading-relaxed">
                                    Platform ini menyediakan akses terbuka terhadap informasi anggaran dan pembangunan desa.
                                </p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="bg-accent/20 p-2 rounded-lg flex-shrink-0 mt-1">
                                    <i class="fas fa-eye text-accent text-sm"></i>
                                </div>
                                <p class="text-dark-200 text-sm leading-relaxed">
                                    Semua data telah melalui verifikasi dan persetujuan dari pihak yang berwenang.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-dark-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-dark-300 text-sm text-center md:text-left">
                            <p>&copy; <?= date('Y') ?> Sistem Informasi Transparansi Desa. All rights reserved.</p>
                        </div>
                        <div class="flex items-center space-x-2 text-dark-300 text-sm">
                            <i class="fas fa-code text-primary"></i>
                            <span>Dikembangkan untuk Transparansi Pemerintahan Desa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Toggle Script -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        
        mobileMenuButton.addEventListener('click', function() {
            if (mobileMenu.classList.contains('opacity-0')) {
                mobileMenu.classList.remove('opacity-0', 'invisible', '-translate-y-full');
                mobileMenu.classList.add('opacity-100', 'visible', 'translate-y-0');
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
            } else {
                mobileMenu.classList.add('opacity-0', 'invisible', '-translate-y-full');
                mobileMenu.classList.remove('opacity-100', 'visible', 'translate-y-0');
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white/95');
                navbar.classList.remove('bg-transparent');
            } else {
                navbar.classList.remove('bg-white/95');
                navbar.classList.add('bg-transparent');
            }
        });

        // Smooth scrolling for anchor links
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
    </script>
</body>
</html>
