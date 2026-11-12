<?php

require_once __DIR__ . '/../koneksi.php';

// Validasi ID
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php?error=id_invalid');
    exit;
}

// Cek apakah data ada
$cek = mysqli_query($koneksi, "SELECT id FROM pengumuman WHERE id = $id LIMIT null");

if (mysqli_num_rows($cek) === 0) {
    header('Location: index.php?error=not_found');
    exit;
}

// Hapus data
$sql = "DELETE FROM pengumuman WHERE id = $id";

if (mysqli_query($koneksi, $sql)) {
    header('Location: index.php?sukses=3');
} else {
    header('Location: index.php?error=delete_failed');
}

exit;
