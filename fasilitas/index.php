<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Fasilitas Stasiun - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Fasilitas Stasiun</h3>
                    <h6 class="op-7 mb-2">Kelola Data Fasilitas Stasiun Perlanaan</h6>
                </div>
            </div>

            <!-- TOMBOL TAMBAH -->
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Fasilitas
                </a>
            </div>

            <!-- TABEL FASILITAS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Data Fasilitas Stasiun</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Ikon</th>
                                            <th>Nama Fasilitas</th>
                                            <th>Deskripsi</th>
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
                                                f.id,
                                                f.nama,
                                                f.ikon,
                                                f.deskripsi,
                                                f.is_active,
                                                f.urutan,
                                                f.updated_at,
                                                a.nama AS nama_admin
                                            FROM fasilitas f
                                            LEFT JOIN admin a ON f.admin_id = a.id
                                            ORDER BY f.urutan ASC, f.id ASC
                                        ";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)):
                                        ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td class="text-center">
                                                    <i class="bi <?= htmlspecialchars($row['ikon']) ?>" style="font-size:1.4rem;"></i>
                                                </td>
                                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                                <td><?= htmlspecialchars($row['deskripsi'] ?? '-') ?></td>
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
                                                            onclick="return confirm('Yakin ingin menghapus fasilitas ini?')">
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
