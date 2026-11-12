<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tarif Khusus - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Tarif Khusus</h3>
                    <h6 class="op-7 mb-2">Kelola Data Tarif Diskon & Kategori Penumpang Khusus</h6>
                </div>
            </div>

            <!-- TAMBAH Data -->
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah_tarif_khusus.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Tarif Khusus
                </a>
            </div>

            <!-- TABEL TARIF KHUSUS -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Tarif Khusus</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th style="width:50px">Ikon</th>
                                            <th>Kategori</th>
                                            <th>Diskon</th>
                                            <th>Syarat</th>
                                            <th>Keterangan</th>
                                            <th style="width:90px">Status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "SELECT * FROM tarif_khusus ORDER BY id ASC";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td class="text-center" style="font-size:1.4rem;"><?= $row['ikon_emoji'] ?></td>
                                                <td><strong><?= htmlspecialchars($row['kategori']) ?></strong></td>
                                                <td>
                                                    <span class="badge bg-success fs-6">
                                                        <?= $row['persen_diskon'] ?>%
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($row['syarat'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                                <td>
                                                    <?php if ($row['is_active'] == 1): ?>
                                                        <span class="badge bg-success">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <!-- Tombol Edit -->
                                                        <a href="edit_tarif_khusus.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses_tarif_khusus.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus tarif khusus ini?')">
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
