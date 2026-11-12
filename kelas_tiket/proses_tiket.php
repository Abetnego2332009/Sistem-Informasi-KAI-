<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id               = intval($_POST['id']);
    $nama_kelas             = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
    $deskripsi              = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    

    mysqli_query($koneksi, "UPDATE kelas_tiket SET
        nama_kelas           = '$nama_kelas',
        deskripsi            = '$deskripsi'
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
    $nama_kelas             = mysqli_real_escape_string($koneksi, $_POST['nama_kelas']);
    $deskripsi              = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    mysqli_query($koneksi, "INSERT INTO kelas_tiket
        (nama_kelas, deskripsi)
        VALUES
        ('$nama_kelas', '$deskripsi')
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah_kelas_tiket.php';
              </script>";
    }
}

// ============================================================
// PROSES Hapus
// ============================================================

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_query($koneksi, "DELETE FROM kelas_tiket WHERE id = $id");

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