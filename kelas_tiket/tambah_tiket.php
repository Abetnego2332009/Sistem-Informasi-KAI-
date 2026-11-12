<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Kelas Tiket - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Kelas Tiket</h3>
                    <h6 class="op-7 mb-2">Informasi Kelas Tiket</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Kelas Tiket</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_tiket.php">

                                <?php
                                $query = "SELECT * FROM kelas_tiket ORDER BY id DESC";
                                $result = mysqli_query($koneksi, $query);
                                ?>
                                <div class="row">


                                    <!-- Nama Kelas Tiket -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Kelas Tiket</label>
                                            <select name="nama_kelas" class="form-control" required>
                                                <option value="">-- Pilih Kelas Tiket --</option>
                                                <option value="Ekonomi">Ekonomi</option>
                                                <option value="Bisnis">Bisnis</option>
                                                <option value="Eksekutif">Eksekutif</option>
                                            </select>
                                        </div>
                                    </div>


                                    <!-- Deskripsi -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="4"
                                                placeholder="Masukkan deskripsi..."></textarea>
                                        </div>
                                    </div>



                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="tambah" class="btn btn-primary">
                                        Simpan Data
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
    <option value="Setiap Hari">Setiap Hari</option>
    <option value="Senin-Jumat">Senin - Jumat</option>
    <option value="Sabtu-Minggu">Sabtu - Minggu</option>
    <option value="Senin-Sabtu">Senin - Sabtu</option>
    </select>
</div>
</div>

<!-- Status -->
<div class="col-md-6">
    <div class="form-group">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="Normal" selected>Normal</option>
            <option value="Terlambat">Terlambat</option>
            <option value="Dibatalkan">Dibatalkan</option>
        </select>
    </div>
</div>

</div>

<div class="mt-3">
    <button type="submit" name="tambah" class="btn btn-primary">
        Simpan Data
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