<?php

require_once '../auth/auth_admin.php'; // hanya admin
require_once __DIR__ . '/../koneksi.php';

$title = 'Berita - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Berita</h3>
                    <h6 class="op-7 mb-2">Kelola Data Berita, Promo & Informasi Stasiun</h6>
                </div>
            </div>

            <!-- NOTIFIKASI -->
            <?php if (isset($_GET['sukses'])): ?>
                <?php
                $pesan = match((int)$_GET['sukses']) {
                    1 => 'Berita berhasil ditambahkan.',
                    2 => 'Berita berhasil diperbarui.',
                    3 => 'Berita berhasil dihapus.',
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
                    'not_found'     => 'Data berita tidak ditemukan.',
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
                <a href="tambah_berita.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Berita
                </a>
            </div>

            <!-- TABEL BERITA -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Daftar Berita</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th style="width:70px">Foto</th>
                                            <th>Judul</th>
                                            <th style="width:100px">Kategori</th>
                                            <th style="width:110px">Tgl Tayang</th>
                                            <!-- <th style="width:70px">Views</th> -->
                                            <th style="width:90px">Status</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "SELECT b.*, a.nama AS nama_admin
                                                FROM berita b
                                                LEFT JOIN admin a ON b.admin_id = a.id
                                                ORDER BY b.id ASC";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {

                                            $kat_class = match($row['kategori']) {
                                                'Promo'     => 'bg-success',
                                                'Informasi' => 'bg-info text-dark',
                                                'Kegiatan'  => 'bg-warning text-dark',
                                                default     => 'bg-primary',
                                            };
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td class="text-center">
                                                    <?php if (!empty($row['foto'])): ?>
                                                        <img src="../uploads/berita/<?= htmlspecialchars($row['foto']) ?>"
                                                            alt="foto" class="rounded"
                                                            style="width:50px;height:40px;object-fit:cover;">
                                                    <?php else: ?>
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width:50px;height:40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($row['judul']) ?></strong>
                                                    <div class="text-muted small mt-1">
                                                        <?= htmlspecialchars(mb_strimwidth($row['ringkasan'] ?? '', 0, 90, '...')) ?>
                                                    </div>
                                                    <div class="text-muted small mt-1">
                                                        <i class="fas fa-user-circle me-1"></i><?= htmlspecialchars($row['nama_admin']) ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $kat_class ?>">
                                                        <?= $row['kategori'] ?>
                                                    </span>
                                                </td>
                                                <td><?= date('d M Y', strtotime($row['tgl_tayang'])) ?></td>
                                                <!-- <td class="text-center">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-eye me-1"></i><?= number_format($row['views']) ?>
                                                    </span>
                                                </td> -->
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
                                                        <a href="berita_edit.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses_berita.php?aksi=hapus&id=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus berita ini?')">
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
