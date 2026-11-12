<?php 
session_start();
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/auth/auth_admin.php';
$title = 'Dashboard - Admin';
require 'templates/header.php';
require 'templates/sidebar.php';

// ========== KUMPULKAN DATA STATISTIK ==========
// 1. Berita
$total_berita     = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM berita"))['cnt'];
$berita_aktif     = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM berita WHERE is_active = 1"))['cnt'];
$berita_nonaktif  = $total_berita - $berita_aktif;

// 2. Pengumuman
$total_pengumuman     = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM pengumuman"))['cnt'];
$pengumuman_aktif     = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM pengumuman WHERE is_active = 1"))['cnt'];
$pengumuman_tinggi    = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM pengumuman WHERE prioritas = 'Tinggi' AND is_active = 1"))['cnt'];

// 3. Jadwal Kereta (aktif)
$total_jadwal_aktif   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM jadwal_kereta WHERE is_active = 1"))['cnt'];
$jadwal_berangkat     = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM jadwal_kereta WHERE tipe = 'keberangkatan' AND is_active = 1"))['cnt'];
$jadwal_datang        = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM jadwal_kereta WHERE tipe = 'kedatangan' AND is_active = 1"))['cnt'];

// 4. Fasilitas
$total_fasilitas      = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM fasilitas WHERE is_active = 1"))['cnt'];

// 5. FAQ
$total_faq            = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM faq WHERE is_active = 1"))['cnt'];

// 6. Tarif Khusus
$total_tarif_khusus   = (int) mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as cnt FROM tarif_khusus WHERE is_active = 1"))['cnt'];

// 7. Info stasiun
$info_stasiun = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_stasiun, kode_stasiun, alamat FROM info_stasiun LIMIT 1"));
?>

<div class="main-panel">
    <?php require 'templates/navbar.php'; ?>
    
    <div class="container">
        <div class="page-inner">
            <!-- Header -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Ringkasan data Stasiun <?= htmlspecialchars($info_stasiun['nama_stasiun'] ?? 'Perlanaan') ?></h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="berita/index.php" class="btn btn-primary btn-round btn-sm">Kelola Berita</a>
                    <a href="pengumuman/index.php" class="btn btn-secondary btn-round btn-sm">Kelola Pengumuman</a>
                </div>
            </div>

            <!-- Kartu Statistik (4 kolom) -->
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Berita</p>
                                        <h4 class="card-title"><?= $total_berita ?></h4>
                                        <small class="text-success">Aktif: <?= $berita_aktif ?></small>
                                        <?php if($berita_nonaktif > 0): ?>
                                            <small class="text-danger"> | Nonaktif: <?= $berita_nonaktif ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Pengumuman</p>
                                        <h4 class="card-title"><?= $total_pengumuman ?></h4>
                                        <small class="text-success">Aktif: <?= $pengumuman_aktif ?></small>
                                        <small class="text-warning"> | Prioritas Tinggi: <?= $pengumuman_tinggi ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-train"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Jadwal Aktif</p>
                                        <h4 class="card-title"><?= $total_jadwal_aktif ?></h4>
                                        <small>Berangkat: <?= $jadwal_berangkat ?> | Datang: <?= $jadwal_datang ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Fasilitas</p>
                                        <h4 class="card-title"><?= $total_fasilitas ?></h4>
                                        <small>FAQ: <?= $total_faq ?> | Tarif Khusus: <?= $total_tarif_khusus ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info singkat stasiun -->
            <!-- <div class="row mt-4">
                <div class="col-12">
                    <div class="card card-round bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    <strong><?= htmlspecialchars($info_stasiun['nama_stasiun']) ?></strong> 
                                    (Kode: <?= htmlspecialchars($info_stasiun['kode_stasiun']) ?>)
                                    <span class="text-muted mx-2">|</span>
                                    <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                    <?= htmlspecialchars($info_stasiun['alamat']) ?>
                                </div>
                                <div>
                                    <a href="profil_stasiun/index.php" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-pen"></i> Edit Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

        </div>
    </div>

    <?php require 'templates/footer.php'; ?>
</div>