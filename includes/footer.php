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
                        
                        <!-- Social Links dihapus sesuai revisi klien -->
                    </div>
                    
                    <!-- Menu Utama & Informasi dihapus sesuai revisi klien -->
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-dark-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-dark-300 text-sm text-center md:text-left">
                            <p>&copy; <?= date('Y') ?> Sistem Informasi Transparansi Desa. All rights reserved.</p>
                        </div>
                        <!-- Tagline pengembang dihapus sesuai revisi klien -->
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
