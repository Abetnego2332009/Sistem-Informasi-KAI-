<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id            = intval($_POST['id']);
    $kategori      = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $ikon_emoji    = mysqli_real_escape_string($koneksi, $_POST['ikon_emoji']);
    $persen_diskon = intval($_POST['persen_diskon']);
    $syarat        = mysqli_real_escape_string($koneksi, $_POST['syarat']);
    $keterangan    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $is_active     = isset($_POST['is_active']) ? 1 : 0;

    mysqli_query($koneksi, "UPDATE tarif_khusus SET
        kategori      = '$kategori',
        ikon_emoji    = '$ikon_emoji',
        persen_diskon = $persen_diskon,
        syarat        = '$syarat',
        keterangan    = '$keterangan',
        is_active     = $is_active
        WHERE id = $id
    ");

    if (mysqli_affected_rows($koneksi) >= 0) {
        echo "<script>
                alert('Data berhasil diupdate!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal diupdate!');
                window.location.href = 'edit.php?id=$id';
              </script>";
    }
}

// ============================================================
// PROSES TAMBAH
// ============================================================
if (isset($_POST['tambah'])) {

    $kategori      = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $ikon_emoji    = mysqli_real_escape_string($koneksi, $_POST['ikon_emoji']);
    $persen_diskon = intval($_POST['persen_diskon']);
    $syarat        = mysqli_real_escape_string($koneksi, $_POST['syarat']);
    $keterangan    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $is_active     = isset($_POST['is_active']) ? 1 : 0;
    $admin_id      = $_SESSION['admin_id'] ?? 1;

    mysqli_query($koneksi, "INSERT INTO tarif_khusus
        (kategori, ikon_emoji, persen_diskon, syarat, keterangan, is_active, admin_id)
        VALUES
        ('$kategori', '$ikon_emoji', $persen_diskon, '$syarat', '$keterangan', $is_active, $admin_id)
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah_tarif_khusus.php';
              </script>";
    }
}

// ============================================================
// PROSES HAPUS
// ============================================================
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_query($koneksi, "DELETE FROM tarif_khusus WHERE id = $id");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil dihapus!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus!');
                window.location.href = 'index.php';
              </script>";
    }
}
