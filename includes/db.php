<?php
// Koneksi database untuk lokal & Vercel (Aiven)
// Menggunakan environment variables bila tersedia

$host = getenv('DB_HOST') ?: 'localhost';
$port = (int)(getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'transparansi_desa';

$conn = mysqli_init();
// Timeout koneksi agar cepat gagal bila unreachable
if (defined('MYSQLI_OPT_CONNECT_TIMEOUT')) {
    mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
}

// Opsional: paksa SSL bila diperlukan (Aiven umumnya TLS by default)
// Set DB_SSL_MODE=REQUIRED di Vercel bila ingin memaksa SSL
$sslMode = strtoupper(getenv('DB_SSL_MODE') ?: 'DISABLED');
if ($sslMode !== 'DISABLED') {
    // Tanpa CA file (Aiven biasanya tetap menerima TLS). Untuk verifikasi ketat, sediakan DB_SSL_CA.
    $ca = getenv('DB_SSL_CA');
    if ($ca) {
        // Jika CA dikirim via ENV, buat file sementara
        $tmpCa = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'aiven-ca.pem';
        if (!file_exists($tmpCa)) {
            @file_put_contents($tmpCa, $ca);
        }
        mysqli_ssl_set($conn, null, null, $tmpCa, null, null);
    } else {
        mysqli_ssl_set($conn, null, null, null, null, null);
    }
}

if (!@mysqli_real_connect($conn, $host, $user, $pass, $db, $port)) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

@mysqli_set_charset($conn, 'utf8mb4');
?>
