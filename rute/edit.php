<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Stasiun & Rute - Admin ';
require '../templates/header.php';
require '../templates/sidebar.php';

$id = (int)$_GET['id'];

// Ambil Data Stasiun & Rute

$query = mysqli_query($koneksi, "SELECT * FROM stasiun_rute WHERE id = $id");
$data = mysqli_fetch_array($query);

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Stasiun & Rute</h3>
                    <h6 class="op-7 mb-2">Informasi Stasiun & Rute Kereta Api</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Stasiun & Rute</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_stasiun_rute.php">

                                <!-- Hidden: id jadwal (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                                <div class="row">

                               
                                   

                                    <!-- KODE STASIUN -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kode Stasiun</label>
                                            <input type="text" name="kode" class="form-control"
                                                value="<?= htmlspecialchars($data['kode']); ?>">
                                        </div>
                                    </div>

                                    <!-- NAMA STASIUN -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Stasiun</label>
                                            <input type="text" name="nama" class="form-control"
                                                value="<?= htmlspecialchars($data['nama']); ?>">
                                        </div>
                                    </div>

                                    <!-- KOTA -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <input type="text" name="kota" class="form-control"
                                                value="<?= htmlspecialchars($data['kota']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        Update Data
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        Kembali
                                    </a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php require '../templates/footer.php' ?>