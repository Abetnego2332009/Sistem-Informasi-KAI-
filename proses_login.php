<?php
session_start();
require_once __DIR__ . '/koneksi.php';
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk memeriksa apakah email dan password cocok dengan data di database
    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE email='$email'");
    $admin = mysqli_fetch_array($query);

    if ($admin) {
        // verifikasi password
        if (password_verify($password, $admin['password'])) {
            // Login berhasil
            $_SESSION['id'] = $admin['id'];
            $_SESSION['email'] = $admin['email'];
            $_SESSION['nama'] = $admin['nama'];
            $_SESSION['role'] = $admin['role'];
            $_SESSION['foto'] = $admin['foto'];

            // Update last login
            $id = $admin['id'];
            mysqli_query($koneksi, "UPDATE admin SET last_login = NOW() WHERE id = $id");
            header("Location: dashboard.php");
        } else {
            echo "<script>
                    alert('Password salah!');
                    window.location='login.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Email tidak ditemukan!');
                window.location='login.php';
              </script>";
    }
    mysqli_close($koneksi);
}
