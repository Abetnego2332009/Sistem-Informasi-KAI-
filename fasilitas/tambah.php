<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Fasilitas - Admin';
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Fasilitas</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses.php">

                                <div class="row">

                                    <!-- NAMA FASILITAS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Fasilitas <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control"
                                                placeholder="Contoh: Toilet Umum" required>
                                        </div>
                                    </div>

                                    <!-- IKON (Bootstrap Icons class) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Ikon <small class="text-muted">(Bootstrap Icons class)</small></label>
                                            <input type="text" name="ikon" class="form-control"
                                                placeholder="Contoh: bi-droplet-fill">
                                            <small class="text-muted">
                                                Lihat daftar ikon di
                                                <a href="https://icons.getbootstrap.com/" target="_blank">icons.getbootstrap.com</a>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- DESKRIPSI -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="3"
                                                placeholder="Deskripsi singkat fasilitas..."></textarea>
                                        </div>
                                    </div>

                                    <!-- URUTAN -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Urutan Tampil <span class="text-danger">*</span></label>
                                            <input type="number" name="urutan" class="form-control"
                                                min="0" value="0" required>
                                            <small class="text-muted">Angka kecil tampil lebih dulu</small>
                                        </div>
                                    </div>

                                    <!-- STATUS -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="is_active" class="form-control" required>
                                                <option value="1" selected>Aktif</option>
                                                <option value="0">Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Data
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php'; ?>
</div>
