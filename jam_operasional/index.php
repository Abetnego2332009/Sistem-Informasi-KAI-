<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Jam Operasional - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Jam Operasional</h3>
                    <h6 class="op-7 mb-2">Kelola Jam Operasional Layanan Stasiun Perlanaan</h6>
                </div>
            </div>

            <!-- TOMBOL TAMBAH -->
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Jam Operasional
                </a>
            </div>

            <!-- TABEL JAM OPERASIONAL -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Data Jam Operasional Layanan</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Nama Layanan</th>
                                            <th>Jam Buka</th>
                                            <th>Jam Tutup</th>
                                            <th>Keterangan</th>
                                            <th>Urutan</th>
                                            <th>Status</th>
                                            <th>Diperbarui Oleh</th>
                                            <th style="width:130px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no  = 1;
                                        $sql = "
                                            SELECT
                                                j.id,
                                                j.layanan,
                                                j.jam_buka,
                                                j.jam_tutup,
                                                j.keterangan,
                                                j.is_active,
                                                j.urutan,
                                                j.updated_at,
                                                a.nama AS nama_admin
                                            FROM jam_operasional j
                                            LEFT JOIN admin a ON j.admin_id = a.id
                                            ORDER BY j.urutan ASC, j.id ASC
                                        ";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)):
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <i class="fas fa-clock text-primary me-1"></i>
                                                    <?= htmlspecialchars($row['layanan']) ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info text-dark">
                                                        <?= date('H:i', strtotime($row['jam_buka'])) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= date('H:i', strtotime($row['jam_tutup'])) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                                <td class="text-center"><?= $row['urutan'] ?></td>
                                                <td class="text-center">
                                                    <?php if ($row['is_active'] == 1): ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($row['nama_admin'] ?? '-') ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <!-- Tombol Edit -->
                                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses.php?hapus=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin ingin menghapus jam operasional ini?')">
                                                            <i class="fas fa-trash"></i>
                                                            Hapus
                                                        </a>

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

    <?php require '../templates/footer.php'; ?>
</div>
