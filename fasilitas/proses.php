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

    // Sanitasi input dari form tambah.php
    $nama      = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $ikon      = mysqli_real_escape_string($koneksi, trim($_POST['ikon']));
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $urutan    = (int)$_POST['urutan'];
    $is_active = (int)$_POST['is_active'];

    // Validasi: nama wajib diisi
    if ($nama === '') {
        echo "<script>alert('Nama fasilitas wajib diisi!'); window.history.back();</script>";
        exit;
    }
    // cek urutan tidak boleh sama dengan fasilitas lain
        $cek = mysqli_query($koneksi, "SELECT id FROM fasilitas WHERE urutan = '$urutan'");
        if (mysqli_num_rows($cek) == 0) {
            // urutan belum dipakai, lanjutkan
        } else {
            echo "<script>
                    alert('Urutan $urutan sudah dipakai oleh fasilitas lain. Silakan pilih urutan lain!');
                    window.location.href = 'tambah.php';
                  </script>";
            exit;
        }

    // Jalankan INSERT ke tabel fasilitas
    $sql = "INSERT INTO fasilitas (nama, ikon, deskripsi, is_active, urutan, admin_id)
            VALUES ('$nama', '$ikon', '$deskripsi', $is_active, $urutan, $admin_id)";

    mysqli_query($koneksi, $sql);

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data fasilitas berhasil ditambahkan!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data fasilitas!');
                window.location.href = 'tambah.php';
              </script>";
    }
}


// ============================================================
// PROSES UPDATE
// Dipanggil dari: edit.php (POST, name="update")
// ============================================================
if (isset($_POST['update'])) {

    // id wajib ada dan berupa integer
    $id        = (int)$_POST['id'];
    $nama      = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $ikon      = mysqli_real_escape_string($koneksi, trim($_POST['ikon']));
    $deskripsi = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $urutan    = (int)$_POST['urutan'];
    $is_active = (int)$_POST['is_active'];

    // Validasi: nama wajib diisi
    if ($nama === '') {
        echo "<script>alert('Nama fasilitas wajib diisi!'); window.history.back();</script>";
        exit;
    }

    // Jalankan UPDATE ke tabel fasilitas
    // Kolom updated_at diperbarui otomatis oleh trigger ON UPDATE di database
    $sql = "UPDATE fasilitas SET
                nama      = '$nama',
                ikon      = '$ikon',
                deskripsi = '$deskripsi',
                is_active = $is_active,
                urutan    = $urutan,
                admin_id  = $admin_id
            WHERE id = $id";

    mysqli_query($koneksi, $sql);

    // mysqli_affected_rows() >= 0 karena nilai 0 berarti query sukses tapi data tidak berubah
    if (mysqli_affected_rows($koneksi) >= 0) {
        echo "<script>
                alert('Data fasilitas berhasil diupdate!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengupdate data fasilitas!');
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

    // Jalankan DELETE dari tabel fasilitas berdasarkan id
    mysqli_query($koneksi, "DELETE FROM fasilitas WHERE id = $id");

    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Data fasilitas berhasil dihapus!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data fasilitas!');
                window.location.href = 'index.php';
              </script>";
    }
}
