<?php

require_once __DIR__ . '/../koneksi.php';

$aksi = $_GET['aksi'] ?? '';

// ============================================================
// TAMBAH BERITA
// ============================================================
if ($aksi === 'tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $judul      = trim($_POST['judul']);
    $ringkasan  = trim($_POST['ringkasan']);
    $isi        = trim($_POST['isi']);
    $kategori   = $_POST['kategori'];
    $tgl_tayang = $_POST['tgl_tayang'];
    $is_active  = isset($_POST['is_active']) ? 1 : 0;
    $admin_id   = $_SESSION['admin_id'] ?? 1;

    // Generate slug dari judul
    $slug = strtolower(trim($judul));
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');

    // Cek slug duplikat
    $slug_cek = mysqli_real_escape_string($koneksi, $slug);
    $cek = mysqli_query($koneksi, "SELECT id FROM berita WHERE slug = '$slug_cek' LIMIT null");
    if (mysqli_num_rows($cek) > 0) {
        $slug = $slug . '-' . time();
    }

    // Upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $nama_file  = 'berita_' . time() . '.' . $ext;
            $dir_upload = __DIR__ . '/../uploads/berita/';
            if (!is_dir($dir_upload)) mkdir($dir_upload, 0755, true);
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $dir_upload . $nama_file)) {
                $foto = $nama_file;
            }
        } else {
            header('Location: tambah_berita.php?error=format_foto');
            exit;
        }
    }

    $judul      = mysqli_real_escape_string($koneksi, $judul);
    $ringkasan  = mysqli_real_escape_string($koneksi, $ringkasan);
    $isi        = mysqli_real_escape_string($koneksi, $isi);
    $tgl_tayang = mysqli_real_escape_string($koneksi, $tgl_tayang);
    $foto_val   = $foto ? "'$foto'" : "NULL";

    $sql = "INSERT INTO berita (judul, slug, ringkasan, isi, foto, kategori, tgl_tayang, is_active, admin_id)
            VALUES ('$judul', '$slug', '$ringkasan', '$isi', $foto_val, '$kategori', '$tgl_tayang', $is_active, $admin_id)";

    if (mysqli_query($koneksi, $sql)) {
        header('Location: index.php?sukses=1');
    } else {
        header('Location: tambah_berita.php?error=db');
    }
    exit;
}

// ============================================================
// EDIT BERITA
// ============================================================
if ($aksi === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?error=id_invalid');
        exit;
    }

    // Ambil data lama
    $res  = mysqli_query($koneksi, "SELECT foto FROM berita WHERE id = $id LIMIT 1");
    $lama = mysqli_fetch_assoc($res);
    if (!$lama) {
        header('Location: index.php?error=not_found');
        exit;
    }

    $judul      = trim($_POST['judul']);
    $ringkasan  = trim($_POST['ringkasan']);
    $isi        = trim($_POST['isi']);
    $kategori   = $_POST['kategori'];
    $tgl_tayang = $_POST['tgl_tayang'];
    $is_active  = isset($_POST['is_active']) ? 1 : 0;
    $foto       = $lama['foto'];
    $dir_upload = __DIR__ . '/../uploads/berita/';

    // Hapus foto jika dicentang
    if (isset($_POST['hapus_foto']) && $foto) {
        if (file_exists($dir_upload . $foto)) unlink($dir_upload . $foto);
        $foto = null;
    }

    // Upload foto baru jika ada
    if (!empty($_FILES['foto']['name'])) {
        $ext     = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ext, $allowed)) {
            $nama_file = 'berita_' . time() . '.' . $ext;
            if (!is_dir($dir_upload)) mkdir($dir_upload, 0755, true);
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $dir_upload . $nama_file)) {
                // Hapus foto lama
                if ($foto && file_exists($dir_upload . $foto)) unlink($dir_upload . $foto);
                $foto = $nama_file;
            }
        } else {
            header("Location: edit.php?id=$id&error=format_foto");
            exit;
        }
    }

    $judul      = mysqli_real_escape_string($koneksi, $judul);
    $ringkasan  = mysqli_real_escape_string($koneksi, $ringkasan);
    $isi        = mysqli_real_escape_string($koneksi, $isi);
    $tgl_tayang = mysqli_real_escape_string($koneksi, $tgl_tayang);
    $foto_val   = $foto ? "'$foto'" : "NULL";

    $sql = "UPDATE berita SET
                judul      = '$judul',
                ringkasan  = '$ringkasan',
                isi        = '$isi',
                foto       = $foto_val,
                kategori   = '$kategori',
                tgl_tayang = '$tgl_tayang',
                is_active  = $is_active
            WHERE id = $id";

    if (mysqli_query($koneksi, $sql)) {
        header('Location: index.php?sukses=2');
    } else {
        header("Location: edit.php?id=$id&error=db");
    }
    exit;
}

// ============================================================
// HAPUS BERITA
// ============================================================
if ($aksi === 'hapus') {

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        header('Location: index.php?error=id_invalid');
        exit;
    }

    // Ambil data foto
    $res  = mysqli_query($koneksi, "SELECT foto FROM berita WHERE id = $id LIMIT 1");
    $data = mysqli_fetch_assoc($res);

    if (!$data) {
        header('Location: index.php?error=not_found');
        exit;
    }

    // Hapus file foto dari server
    if (!empty($data['foto'])) {
        $path_foto = __DIR__ . '/../uploads/berita/' . $data['foto'];
        if (file_exists($path_foto)) unlink($path_foto);
    }

    // Hapus dari database
    if (mysqli_query($koneksi, "DELETE FROM berita WHERE id = $id")) {
        header('Location: index.php?sukses=3');
    } else {
        header('Location: index.php?error=delete_failed');
    }
    exit;
}

// Jika aksi tidak dikenal
header('Location: index.php');
exit;
