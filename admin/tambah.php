<?php
session_start();

require_once __DIR__ . '/../auth/auth_admin.php';


$title = 'Tambah Admin - Admin';
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
                    <h3 class="fw-bold mb-3">Manajemen Admin</h3>
                    <h6 class="op-7 mb-2">Tambah akun admin atau petugas baru</h6>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Tambah Admin Baru</h4>
                        </div>
                        <div class="card-body">

                            <form method="POST" action="proses.php" enctype="multipart/form-data">

                                <div class="row">

                                    <!-- Nama -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" required
                                                   placeholder="Contoh: Budi Santoso">
                                        </div>
                                    </div>

                                    <!-- Username -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username <span class="text-danger">*</span></label>
                                            <input type="text" name="username" class="form-control" required
                                                   placeholder="Contoh: budi_admin"
                                                   pattern="[a-zA-Z0-9_]+"
                                                   title="Hanya huruf, angka, dan underscore">
                                            <small class="text-muted">Hanya huruf, angka, dan underscore. Tidak bisa diubah setelah dibuat.</small>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" required
                                                   placeholder="contoh@kai-perlanaan.id">
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select name="role" class="form-control" required>
                                                <option value="admin" selected>Admin</option>
                                                <option value="superadmin">Superadmin</option>
                                            </select>
                                            <small class="text-muted">
                                                <strong>Admin:</strong> kelola konten. &nbsp;
                                                <strong>Superadmin:</strong> akses penuh termasuk manajemen admin.
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="password"
                                                       class="form-control" required
                                                       minlength="6"
                                                       placeholder="Minimal 6 karakter">
                                                <button type="button" class="btn btn-outline-secondary"
                                                        onclick="togglePass('password', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Konfirmasi Password <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" name="konfirmasi_password" id="konfirmasi"
                                                       class="form-control" required
                                                       minlength="6"
                                                       placeholder="Ulangi password">
                                                <button type="button" class="btn btn-outline-secondary"
                                                        onclick="togglePass('konfirmasi', this)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <small id="passMsg" class="text-danger d-none">Password tidak cocok.</small>
                                        </div>
                                    </div>

                                    <!-- Foto -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Foto Profil</label>
                                            <input type="file" name="foto" class="form-control"
                                                   accept="image/jpeg,image/png,image/webp"
                                                   onchange="previewFoto(this)">
                                            <small class="text-muted">Format: JPG, PNG, WEBP. Maks. 2MB. Opsional.</small>
                                            <div class="mt-2">
                                                <img id="previewImg" src="#" alt="preview"
                                                     style="display:none;width:80px;height:80px;
                                                            object-fit:cover;border-radius:50%;border:2px solid #dee2e6;">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" name="tambah" class="btn btn-primary"
                                            onclick="return validasiPassword()">
                                        <i class="fas fa-save me-1"></i> Simpan Data
                                    </button>
                                    <a href="index.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
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
</div>

<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

function validasiPassword() {
    const p1  = document.getElementById('password').value;
    const p2  = document.getElementById('konfirmasi').value;
    const msg = document.getElementById('passMsg');
    if (p1 !== p2) {
        msg.classList.remove('d-none');
        return false;
    }
    msg.classList.add('d-none');
    return true;
}

function previewFoto(input) {
    const img = document.getElementById('previewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { img.src = e.target.result; img.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>