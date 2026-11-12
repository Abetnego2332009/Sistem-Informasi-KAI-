<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Kelas Tiket - Admin ';
require '../templates/header.php';
require '../templates/sidebar.php';

$id = (int)$_GET['id'];

// Ambil Data Kelas Tiket

$query = mysqli_query($koneksi, "SELECT * FROM kelas_tiket WHERE id = $id");
$data = mysqli_fetch_array($query);

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Kelas Tiket</h3>
                    <h6 class="op-7 mb-2">Informasi Kelas Tiket</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Kelas Tiket</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_kelas_tiket.php">

                                <!-- Hidden: id jadwal (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                                <div class="row">

                                    <!-- NAMA KELAS TIKET -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kelas Tiket</label>
                                            <input type="text" name="nama_kelas" class="form-control"
                                                value="<?= htmlspecialchars($data['nama_kelas']); ?>">
                                        </div>
                                    </div>

                                    <!-- JAM -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($data['deskripsi']); ?></textarea>
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