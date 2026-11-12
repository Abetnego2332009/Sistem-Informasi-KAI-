<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Kelas Tiket - Admin ';
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

            <!-- TAMBAH Data  -->
            <div class="d-flex justify-content-between align-items-center my-4">
                
                <a href="tambah_tiket.php" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Tambah Kelas Tiket
                </a>
            </div>

            <!-- TABEL nama -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Nama Kelas Tiket</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">No</th>
                                            <th>Nama Kelas Tiket</th>
                                            <th>Deskripsi</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $sql = "SELECT * FROM kelas_tiket ORDER BY id DESC";
                                        $result = mysqli_query($koneksi, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['nama_kelas'] ?></td>
                                                <td><?= $row['deskripsi'] ?></td>
                                                <td>
                                                    <div class="d-flex gap-2">

                                                        <!-- Tombol Edit -->
                                                        <a href="edit.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-warning btn-sm d-flex align-items-center gap-1">
                                                            <i class="fas fa-edit"></i>
                                                            Edit
                                                        </a>

                                                        <!-- Tombol Hapus -->
                                                        <a href="proses_tiket.php?id=<?= $row['id'] ?>"
                                                            class="btn btn-danger btn-sm d-flex align-items-center gap-1"
                                                            onclick="return confirm('Yakin mau hapus data ini?')">
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