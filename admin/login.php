<?php
require_once __DIR__ . '/../includes/session.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD']=="POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // Cek password menggunakan password_verify untuk hash baru
            // atau MD5 untuk backward compatibility
            if (password_verify($password, $admin['password']) || 
                (strlen($admin['password']) == 32 && md5($password) == $admin['password'])) {
                
                $_SESSION['admin'] = $username;
                $_SESSION['admin_id'] = $admin['id_admin'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Password salah!";
            }
        } else {
            $error_message = "Username tidak ditemukan!";
        }
        $stmt->close();
    } else {
        $error_message = "Username dan password harus diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Transparansi Desa</title>
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
                        'shake': 'shake 0.82s cubic-bazier(0.36,0.07,0.19,0.97) both',
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
                        },
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
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
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4 relative overflow-x-hidden overflow-y-auto">
    <!-- Background Pattern -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-1/2 -left-1/2 w-full h-full bg-white/5 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-1/2 -right-1/2 w-full h-full bg-primary/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/4 left-1/3 w-96 h-96 bg-accent/5 rounded-full blur-3xl animate-pulse-slow"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md mx-auto animate-slide-up my-8">
        <!-- Logo Section -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-lg rounded-2xl flex items-center justify-center mx-auto mb-3 border border-white/30 hover-lift">
                <i class="fas fa-user-shield text-2xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">Administrator</h1>
            <p class="text-white/70 text-sm">Sistem Transparansi Desa</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl overflow-hidden hover-lift">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-primary/20 to-accent/20 backdrop-blur-lg p-6 text-center border-b border-white/10">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-sign-in-alt text-lg text-white"></i>
                </div>
                <h2 class="text-xl font-bold text-white mb-1">Login Administrator</h2>
                <p class="text-white/70 text-sm">Masuk ke dashboard admin</p>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-500/20 backdrop-blur-lg border border-red-500/30 rounded-xl p-4 mb-6 animate-shake">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="text-red-100 font-medium"><?= htmlspecialchars($error_message) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <form method="post" class="space-y-6">
                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-white font-semibold flex items-center space-x-2">
                            <i class="fas fa-user text-primary"></i>
                            <span>Username</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="username" 
                                   id="username" 
                                   class="w-full px-4 py-4 bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-300" 
                                   placeholder="Masukkan username Anda" 
                                   required 
                                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-user text-white/30"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-white font-semibold flex items-center space-x-2">
                            <i class="fas fa-lock text-accent"></i>
                            <span>Password</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-4 py-4 bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300" 
                                   placeholder="Masukkan password Anda" 
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" 
                                        onclick="togglePassword()" 
                                        class="text-white/30 hover:text-white/60 transition-colors duration-200">
                                    <i id="password-toggle" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary to-accent text-white font-bold py-4 px-6 rounded-xl hover:shadow-lg hover:shadow-primary/25 transition-all duration-300 hover:scale-105 flex items-center justify-center space-x-2 group">
                        <i class="fas fa-sign-in-alt group-hover:rotate-12 transition-transform duration-300"></i>
                        <span>Masuk ke Dashboard</span>
                    </button>
                </form>
                
                <!-- Back to Home -->
                <div class="text-center mt-6 pt-4 border-t border-white/10">
                    <a href="../index.php" 
                       class="inline-flex items-center space-x-2 text-white/70 hover:text-white transition-colors duration-300 font-medium group text-sm">
                        <i class="fas fa-home group-hover:-translate-x-1 transition-transform duration-300"></i>
                        <span>Kembali ke Halaman Utama</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-white/60 text-xs">
                © <?= date('Y') ?> Sistem Transparansi Desa. Semua hak dilindungi.
            </p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            }
        }

        // Add loading state to login button
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <i class="fas fa-spinner fa-spin"></i>
                <span>Memproses...</span>
            `;
            submitBtn.disabled = true;
            
            // Re-enable button after 3 seconds if form submission fails
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Add focus animation to inputs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('scale-105');
            });
        });
    </script>
</body>
</html>
