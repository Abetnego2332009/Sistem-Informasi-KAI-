<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';


// Ambil data login yang sedang aktif
$login_id   = intval($_SESSION['id']   ?? 0);
$login_role = $_SESSION['role'] ?? '';

// Proteksi: harus login dulu
if ($login_id <= 0) {
    header('Location: ../login.php');
    exit;
}

// ============================================================
// HELPER: Upload Foto
// ============================================================
function uploadFoto($file, $foto_lama = '') {
    if (empty($file['name'])) return $foto_lama;

    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $max_size    = 2 * 1024 * 1024; // 2MB
    $ext         = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        echo "<script>alert('Format foto tidak didukung! Gunakan JPG, PNG, atau WEBP.'); history.back();</script>";
        exit;
    }
    if ($file['size'] > $max_size) {
        echo "<script>alert('Ukuran foto terlalu besar! Maks. 2MB.'); history.back();</script>";
        exit;
    }

    $upload_dir = __DIR__ . '/../uploads/admin/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    $nama_baru = 'admin_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $upload_dir . $nama_baru)) {
        echo "<script>alert('Gagal upload foto! Periksa izin folder.'); history.back();</script>";
        exit;
    }

    // Hapus foto lama
    if (!empty($foto_lama) && file_exists($upload_dir . $foto_lama)) {
        unlink($upload_dir . $foto_lama);
    }

    return $nama_baru;
}


// ============================================================
// PROSES TAMBAH
// ============================================================
if (isset($_POST['tambah'])) {

    // Hanya superadmin yang boleh tambah admin
    if ($login_role !== 'superadmin') {
        header('Location: index.php?error=akses_ditolak');
        exit;
    }

    $nama       = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $username   = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $email      = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $role       = in_array($_POST['role'], ['admin', 'superadmin']) ? $_POST['role'] : 'admin';
    $password   = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi_password']);

    // Validasi password cocok
    if ($password !== $konfirmasi) {
        header('Location: tambah.php?error=pass_mismatch');
        exit;
    }

    // Cek username sudah ada
    $cek_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM admin WHERE username = '$username'"));
    if ($cek_user) {
        header('Location: tambah.php?error=username_exists');
        exit;
    }

    // Cek email sudah ada
    $cek_email = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id FROM admin WHERE email = '$email'"));
    if ($cek_email) {
        header('Location: tambah.php?error=email_exists');
        exit;
    }

    $hash     = password_hash($password, PASSWORD_BCRYPT);
    $foto     = uploadFoto($_FILES['foto'] ?? []);
    $foto_sql = $foto ? "'$foto'" : 'NULL';

    mysqli_query($koneksi, "INSERT INTO admin (nama, username, email, password, foto, role)
        VALUES ('$nama', '$username', '$email', '$hash', $foto_sql, '$role')");

    if (mysqli_affected_rows($koneksi) > 0) {
        header('Location: index.php?success=tambah');
    } else {
        header('Location: tambah.php?error=gagal');
    }
    exit;
}


// ============================================================
// PROSES UPDATE
// ============================================================
// ============================================================
// PROSES UPDATE
// ============================================================
if (isset($_POST['update'])) {

    $id         = intval($_POST['id']);
    $nama       = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $email      = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $role       = in_array($_POST['role'], ['admin', 'superadmin']) ? $_POST['role'] : 'admin';
    $foto_lama  = $_POST['foto_lama'] ?? '';
    $hapus_foto = isset($_POST['hapus_foto']) ? true : false;
    $password   = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi_password']);

    // Ambil data target yang akan diupdate
    $target = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $id"));
    if (!$target) {
        header('Location: index.php?error=not_found');
        exit;
    }

    // Proteksi: admin biasa tidak boleh edit superadmin (kecuali diri sendiri)
    if ($login_role !== 'superadmin' && $target['role'] === 'superadmin' && $id !== $login_id) {
        header('Location: index.php?error=akses_ditolak');
        exit;
    }

    // Proteksi: admin biasa tidak boleh ubah role jadi superadmin
    if ($login_role !== 'superadmin') {
        $role = $target['role']; // role tidak berubah
    }

    // Validasi password jika diisi
    if ($password !== '' && $password !== $konfirmasi) {
        header("Location: edit.php?id=$id&error=pass_mismatch");
        exit;
    }

    // Cek email duplikat (kecuali milik sendiri)
    $cek_email = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT id FROM admin WHERE email = '$email' AND id != $id"));
    if ($cek_email) {
        header("Location: edit.php?id=$id&error=email_exists");
        exit;
    }

    // ========== PENANGANAN FOTO ==========
    $upload_dir = __DIR__ . '/../uploads/admin/';
    $foto = $foto_lama; // default pakai foto lama

    // 1. Jika centang hapus, hapus file lama dan set foto = null
    if ($hapus_foto && !empty($foto_lama)) {
        if (file_exists($upload_dir . $foto_lama)) {
            unlink($upload_dir . $foto_lama);
        }
        $foto = null;
    }

    // 2. Upload foto baru (akan menimpa foto lama, termasuk jika sudah dihapus)
    if (!empty($_FILES['foto']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $max_size    = 2 * 1024 * 1024;
        $ext         = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            header("Location: edit.php?id=$id&error=format_foto");
            exit;
        }
        if ($_FILES['foto']['size'] > $max_size) {
            header("Location: edit.php?id=$id&error=size_foto");
            exit;
        }

        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        $nama_baru = 'admin_' . time() . '_' . mt_rand(100, 999) . '.' . $ext;
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $nama_baru)) {
            // Hapus foto lama jika masih ada (kecuali sudah dihapus di step 1)
            if (!empty($foto_lama) && file_exists($upload_dir . $foto_lama)) {
                unlink($upload_dir . $foto_lama);
            }
            $foto = $nama_baru;
        } else {
            header("Location: edit.php?id=$id&error=upload_foto");
            exit;
        }
    }

    $foto_sql = $foto ? "'$foto'" : 'NULL';

    // Password
    if ($password !== '') {
        $hash     = password_hash($password, PASSWORD_BCRYPT);
        $pass_sql = ", password = '$hash'";
    } else {
        $pass_sql = '';
    }

    mysqli_query($koneksi, "UPDATE admin SET
        nama  = '$nama',
        email = '$email',
        role  = '$role',
        foto  = $foto_sql
        $pass_sql
    WHERE id = $id");

    if (mysqli_affected_rows($koneksi) >= 0) {
        // Jika yang diupdate adalah akun sendiri, update session foto
        if ($id === $login_id) {
            $_SESSION['foto'] = $foto; // bisa null
        }
        header('Location: index.php?success=update');
    } else {
        header("Location: edit.php?id=$id&error=gagal");
    }
    exit;
}


