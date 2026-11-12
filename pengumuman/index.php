<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Pengumuman - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Pengumuman</h3>
                    <h6 class="op-7 mb-2">Kelola Data Pengumuman & Informasi Layanan Stasiun</h6>
                </div>
            </div>

            <!-- NOTIFIKASI -->
            <?php if (isset($_GET['sukses'])): ?>
                <?php
                $pesan = match((int)$_GET['sukses']) {
                    1 => 'Pengumuman berhasil ditambahkan.',
                    2 => 'Pengumuman berhasil diperbarui.',
                    3 => 'Pengumuman berhasil dihapus.',
                    default => 'Operasi berhasil.'
                };
                ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $pesan ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <?php
                $pesan_err = match($_GET['error']) {
                    'id_invalid'    => 'ID tidak valid.',
                    'not_found'     => 'Data pengumuman tidak ditemukan.',
                    'delete_failed' => 'Gagal menghapus data. Silakan coba lagi.',
                    default         => 'Terjadi kesalahan.'
                };
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= $pesan_err ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- TAMBAH Data -->
            <div class="d-flex justify-content-between align-items-center my-4">
                <a href="tambah_pengumuman.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Pengumuman
                </a>
            </div>

            <!-- TABEL PENGUMUMAN -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Pengumuman</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Judul</th>
                                            <th style="width:110px">Tipe</th>
                                            <th style="width:100px">Prioritas</th>
                                            <th style="width:110px">Tgl Mulai</th>
                                            <th style="width:110px">Tgl Selesai</th>
                                            <th style="width:90px">Status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "SELECT * FROM pengumuman ORDER BY id ASC";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {

                                            // Badge warna berdasarkan tipe
                                            $tipe_class = match($row['tipe']) {
                                                'Darurat'      => 'bg-danger',
                                                'Keterlambatan'=> 'bg-warning text-dark',
                                                'Gangguan'     => 'bg-orange text-dark',
                                                default        => 'bg-info text-dark',
                                            };

                                            // Badge warna berdasarkan prioritas
                                            $prioritas_class = match($row['prioritas']) {
                                                'Tinggi'  => 'bg-danger',
                                                'Sedang'  => 'bg-warning text-dark',
                                                default   => 'bg-secondary',
                                            };
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($row['judul']) ?></strong>
                                                    <div class="text-muted small mt-1" style="max-width:400px;">
                                                        <?= htmlspecialchars(mb_strimwidth($row['isi'], 0, 100, '...')) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $tipe_class ?>">
                                                        <?= $row['tipe'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $prioritas_class ?>">
                                                        <?= $row['prioritas'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d M Y', strtotime($row['tgl_mulai'])) ?></td>
                                                <td>
                                                    <?= $row['tgl_selesai']
                                                        ? date('d M Y', strtotime($row['tgl_selesai']))
                                                        : '<span class="text-muted">–</span>' ?>
                                                </td>
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
                                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses_pengumuman.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus pengumuman ini?')">
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
