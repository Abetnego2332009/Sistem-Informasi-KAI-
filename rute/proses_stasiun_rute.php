<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id   = intval($_POST['id']);
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kota = mysqli_real_escape_string($koneksi, $_POST['kota']);

    mysqli_query($koneksi, "UPDATE stasiun_rute SET
        kode = '$kode',
        nama = '$nama',
        kota = '$kota'
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

    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $kota = mysqli_real_escape_string($koneksi, $_POST['kota']);

    mysqli_query($koneksi, "INSERT INTO stasiun_rute
        (kode, nama, kota)
        VALUES
        ('$kode', '$nama', '$kota')
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah_stasiun_rute.php';
              </script>";
    }
}

// ============================================================
// PROSES HAPUS ✅ FIXED
// ============================================================
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // ✅ Benar: hapus dari stasiun_rute, bukan kereta
    mysqli_query($koneksi, "DELETE FROM stasiun_rute WHERE id = $id");

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