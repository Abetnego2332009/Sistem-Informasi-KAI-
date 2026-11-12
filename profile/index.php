<?php
session_start();
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php';

$login_id = intval($_SESSION['id'] ?? 0);

// Ambil data admin yang sedang login (fresh dari DB)
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id = $login_id");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    session_destroy();
    header('Location: ../login.php');
    exit;
}

$title = 'Profil Saya - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';
?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Profil Saya</h3>
                    <h6 class="op-7 mb-2">Informasi akun Anda</h6>
                </div>
            </div>

            <!-- ALERT SUCCESS / ERROR -->
            <?php if (isset($_GET['success']) && $_GET['success'] === 'update'): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> Profil berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php
                    $err = [
                        'email_exists'  => 'Email sudah digunakan akun lain.',
                        'pass_mismatch' => 'Konfirmasi password tidak cocok.',
                        'gagal'         => 'Gagal memperbarui profil, coba lagi.',
                    ];
                    echo $err[$_GET['error']] ?? 'Terjadi kesalahan.';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-9">
                    <div class="card card-round shadow-sm">
                        <div class="card-body p-4 p-md-5">

                            <!-- Layout: Foto kiri, Info kanan -->
                            <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">

                                <!-- FOTO (besar) -->
                                <div class="flex-shrink-0 text-center">
                                    <?php if (!empty($data['foto'])): ?>
                                        <img src="../uploads/admin/<?= htmlspecialchars($data['foto']) ?>"
                                             alt="Foto Profil"
                                             class="rounded-circle"
                                             style="width:150px;height:150px;object-fit:cover;
                                                    border:4px solid #e9ecef;
                                                    box-shadow:0 4px 18px rgba(0,0,0,0.10);">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                             style="width:150px;height:150px;
                                                    border:4px solid #e9ecef;
                                                    box-shadow:0 4px 18px rgba(0,0,0,0.10);">
                                            <i class="fas fa-user text-secondary" style="font-size:60px;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- INFO KANAN -->
                                <div class="flex-grow-1 w-100">

                                    <!-- Nama & Badge Role -->
                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                        <h3 class="fw-bold mb-0"><?= htmlspecialchars($data['nama']) ?></h3>
                                        <span class="badge <?= $data['role'] === 'superadmin' ? 'bg-danger' : 'bg-primary' ?> px-3 py-2"
                                              style="font-size:0.8rem;">
                                            <?= $data['role'] === 'superadmin' ? 'Superadmin' : 'Admin' ?>
                                        </span>
                                    </div>

                                    <!-- Email -->
                                    <p class="text-muted mb-3" style="font-size:1rem;">
                                        <i class="fas fa-envelope me-1"></i>
                                        <?= htmlspecialchars($data['email']) ?>
                                    </p>

                                    <hr class="my-3">

                                    <!-- Detail Info grid -->
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <p class="text-muted small fw-semibold text-uppercase mb-1">Username</p>
                                            <p class="mb-0 fw-semibold">
                                                <code class="fs-6"><?= htmlspecialchars($data['username']) ?></code>
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-muted small fw-semibold text-uppercase mb-1">Login Terakhir</p>
                                            <p class="mb-0">
                                                <?= !empty($data['last_login'])
                                                    ? '<i class="fas fa-clock me-1 text-muted"></i>' . date('d M Y, H:i', strtotime($data['last_login']))
                                                    : '<span class="text-muted fst-italic">Belum tercatat</span>' ?>
                                            </p>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <!-- Tombol Aksi -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="profile_edit.php" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i> Edit Profil
                                        </a>
                                        <a href="index.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-users me-1"></i> Manajemen Admin
                                        </a>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>
</div>