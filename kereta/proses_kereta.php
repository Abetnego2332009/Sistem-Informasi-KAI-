<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id               = intval($_POST['id']);
    $nomor_ka             = mysqli_real_escape_string($koneksi, $_POST['nomor_ka']);
    $nama_ka              = mysqli_real_escape_string($koneksi, $_POST['nama_ka']);
    $jenis     = mysqli_real_escape_string($koneksi, $_POST['jenis']);
    

    mysqli_query($koneksi, "UPDATE kereta SET
        nomor_ka           = '$nomor_ka',
        nama_ka            = '$nama_ka',
        jenis              = '$jenis'
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

    
    $id               = intval($_POST['id']);
    $nomor_ka             = mysqli_real_escape_string($koneksi, $_POST['nomor_ka']);
    $nama_ka              = mysqli_real_escape_string($koneksi, $_POST['nama_ka']);
    $jenis     = mysqli_real_escape_string($koneksi, $_POST['jenis']);

    mysqli_query($koneksi, "INSERT INTO kereta
        (nomor_ka, nama_ka, jenis)
        VALUES
        ('$nomor_ka', '$nama_ka', '$jenis')
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah_kereta.php';
              </script>";
    }
}

// ============================================================
// PROSES Hapus
// ============================================================

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_query($koneksi, "DELETE FROM kereta WHERE id = $id");

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