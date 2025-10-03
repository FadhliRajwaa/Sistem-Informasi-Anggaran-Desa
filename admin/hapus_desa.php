<?php
include "../includes/db.php";
include "../includes/auth.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Hapus desa dengan prepared statement
    $stmt = $conn->prepare("DELETE FROM desa WHERE id_desa = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: dashboard.php");
exit;
?>
