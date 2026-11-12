<?php

require_once __DIR__ . '/../koneksi.php';

// ============================================================
// PROSES UPDATE PROFIL STASIUN
// ============================================================
if (isset($_POST['update'])) {

    $id             = intval($_POST['id']);
    $nama_stasiun   = mysqli_real_escape_string($koneksi, trim($_POST['nama_stasiun']));
    $kode_stasiun   = mysqli_real_escape_string($koneksi, strtoupper(trim($_POST['kode_stasiun'])));
    $kelas_stasiun  = mysqli_real_escape_string($koneksi, $_POST['kelas_stasiun']);
    $divre          = mysqli_real_escape_string($koneksi, trim($_POST['divre']));
    $jumlah_jalur   = intval($_POST['jumlah_jalur']);
    $ketinggian     = $_POST['ketinggian_mdpl'] !== '' ? intval($_POST['ketinggian_mdpl']) : 'NULL';
    $alamat         = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $desa           = mysqli_real_escape_string($koneksi, trim($_POST['desa']));
    $kecamatan      = mysqli_real_escape_string($koneksi, trim($_POST['kecamatan']));
    $kabupaten      = mysqli_real_escape_string($koneksi, trim($_POST['kabupaten']));
    $provinsi       = mysqli_real_escape_string($koneksi, trim($_POST['provinsi']));
    $kode_pos       = mysqli_real_escape_string($koneksi, trim($_POST['kode_pos']));
    $latitude       = $_POST['latitude']  !== '' ? floatval($_POST['latitude'])  : 'NULL';
    $longitude      = $_POST['longitude'] !== '' ? floatval($_POST['longitude']) : 'NULL';
    $telepon        = mysqli_real_escape_string($koneksi, trim($_POST['telepon']));
    $email          = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $website        = mysqli_real_escape_string($koneksi, trim($_POST['website']));
    $deskripsi      = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $sejarah        = mysqli_real_escape_string($koneksi, trim($_POST['sejarah']));
    $foto_lama      = $_POST['foto_lama'];

    // ---- UPLOAD FOTO ----
    $foto_baru = $foto_lama; // default: pakai foto lama

    if (!empty($_FILES['foto']['name'])) {

        $allowed_ext  = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size     = 2 * 1024 * 1024; // 2MB
        $file_name    = $_FILES['foto']['name'];
        $file_tmp     = $_FILES['foto']['tmp_name'];
        $file_size    = $_FILES['foto']['size'];
        $file_ext     = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>
                    alert('Format foto tidak didukung! Gunakan JPG, PNG, atau WEBP.');
                    window.location.href = 'edit.php?id=$id';
                  </script>";
            exit;
        }

        if ($file_size > $max_size) {
            echo "<script>
                    alert('Ukuran foto terlalu besar! Maksimal 2MB.');
                    window.location.href = 'edit.php?id=$id';
                  </script>";
            exit;
        }

        // Buat folder upload jika belum ada
        $dir_upload = __DIR__ . '/../uploads/stasiun/';
        if (!is_dir($dir_upload)) {
            mkdir($dir_upload, 0755, true);
        }

        // Nama file baru unik
        $foto_baru = 'stasiun_' . time() . '.' . $file_ext;

        if (!move_uploaded_file($file_tmp, $dir_upload . $foto_baru)) {
            echo "<script>
                    alert('Gagal mengupload foto! Periksa izin folder.');
                    window.location.href = 'edit.php?id=$id';
                  </script>";
            exit;
        }

        // Hapus foto lama jika ada
        if (!empty($foto_lama) && file_exists($dir_upload . $foto_lama)) {
            unlink($dir_upload . $foto_lama);
        }
    }

    // Nilai NULL perlu tanpa kutip di SQL
    $lat_sql        = is_numeric($latitude)   ? $latitude   : 'NULL';
    $lng_sql        = is_numeric($longitude)  ? $longitude  : 'NULL';
    $tinggi_sql     = is_numeric($ketinggian) ? $ketinggian : 'NULL';
    $foto_sql       = !empty($foto_baru) ? "'$foto_baru'" : 'NULL';

    mysqli_query($koneksi, "UPDATE info_stasiun SET
        nama_stasiun     = '$nama_stasiun',
        kode_stasiun     = '$kode_stasiun',
        kelas_stasiun    = '$kelas_stasiun',
        divre            = '$divre',
        jumlah_jalur     = $jumlah_jalur,
        ketinggian_mdpl  = $tinggi_sql,
        alamat           = '$alamat',
        desa             = '$desa',
        kecamatan        = '$kecamatan',
        kabupaten        = '$kabupaten',
        provinsi         = '$provinsi',
        kode_pos         = '$kode_pos',
        latitude         = $lat_sql,
        longitude        = $lng_sql,
        telepon          = '$telepon',
        email            = '$email',
        website          = '$website',
        deskripsi        = '$deskripsi',
        sejarah          = '$sejarah',
        foto             = $foto_sql,
        updated_at       = NOW()
    WHERE id = $id");

    if (mysqli_affected_rows($koneksi) >= 0) {
        echo "<script>
                alert('Profil stasiun berhasil diperbarui!');
                window.location.href = 'index.php?success=1';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data! Coba lagi.');
                window.location.href = 'edit.php?id=$id';
              </script>";
    }
}