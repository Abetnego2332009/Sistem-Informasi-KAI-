<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// AMBIL SESSION ADMIN YANG LOGIN (untuk mengisi kolom admin_id)
// ============================================================
session_start();
$admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 1; // Default ke 1 jika tidak ada sesi (untuk pengujian awal, ganti dengan logika autentikasi yang benar nanti)


// ============================================================
// PROSES TAMBAH
// Dipanggil dari: tambah.php (POST, name="tambah")
// ============================================================
if (isset($_POST['tambah'])) {

    // Sanitasi semua input dari form tambah.php
    $layanan    = mysqli_real_escape_string($koneksi, trim($_POST['layanan']));
    $jam_buka   = mysqli_real_escape_string($koneksi, $_POST['jam_buka']);
    $jam_tutup  = mysqli_real_escape_string($koneksi, $_POST['jam_tutup']);
    $keterangan = mysqli_real_escape_string($koneksi, trim($_POST['keterangan']));
    $urutan     = (int)$_POST['urutan'];
    $is_active  = (int)$_POST['is_active'];

    // Validasi: nama layanan wajib diisi
    if ($layanan === '') {
        echo "<script>alert('Nama layanan wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Validasi: jam buka tidak boleh sama dengan jam tutup
    if ($jam_buka === $jam_tutup) {
        echo "<script>alert('Jam buka dan jam tutup tidak boleh sama!'); window.history.back();</script>";
        exit;
    }

    // Jalankan INSERT ke tabel jam_operasional
    $sql = "INSERT INTO jam_operasional (layanan, jam_buka, jam_tutup, keterangan, is_active, urutan, admin_id)
            VALUES ('$layanan', '$jam_buka', '$jam_tutup', '$keterangan', $is_active, $urutan, $admin_id)";

    mysqli_query($koneksi, $sql);

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data jam operasional berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data jam operasional!');
                window.location.href = 'tambah.php';
              </script>";
    }
}


// ============================================================
// PROSES UPDATE
// Dipanggil dari: edit.php (POST, name="update")
// ============================================================
if (isset($_POST['update'])) {

    // id wajib ada dan bertipe integer
    $id         = (int)$_POST['id'];
    $layanan    = mysqli_real_escape_string($koneksi, trim($_POST['layanan']));
    $jam_buka   = mysqli_real_escape_string($koneksi, $_POST['jam_buka']);
    $jam_tutup  = mysqli_real_escape_string($koneksi, $_POST['jam_tutup']);
    $keterangan = mysqli_real_escape_string($koneksi, trim($_POST['keterangan']));
    $urutan     = (int)$_POST['urutan'];
    $is_active  = (int)$_POST['is_active'];

    // Validasi: nama layanan wajib diisi
    if ($layanan === '') {
        echo "<script>alert('Nama layanan wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Validasi: jam buka tidak boleh sama dengan jam tutup
    if ($jam_buka === $jam_tutup) {
        echo "<script>alert('Jam buka dan jam tutup tidak boleh sama!'); window.history.back();</script>";
        exit;
    }

    // Jalankan UPDATE ke tabel jam_operasional
    // Kolom updated_at diperbarui otomatis oleh trigger ON UPDATE di database
    $sql = "UPDATE jam_operasional SET
                layanan    = '$layanan',
                jam_buka   = '$jam_buka',
                jam_tutup  = '$jam_tutup',
                keterangan = '$keterangan',
                is_active  = $is_active,
                urutan     = $urutan,
                admin_id   = $admin_id
            WHERE id = $id";

    mysqli_query($koneksi, $sql);

    // mysqli_affected_rows() >= 0 karena nilai 0 berarti query sukses tapi data tidak berubah
    if (mysqli_affected_rows($koneksi) >= 0) {
        echo "<script>
                alert('Data jam operasional berhasil diupdate!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate data jam operasional!');
                window.location.href = 'edit.php?id=$id';
              </script>";
    }
}


// ============================================================
// PROSES HAPUS
// Dipanggil dari: index.php (GET, parameter ?hapus=id)
// ============================================================
if (isset($_GET['hapus'])) {

    $id = (int)$_GET['hapus'];

    // Validasi: id harus lebih dari 0
    if ($id <= 0) {
        echo "<script>alert('ID tidak valid!'); window.location.href='index.php';</script>";
        exit;
    }

    // Jalankan DELETE dari tabel jam_operasional berdasarkan id
    mysqli_query($koneksi, "DELETE FROM jam_operasional WHERE id = $id");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data jam operasional berhasil dihapus!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data jam operasional!');
                window.location.href = 'index.php';
              </script>";
    }
}
