<?php
include "../includes/db.php";
include "../includes/auth.php";
include "../includes/flash.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM pembangunan WHERE id_pembangunan = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        flash_set('success', 'Berhasil', 'Data pembangunan berhasil dihapus.');
    } else {
        flash_set('error', 'Gagal', 'Gagal menghapus data pembangunan: ' . $err);
    }
} else {
    flash_set('warning', 'Aksi Tidak Valid', 'Parameter id tidak ditemukan.');
}

header("Location: dashboard.php#pembangunan");
exit;
?>
