<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin


$title = 'FAQ - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard FAQ</h3>
                    <h6 class="op-7 mb-2">Kelola Pertanyaan yang Sering Diajukan (FAQ)</h6>
                </div>
            </div>

            <!-- ALERT PESAN -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- TAMBAH Data -->
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah_faq.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah FAQ
                </a>
            </div>

            <!-- TABEL FAQ -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Daftar FAQ</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Pertanyaan</th>
                                            <th>Jawaban</th>
                                            <th>Kategori</th>
                                            <th>Urutan</th>
                                            <th>Status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "
                                            SELECT
                                                f.id,
                                                f.pertanyaan,
                                                f.jawaban,
                                                f.kategori,
                                                f.urutan,
                                                f.is_active,
                                                f.created_at,
                                                a.nama AS admin_nama
                                            FROM faq f
                                            JOIN admin a ON f.admin_id = a.id
                                            ORDER BY f.urutan ASC, f.id ASC
                                        ";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= htmlspecialchars($row['pertanyaan']) ?></td>
                                                <td><?= htmlspecialchars($row['jawaban']) ?></td>
                                                <td>
                                                    <span class="badge bg-info text-dark">
                                                        <?= htmlspecialchars($row['kategori']) ?>
                                                    </span>
                                                </td>
                                                <td><?= $row['urutan'] ?></td>
                                                <td>
                                                    <?php if ($row['is_active'] == 1): ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <!-- Tombol Edit -->
                                                        <a href="edit_faq.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses_faq.php?id=<?= $row['id'] ?>&aksi=hapus"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus FAQ ini?')">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
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
