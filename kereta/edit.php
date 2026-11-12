<?php
require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Edit Jadwal Kereta - Admin ';
require '../templates/header.php';
require '../templates/sidebar.php';

$id = (int)$_GET['id'];

// Ambil Data Kereta

$query = mysqli_query($koneksi, "SELECT * FROM kereta WHERE id = $id");
$data = mysqli_fetch_array($query);

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard Jadwal Kereta</h3>
                    <h6 class="op-7 mb-2">Informasi Jadwal Keberangkatan Kereta Api</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Edit Jadwal Kereta</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_kereta.php">

                                <!-- Hidden: id jadwal (wajib untuk WHERE di UPDATE) -->
                                <input type="hidden" name="id" value="<?= $data['id']; ?>">

                                <div class="row">

                                    <!-- NAMA KERETA: dropdown dari tabel kereta, value = kereta_id -->
                                   

                                    <!-- VIA -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nomor Kereta</label>
                                            <input type="text" name="nomor_ka" class="form-control"
                                                value="<?= htmlspecialchars($data['nomor_ka']); ?>">
                                        </div>
                                    </div>

                                    <!-- JAM -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kereta</label>
                                            <input type="text" name="nama_ka" class="form-control"
                                                value="<?= htmlspecialchars($data['nama_ka']); ?>">
                                        </div>
                                    </div>

                                    <!-- HARI OPERASI -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Kereta</label>
                                            <select name="jenis" class="form-control">
                                                <option value="Lokal" <?= $data['jenis'] == 'Lokal' ? 'selected' : '' ?>>Lokal</option>
                                                <option value="Ekspres" <?= $data['jenis'] == 'Ekspres' ? 'selected' : '' ?>>Ekspres</option>
                                                <option value="Campuran" <?= $data['jenis'] == 'Campuran' ? 'selected' : '' ?>>Campuran</option>
                                            </select>
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