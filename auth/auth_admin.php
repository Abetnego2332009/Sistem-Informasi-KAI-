<?php
// hanya admin
require_once __DIR__ . '/auth_login.php'; // ini sudah session_start()


// Izinkan admin DAN superadmin
if (!in_array($_SESSION['role'], ['admin', 'superadmin'])) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location = '" . $base_url . "/login.php';
          </script>";
    exit;
}
?>
