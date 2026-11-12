<?php
require_once 'koneksi.php';

// Ambil parameter id atau slug (gunakan id dulu, lebih sederhana)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Ambil data berita
$sql = "SELECT * FROM berita WHERE id = $id AND is_active = 1 LIMIT 1";
$res = mysqli_query($koneksi, $sql);
$berita = mysqli_fetch_assoc($res);

if (!$berita) {
    // Jika berita tidak ditemukan atau tidak aktif, redirect ke beranda
    header('Location: index.php');
    exit;
}

// Update views (opsional, jika kolom views ada)
// mysqli_query($koneksi, "UPDATE berita SET views = views + 1 WHERE id = $id");

// Helper untuk ikon kategori (sama seperti di index)
function ikonBerita($tipe)
{
    $map = [
        'Berita'    => ['text-success', 'bi-newspaper',      'BERITA KAI',    'linear-gradient(135deg,#27AE60,#1A6639)', 'bi-stars'],
        'Promo'     => ['text-warning', 'bi-megaphone-fill', 'PROMO SPESIAL', 'linear-gradient(135deg,#F39C12,#8B6914)', 'bi-stars'],
        'Informasi' => ['text-primary', 'bi-megaphone',      'PENGUMUMAN',    'linear-gradient(135deg,#2980B9,#1A3A5C)', 'bi-info-circle-fill'],
        'Kegiatan'  => ['text-info',    'bi-calendar-event', 'KEGIATAN',      'linear-gradient(135deg,#16A085,#0D5C4A)', 'bi-people-fill'],
    ];
    return $map[$tipe] ?? $map['Informasi'];
}

$ikon_data = ikonBerita($berita['kategori']);
[$teks_cls, $ikon_cls, $label, $gradient, $bg_ikon] = $ikon_data;

// Ambil pengaturan sistem untuk judul website dll (sama seperti di index)
$pengaturan = [];
$res = mysqli_query($koneksi, "SELECT kunci, nilai FROM pengaturan_sistem");
while ($r = mysqli_fetch_assoc($res)) $pengaturan[$r['kunci']] = $r['nilai'];

$info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM info_stasiun LIMIT 1"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($berita['judul']) ?> - <?= htmlspecialchars($pengaturan['nama_website'] ?? 'Stasiun Perlanaan') ?></title>
    <meta name="description" content="<?= htmlspecialchars($berita['ringkasan'] ?? '') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/landingpage/style.css">
    <link rel="icon" type="image/x-icon" href="assets/gambarKai.png">
</head>
<body>

<!-- Navbar (copy persis dari index.php) -->
<nav class="navbar navbar-kai navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
            <i class="bi bi-train-front-fill text-danger fs-5"></i>
            KAI <span style="color:var(--kai-gold);margin-left:4px"><?= htmlspecialchars($info['kode_stasiun'] === 'PLN' ? 'Perlanaan' : ($info['nama_stasiun'] ?? 'Perlanaan')) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto gap-1">
                <li class="nav-item"><a class="nav-link" href="index.php#beranda">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#jadwal">Jadwal</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#harga">Harga Tiket</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#stasiun">Info Stasiun</a></li>
                <li class="nav-item"><a class="nav-link active" href="index.php#berita">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#faq">FAQ</a></li>
                <li class="nav-item ms-2"><a class="btn btn-kai-red btn-sm" href="https://kai.id" target="_blank">Beli Tiket</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Detail Berita -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Tombol Kembali -->
                <div class="mb-4">
                    <a href="index.php#berita" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                    </a>
                </div>

                <!-- Header Berita -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge" style="background:<?= $gradient ?>; color:white;">
                            <i class="bi <?= $ikon_cls ?> me-1"></i> <?= $label ?>
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i> <?= date('d F Y', strtotime($berita['tgl_tayang'])) ?>
                        </span>
                    </div>
                    <h1 class="display-6 fw-bold mb-3"><?= htmlspecialchars($berita['judul']) ?></h1>
                    <?php if (!empty($berita['ringkasan'])): ?>
                        <p class="lead text-muted"><?= htmlspecialchars($berita['ringkasan']) ?></p>
                    <?php endif; ?>
                </div>

                <!-- Foto (jika ada) -->
                <?php if (!empty($berita['foto'])): ?>
                    <div class="mb-5 text-center">
                        <img src="uploads/berita/<?= htmlspecialchars($berita['foto']) ?>" 
                             alt="<?= htmlspecialchars($berita['judul']) ?>"
                             class="img-fluid rounded shadow-sm"
                             style="max-height:500px; width:auto; object-fit:cover;">
                    </div>
                <?php endif; ?>

                <!-- Isi Berita -->
                <div class="berita-content" style="font-size:1.05rem; line-height:1.8; color: #2c3e50;">
                    <?= nl2br(htmlspecialchars_decode($berita['isi'])) ?>
                </div>

                <!-- Bagian Share / Footer -->
                <hr class="my-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-tag me-1"></i> Kategori: 
                        <span class="badge bg-light text-dark"><?= $berita['kategori'] ?></span>
                    </div>
                    <div>
                        <a href="index.php#berita" class="btn btn-kai-red btn-sm">
                            <i class="bi bi-newspaper me-1"></i> Berita Lainnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer (copy persis dari index.php) -->
<footer class="footer pt-5 pb-3">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-train-front-fill text-danger fs-4"></i>
                    <span class="display-font text-white fs-5">KAI <span style="color:var(--kai-gold)"><?= htmlspecialchars($info['kode_stasiun'] === 'PLN' ? 'Perlanaan' : ($info['nama_stasiun'] ?? 'Perlanaan')) ?></span></span>
                </div>
                <p>Portal resmi informasi layanan <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?>.</p>
            </div>
            <div class="col-6 col-md-2">
                <h6>Layanan</h6>
                <a href="index.php#jadwal">Jadwal Kereta</a>
                <a href="index.php#harga">Harga Tiket</a>
                <a href="index.php#stasiun">Info Stasiun</a>
                <a href="index.php#berita">Berita & Promo</a>
                <a href="index.php#faq">FAQ</a>
            </div>
            <div class="col-md-4">
                <h6>Kontak &amp; Darurat</h6>
                <?php if ($info['telepon']): ?>
                    <p class="mb-1"><i class="bi bi-telephone me-2" style="color:var(--kai-gold);"></i><?= htmlspecialchars($info['telepon']) ?></p>
                <?php endif; ?>
                <?php if (!empty($pengaturan['call_center'])): ?>
                    <p class="mb-1"><i class="bi bi-headset me-2" style="color:var(--kai-gold);"></i>Call Center: <strong style="color:#fff;"><?= htmlspecialchars($pengaturan['call_center']) ?></strong></p>
                <?php endif; ?>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,.1);">
        <div class="text-center">
            <p class="mb-0" style="font-size:.8rem;">&copy; <?= date('Y') ?> PT Kereta Api Indonesia (Persero)</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>