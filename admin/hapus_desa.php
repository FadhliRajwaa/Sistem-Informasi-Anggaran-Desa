<?php
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/flash.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Hapus desa dengan prepared statement
    $stmt = $conn->prepare("DELETE FROM desa WHERE id_desa = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        flash_set('success', 'Berhasil', 'Data desa berhasil dihapus.');
    } else {
        flash_set('error', 'Gagal', 'Gagal menghapus data desa: ' . $err);
    }
} else {
    flash_set('warning', 'Aksi Tidak Valid', 'Parameter id tidak ditemukan.');
}

header("Location: dashboard.php#desa");
exit;
?>
