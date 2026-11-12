<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Tambah Stasiun & Rute - Admin';
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
                    <h3 class="fw-bold mb-3">Dashboard Stasiun & Rute</h3>
                    <h6 class="op-7 mb-2">Informasi Stasiun & Rute Kereta Api</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Stasiun & Rute</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses_stasiun_rute.php">

                            <?php 
                            $query = "SELECT * FROM stasiun_rute ORDER BY id DESC";
                            $result = mysqli_query($koneksi, $query);
                            ?>
                                <div class="row">

                                 
                                    <!-- Nomor Kereta -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kode Stasiun</label>
                                            <input type="text" name="kode" class="form-control"
                                                placeholder="Contoh: PLN">
                                        </div>
                                    </div>

                                    <!-- Nama Stasiun -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Stasiun</label>
                                            <input type="text" name="nama" class="form-control"
                                                placeholder="Contoh: Perlanaan">
                                        </div>
                                    </div>

                                    <!-- Kota -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <input type="text" name="kota" class="form-control"
                                                placeholder="Contoh: Serdang Bedagai">
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