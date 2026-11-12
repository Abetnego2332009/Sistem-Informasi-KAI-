<?php
/**
 * proses_profile.php
 * Khusus untuk admin update data diri sendiri.
 * - Hanya bisa update nama, email, password, foto milik diri sendiri.
 * - Role & username tidak bisa diubah dari sini.
 * - Setelah berhasil, session nama, email, dan foto diperbarui otomatis.
 * - Mendukung penghapusan foto (checkbox hapus_foto).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';

$login_id = intval($_SESSION['id'] ?? 0);

// Proteksi: harus sudah login
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
// PROSES UPDATE PROFIL SENDIRI
// ============================================================
if (isset($_POST['update_profile'])) {

    $id = intval($_POST['id']);

    // Pastikan admin hanya bisa edit data dirinya sendiri
    if ($id !== $login_id) {
        header('Location: index.php?error=akses_ditolak');
        exit;
    }

    $nama       = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $email      = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $foto_lama  = $_POST['foto_lama'] ?? '';
    $hapus_foto = isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == 1;
    $password   = trim($_POST['password']);
    $konfirmasi = trim($_POST['konfirmasi_password']);

    // Validasi password jika diisi
    if ($password !== '' && $password !== $konfirmasi) {
        header("Location: profile_edit.php?error=pass_mismatch");
        exit;
    }

    // Cek email duplikat (kecuali email milik sendiri)
    $cek_email = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT id FROM admin WHERE email = '$email' AND id != $id"));
    if ($cek_email) {
        header("Location: profile_edit.php?error=email_exists");
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
            header("Location: profile_edit.php?error=format_foto");
            exit;
        }
        if ($_FILES['foto']['size'] > $max_size) {
            header("Location: profile_edit.php?error=size_foto");
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
            header("Location: profile_edit.php?error=upload_foto");
            exit;
        }
    }

    $foto_sql = $foto ? "'$foto'" : 'NULL';

    // Siapkan query password jika diubah
    if ($password !== '') {
        $hash     = password_hash($password, PASSWORD_BCRYPT);
        $pass_sql = ", password = '$hash'";
    } else {
        $pass_sql = '';
    }

    // Update ke database (role & username tidak ikut diupdate)
    mysqli_query($koneksi, "UPDATE admin SET
        nama  = '$nama',
        email = '$email',
        foto  = $foto_sql
        $pass_sql
    WHERE id = $id");

    if (mysqli_affected_rows($koneksi) >= 0) {
        // Perbarui data session supaya navbar/sidebar langsung update
        $_SESSION['nama']  = $nama;
        $_SESSION['email'] = $email;
        $_SESSION['foto']  = $foto; // bisa null atau nama file

        header('Location: index.php?success=update');
    } else {
        header("Location: profile_edit.php?error=gagal");
    }
    exit;
}

// Tidak ada aksi yang dikenali
header('Location: index.php');
exit;