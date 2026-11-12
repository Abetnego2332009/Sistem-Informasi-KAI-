<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id               = intval($_POST['id']);
    $kereta_id        = intval($_POST['kereta_id']);
    $tipe             = mysqli_real_escape_string($koneksi, $_POST['tipe']);
    $stasiun_asal_id  = intval($_POST['stasiun_asal']);
    $stasiun_tujuan_id = intval($_POST['stasiun_tujuan']);
    $via              = mysqli_real_escape_string($koneksi, $_POST['via']);
    $jam              = mysqli_real_escape_string($koneksi, $_POST['jam']);
    $hari_operasi     = mysqli_real_escape_string($koneksi, $_POST['hari_operasi']);
    $status           = mysqli_real_escape_string($koneksi, $_POST['status']);

    mysqli_query($koneksi, "UPDATE jadwal_kereta SET
        kereta_id          = $kereta_id,
        tipe               = '$tipe',
        stasiun_asal_id    = $stasiun_asal_id,
        stasiun_tujuan_id  = $stasiun_tujuan_id,
        via                = '$via',
        jam                = '$jam',
        hari_operasi       = '$hari_operasi',
        status             = '$status'
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

    $kereta_id         = intval($_POST['kereta_id']);
    $tipe              = mysqli_real_escape_string($koneksi, $_POST['tipe']);
    $stasiun_asal_id   = intval($_POST['stasiun_asal_id']);
    $stasiun_tujuan_id = intval($_POST['stasiun_tujuan_id']);
    $via               = mysqli_real_escape_string($koneksi, $_POST['via']);
    $jam               = mysqli_real_escape_string($koneksi, $_POST['jam']);
    $hari_operasi      = mysqli_real_escape_string($koneksi, $_POST['hari_operasi']);
    $status            = mysqli_real_escape_string($koneksi, $_POST['status']);

    mysqli_query($koneksi, "INSERT INTO jadwal_kereta
        (kereta_id, tipe, stasiun_asal_id, stasiun_tujuan_id, via, jam, hari_operasi, status)
        VALUES
        ($kereta_id, '$tipe', $stasiun_asal_id, $stasiun_tujuan_id, '$via', '$jam', '$hari_operasi', '$status')
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah_jadwal.php';
              </script>";
    }
}

// ============================================================
// PROSES Hapus
// ============================================================

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_query($koneksi, "DELETE FROM jadwal_kereta WHERE id = $id");

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
