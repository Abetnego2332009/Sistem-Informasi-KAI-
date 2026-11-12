<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id               = intval($_POST['id']);
    $stasiun_asal_id  = intval($_POST['stasiun_asal_id']);
    $stasiun_tujuan_id = intval($_POST['stasiun_tujuan_id']);
    $kelas             = intval($_POST['kelas_id']);
    $harga              = intval($_POST['harga']);
    $is_active          = mysqli_real_escape_string($koneksi, $_POST['is_active']);


    // Validasi: stasiun asal dan tujuan tidak boleh sama
    if ($stasiun_asal_id == $stasiun_tujuan_id) {
        echo "<script>
                alert('Stasiun asal dan tujuan tidak boleh sama!');
                window.location.href = 'edit.php?id=$id';
              </script>";
        exit;
    }
        // Validasi: cek duplikat data (kecuali data yang sedang diedit)
        $cek = mysqli_query($koneksi, "
        SELECT * FROM harga_tiket 
        WHERE stasiun_asal_id = '$stasiun_asal_id'
        AND stasiun_tujuan_id = '$stasiun_tujuan_id'
        AND kelas_id = '$kelas'
        AND id != '$id'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                    alert('Data dengan stasiun asal, tujuan, dan kelas yang sama sudah ada!');
                    window.location.href = 'tambah.php';
                </script>";
    }


    mysqli_query($koneksi, "UPDATE harga_tiket SET
        stasiun_asal_id    = $stasiun_asal_id,
        stasiun_tujuan_id  = $stasiun_tujuan_id,
        kelas_id           = $kelas,
        harga              = $harga,
        is_active          = $is_active
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


    $stasiun_asal_id  = intval($_POST['stasiun_asal_id']);
    $stasiun_tujuan_id = intval($_POST['stasiun_tujuan_id']);
    $kelas             = intval($_POST['kelas_id']);
    $harga              = intval($_POST['harga']);
    $is_active          = mysqli_real_escape_string($koneksi, $_POST['is_active']);


    // Validasi: stasiun asal dan tujuan tidak boleh sama
    if ($stasiun_asal_id == $stasiun_tujuan_id) {
        echo "<script>
                alert('Stasiun asal dan tujuan tidak boleh sama!');
                window.location.href = 'tambah.php';
              </script>";
        exit;
    }

        $cek = mysqli_query($koneksi, "
        SELECT * FROM harga_tiket 
        WHERE stasiun_asal_id = '$stasiun_asal_id'
        AND stasiun_tujuan_id = '$stasiun_tujuan_id'
        AND kelas_id = '$kelas'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Data dengan stasiun asal, tujuan, dan kelas yang sama sudah ada!');
                window.location.href = 'tambah.php';
              </script>";
        exit;
    }




    mysqli_query($koneksi, "INSERT INTO harga_tiket
        (stasiun_asal_id, stasiun_tujuan_id, kelas_id, harga, is_active)
        VALUES
        ($stasiun_asal_id, $stasiun_tujuan_id, $kelas, $harga, $is_active)
    ");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal ditambahkan!');
                window.location.href = 'tambah.php';
              </script>";
    }
}

// ============================================================
// PROSES Hapus
// ============================================================

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    mysqli_query($koneksi, "DELETE FROM harga_tiket WHERE id = $id");

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
