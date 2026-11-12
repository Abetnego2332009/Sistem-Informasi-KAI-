<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Profil Stasiun - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil data profil stasiun (hanya 1 record)
$query  = mysqli_query($koneksi, "SELECT * FROM info_stasiun LIMIT 1");
$stasiun = mysqli_fetch_assoc($query);

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Profil Stasiun</h3>
                    <h6 class="op-7 mb-2">Informasi & Detail Data Stasiun</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="edit.php?id=<?= $stasiun['id'] ?>" class="btn btn-primary btn-round">
                        <i class="fas fa-edit me-1"></i> Edit Profil
                    </a>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> Data profil stasiun telah diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">

                <!-- FOTO & INFO SINGKAT -->
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-body text-center py-4">
                            <?php if (!empty($stasiun['foto'])): ?>
                                <img src="../uploads/stasiun/<?= htmlspecialchars($stasiun['foto']) ?>"
                                    class="img-fluid rounded mb-3"
                                    alt="Foto Stasiun"
                                    style="max-height:200px; object-fit:cover; width:100%;">
                            <?php else: ?>
                                <div class="bg-light rounded mb-3 d-flex align-items-center justify-content-center"
                                    style="height:160px;">
                                    <i class="fas fa-train fa-4x text-secondary"></i>
                                </div>
                            <?php endif; ?>

                            <h5 class="fw-bold mb-1"><?= htmlspecialchars($stasiun['nama_stasiun']) ?></h5>
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($stasiun['kode_stasiun']) ?></span>
                            <br>
                            <span class="badge bg-secondary"><?= htmlspecialchars($stasiun['kelas_stasiun']) ?></span>

                            <hr>

                            <div class="text-start">
                                <p class="mb-1"><i class="fas fa-road text-muted me-2"></i>
                                    <strong><?= $stasiun['jumlah_jalur'] ?></strong> Jalur KA</p>
                                <p class="mb-1"><i class="fas fa-mountain text-muted me-2"></i>
                                    <strong><?= $stasiun['ketinggian_mdpl'] ?? '-' ?></strong> mdpl</p>
                                <p class="mb-1"><i class="fas fa-sitemap text-muted me-2"></i>
                                    <?= htmlspecialchars($stasiun['divre'] ?? '-') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETAIL INFORMASI -->
                <div class="col-md-8">

                    <!-- Identitas Stasiun -->
                    <div class="card card-round mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-info-circle me-2 text-primary"></i>Identitas Stasiun</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td class="text-muted fw-semibold" style="width:140px">Nama Stasiun</td>
                                            <td>: <?= htmlspecialchars($stasiun['nama_stasiun']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Kode Stasiun</td>
                                            <td>: <span class="badge bg-primary"><?= htmlspecialchars($stasiun['kode_stasiun']) ?></span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Kelas Stasiun</td>
                                            <td>: <?= htmlspecialchars($stasiun['kelas_stasiun']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Divisi Regional</td>
                                            <td>: <?= htmlspecialchars($stasiun['divre'] ?? '-') ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm mb-0">
                                        <tr>
                                            <td class="text-muted fw-semibold" style="width:140px">Jumlah Jalur</td>
                                            <td>: <?= $stasiun['jumlah_jalur'] ?> jalur</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Ketinggian</td>
                                            <td>: <?= $stasiun['ketinggian_mdpl'] ?? '-' ?> mdpl</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Latitude</td>
                                            <td>: <?= $stasiun['latitude'] ?? '-' ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-semibold">Longitude</td>
                                            <td>: <?= $stasiun['longitude'] ?? '-' ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="card card-round mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Alamat Stasiun</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted fw-semibold" style="width:140px">Alamat Lengkap</td>
                                    <td>: <?= htmlspecialchars($stasiun['alamat']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Desa</td>
                                    <td>: <?= htmlspecialchars($stasiun['desa'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Kecamatan</td>
                                    <td>: <?= htmlspecialchars($stasiun['kecamatan'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Kabupaten</td>
                                    <td>: <?= htmlspecialchars($stasiun['kabupaten'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Provinsi</td>
                                    <td>: <?= htmlspecialchars($stasiun['provinsi'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Kode Pos</td>
                                    <td>: <?= htmlspecialchars($stasiun['kode_pos'] ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div class="card card-round mb-4">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-phone-alt me-2 text-success"></i>Informasi Kontak</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted fw-semibold" style="width:140px">Telepon</td>
                                    <td>: <?= htmlspecialchars($stasiun['telepon'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Email</td>
                                    <td>: <?= htmlspecialchars($stasiun['email'] ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted fw-semibold">Website</td>
                                    <td>:
                                        <?php if (!empty($stasiun['website'])): ?>
                                            <a href="<?= htmlspecialchars($stasiun['website']) ?>" target="_blank">
                                                <?= htmlspecialchars($stasiun['website']) ?>
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <!-- DESKRIPSI & SEJARAH -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-align-left me-2 text-info"></i>Deskripsi Stasiun</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($stasiun['deskripsi'] ?? 'Belum ada deskripsi.')) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title"><i class="fas fa-history me-2 text-warning"></i>Sejarah Stasiun</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($stasiun['sejarah'] ?? 'Belum ada data sejarah.')) ?></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php'; ?>
</div>