// ============================================================
// PROSES HAPUS
// ============================================================
if (isset($_GET['hapus'])) {

    $id = intval($_GET['hapus']);

    // Ambil data admin yang mau dihapus
    $target = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $id"));
    if (!$target) {
        header('Location: index.php?error=not_found');
        exit;
    }

    // Proteksi: tidak boleh hapus diri sendiri
    if ($id === $login_id) {
        header('Location: index.php?error=hapus_sendiri');
        exit;
    }

    // Proteksi: admin biasa tidak boleh hapus superadmin
    if ($login_role !== 'superadmin' && $target['role'] === 'superadmin') {
        header('Location: index.php?error=akses_ditolak');
        exit;
    }

    // Proteksi: tidak boleh hapus superadmin terakhir
    if ($target['role'] === 'superadmin') {
        $jml_super = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT COUNT(*) AS total FROM admin WHERE role = 'superadmin'"));
        if ($jml_super['total'] <= 1) {
            header('Location: index.php?error=hapus_super');
            exit;
        }
    }

    // Hapus foto jika ada
    $upload_dir = __DIR__ . '/../uploads/admin/';
    if (!empty($target['foto']) && file_exists($upload_dir . $target['foto'])) {
        unlink($upload_dir . $target['foto']);
    }

    mysqli_query($koneksi, "DELETE FROM admin WHERE id = $id");

    if (mysqli_affected_rows($koneksi) > 0) {
        header('Location: index.php?success=hapus');
    } else {
        header('Location: index.php?error=gagal');
    }
    exit;
}

// Jika tidak ada aksi yang dikenali
header('Location: index.php');
exit;