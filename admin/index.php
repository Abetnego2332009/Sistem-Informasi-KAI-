<?php
session_start();
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php';


$login_id   = intval($_SESSION['id']   ?? 0);
$login_role = $_SESSION['role'] ?? '';

$title = 'Manajemen Admin - Admin';
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
                    <h3 class="fw-bold mb-3">Manajemen Admin</h3>
                    <h6 class="op-7 mb-2">Kelola akun admin dan petugas stasiun</h6>
                </div>
            </div>

            <!-- ALERT SUCCESS -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php
                    $msg = [
                        'tambah' => 'Admin baru berhasil ditambahkan.',
                        'update' => 'Data admin berhasil diperbarui.',
                        'hapus'  => 'Admin berhasil dihapus.',
                    ];
                    echo $msg[$_GET['success']] ?? 'Operasi berhasil.';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ALERT ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php
                    $err = [
                        'username_exists' => 'Username sudah digunakan, coba yang lain.',
                        'email_exists'    => 'Email sudah terdaftar.',
                        'hapus_sendiri'   => 'Tidak bisa menghapus akun sendiri.',
                        'hapus_super'     => 'Tidak bisa menghapus superadmin terakhir.',
                        'akses_ditolak'   => 'Akses ditolak! Anda tidak punya izin.',
                        'not_found'       => 'Data admin tidak ditemukan.',
                        'gagal'           => 'Operasi gagal, coba lagi.',
                    ];
                    echo $err[$_GET['error']] ?? 'Terjadi kesalahan.';
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- TOMBOL TAMBAH (hanya superadmin) -->
            <?php if ($login_role === 'superadmin'): ?>
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Tambah Admin
                </a>
            </div>
            <?php endif; ?>

            <!-- TABEL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Admin &amp; Petugas</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Foto</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Login Terakhir</th>
                                            <th style="width:130px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no     = 1;
                                        $result = mysqli_query($koneksi, "SELECT * FROM admin ORDER BY role ASC, nama ASC");
                                        while ($row = mysqli_fetch_assoc($result)):
                                            $is_self       = intval($row['id']) === $login_id;
                                            $is_superadmin = $row['role'] === 'superadmin';
                                            $boleh_hapus   = !$is_self && ($login_role === 'superadmin' || !$is_superadmin);
                                        ?>
                                        <tr class="<?= $is_self ? 'table-active' : '' ?>">
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <?php if (!empty($row['foto'])): ?>
                                                    <img src="../uploads/admin/<?= htmlspecialchars($row['foto']) ?>"
                                                         alt="foto"
                                                         style="width:40px;height:40px;object-fit:cover;border-radius:50%;">
                                                <?php else: ?>
                                                    <div style="width:40px;height:40px;border-radius:50%;background:#e9ecef;
                                                                display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-user text-secondary"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($row['nama']) ?>
                                                <?php if ($is_self): ?>
                                                    <span class="badge bg-secondary ms-1">Anda</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><code><?= htmlspecialchars($row['username']) ?></code></td>
                                            <td><?= htmlspecialchars($row['email']) ?></td>
                                            <td>
                                                <?php if ($is_superadmin): ?>
                                                    <span class="badge bg-danger">Superadmin</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">Admin</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= !empty($row['last_login'])
                                                    ? date('d M Y H:i', strtotime($row['last_login']))
                                                    : '<span class="text-muted">Belum pernah</span>' ?>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <!-- Tombol Edit -->
                                                    <a href="edit.php?id=<?= $row['id'] ?>"
                                                       class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>

                                                    <!-- Tombol Hapus: tampil hanya jika boleh hapus -->
                                                    <?php if ($boleh_hapus): ?>
                                                        <a href="proses.php?hapus=<?= $row['id'] ?>"
                                                           class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                           onclick="return confirm('Yakin hapus admin <?= htmlspecialchars(addslashes($row['nama'])) ?>?')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </a>
                                                    <?php else: ?>
                                                        <!-- Tombol disabled dengan tooltip alasan -->
                                                        <button class="btn btn-secondary btn-sm d-flex align-items-center gap-1"
                                                                disabled
                                                                title="<?= $is_self ? 'Tidak bisa hapus akun sendiri' : 'Tidak bisa hapus superadmin' ?>">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>
</div>