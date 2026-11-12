<?php

require_once __DIR__ . '/../koneksi.php';
require_once __DIR__ . '/../auth/auth_admin.php'; // hanya admin

$title = 'Pengaturan Sistem - Admin';
require '../templates/header.php';
require '../templates/sidebar.php';

// Ambil semua pengaturan, jadikan array key => nilai
$query = mysqli_query($koneksi, "SELECT * FROM pengaturan_sistem ORDER BY id ASC");
$pengaturan = [];
while ($row = mysqli_fetch_assoc($query)) {
    $pengaturan[$row['kunci']] = $row;
}

// Pastikan key max_pengumuman_beranda ada (migrasi jika belum ada)
if (!isset($pengaturan['max_pengumuman_beranda'])) {
    mysqli_query($koneksi, "INSERT IGNORE INTO pengaturan_sistem (kunci, nilai, label)
        VALUES ('max_pengumuman_beranda', '4', 'Jumlah Pengumuman di Beranda')");
    $pengaturan['max_pengumuman_beranda'] = ['kunci' => 'max_pengumuman_beranda', 'nilai' => '4', 'label' => 'Jumlah Pengumuman di Beranda'];
}

// Helper ambil nilai
function val($pengaturan, $kunci, $default = '') {
    return htmlspecialchars($pengaturan[$kunci]['nilai'] ?? $default);
}

?>

<div class="main-panel">

    <?php require '../templates/navbar.php'; ?>

    <div class="container">
        <div class="page-inner">

            <!-- HEADER -->
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Pengaturan Sistem</h3>
                    <h6 class="op-7 mb-2">Kelola konfigurasi tampilan dan konten landing page</h6>
                </div>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Berhasil!</strong> Pengaturan sistem telah disimpan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Gagal!</strong> Terjadi kesalahan saat menyimpan. Coba lagi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="proses.php">

                <!-- ══════════════════════════════════════
                     1. IDENTITAS WEBSITE
                ══════════════════════════════════════ -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-globe me-2 text-primary"></i>Identitas Website
                        </h4>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            Muncul di tab browser, meta deskripsi, dan bagian Hero landing page.
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Website <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_website" class="form-control"
                                           value="<?= val($pengaturan, 'nama_website', 'Stasiun Perlanaan – KAI Info Layanan') ?>"
                                           placeholder="Contoh: Stasiun Perlanaan – KAI Info Layanan">
                                    <small class="text-muted">Tampil di judul tab browser.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Deskripsi Website</label>
                                    <input type="text" name="deskripsi_website" class="form-control"
                                           value="<?= val($pengaturan, 'deskripsi_website') ?>"
                                           placeholder="Deskripsi singkat untuk mesin pencari (SEO)">
                                    <small class="text-muted">Tampil di Hero landing page dan meta SEO.</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     2. KONTAK & LAYANAN
                ══════════════════════════════════════ -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-phone-alt me-2 text-success"></i>Kontak &amp; Layanan Darurat
                        </h4>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            Tampil di section Kontak, Footer, dan kartu kontak landing page.
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email Kontak</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email_kontak" class="form-control"
                                               value="<?= val($pengaturan, 'email_kontak') ?>"
                                               placeholder="info@kai-perlanaan.id">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Nomor Telepon Stasiun</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="telepon_utama" class="form-control"
                                               value="<?= val($pengaturan, 'telepon_utama') ?>"
                                               placeholder="(0621) 441-2871">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Call Center KAI</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-headset"></i></span>
                                        <input type="text" name="call_center" class="form-control"
                                               value="<?= val($pengaturan, 'call_center', '121') ?>"
                                               placeholder="121">
                                    </div>
                                    <small class="text-muted">Tampil sebagai "Call Center 24 Jam" di footer.</small>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>WhatsApp CS</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="text" name="whatsapp_cs" class="form-control"
                                               value="<?= val($pengaturan, 'whatsapp_cs') ?>"
                                               placeholder="0811-1211-900">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     3. TAMPILAN LANDING PAGE
                ══════════════════════════════════════ -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-desktop me-2 text-info"></i>Tampilan Landing Page
                        </h4>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            Atur apa yang muncul dan berapa banyak konten di halaman utama.
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <!-- Ticker -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Ticker Pengumuman</label>
                                    <div class="d-flex align-items-center gap-3 mt-2">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                   id="tickerAktif" name="ticker_aktif"
                                                   value="1"
                                                   <?= ($pengaturan['ticker_aktif']['nilai'] ?? '0') == '1' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="tickerAktif">
                                                <?= ($pengaturan['ticker_aktif']['nilai'] ?? '0') == '1'
                                                    ? '<span class="badge bg-success">Aktif</span>'
                                                    : '<span class="badge bg-secondary">Nonaktif</span>' ?>
                                            </label>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        Menampilkan baris berjalan pengumuman di bagian atas halaman.
                                    </small>
                                </div>
                            </div>

                            <!-- Max Berita -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Berita di Beranda</label>
                                    <select name="max_berita_beranda" class="form-control">
                                        <?php foreach ([2, 3, 4, 6] as $n): ?>
                                            <option value="<?= $n ?>"
                                                <?= ($pengaturan['max_berita_beranda']['nilai'] ?? '4') == $n ? 'selected' : '' ?>>
                                                <?= $n ?> berita
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Maks. berita terbaru di section Berita & Promo.</small>
                                </div>
                            </div>

                            <!-- Max Pengumuman -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah Pengumuman di Beranda</label>
                                    <select name="max_pengumuman_beranda" class="form-control">
                                        <?php foreach ([2, 3, 4, 6] as $n): ?>
                                            <option value="<?= $n ?>"
                                                <?= ($pengaturan['max_pengumuman_beranda']['nilai'] ?? '4') == $n ? 'selected' : '' ?>>
                                                <?= $n ?> pengumuman
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Maks. pengumuman aktif di section Berita & Pengumuman.</small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════
                     4. GOOGLE MAPS
                ══════════════════════════════════════ -->
                <div class="card card-round mb-4">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-map-marked-alt me-2 text-danger"></i>Google Maps
                        </h4>
                        <p class="text-muted mb-0" style="font-size:.85rem;">
                            URL embed peta yang tampil di section Lokasi &amp; Kontak.
                        </p>
                    </div>
                    <div class="card-body">

                        <div class="form-group">
                            <label>URL Embed Google Maps</label>
                            <textarea name="google_maps_embed" class="form-control" rows="3"
                                      placeholder="https://www.google.com/maps/embed?pb=..."><?= val($pengaturan, 'google_maps_embed') ?></textarea>
                            <small class="text-muted">
                                Cara dapat URL: Buka Google Maps → Cari lokasi → Klik <strong>Bagikan</strong>
                                → <strong>Sematkan peta</strong> → Salin URL dari atribut <code>src="..."</code>.
                            </small>
                        </div>

                        <!-- Preview Maps jika URL sudah ada -->
                        <?php if (!empty($pengaturan['google_maps_embed']['nilai']) &&
                                  strpos($pengaturan['google_maps_embed']['nilai'], 'google.com/maps') !== false): ?>
                            <div class="mt-3">
                                <label class="text-muted d-block mb-2" style="font-size:.85rem;">Preview Peta Saat Ini:</label>
                                <div style="border-radius:10px;overflow:hidden;border:1px solid #dee2e6;">
                                    <iframe src="<?= htmlspecialchars($pengaturan['google_maps_embed']['nilai']) ?>"
                                            width="100%" height="250" style="border:0;"
                                            allowfullscreen="" loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                    </iframe>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex gap-2 mb-5">
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Simpan Semua Pengaturan
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-undo me-1"></i> Reset
                    </a>
                </div>

            </form>

        </div>
    </div>

    <?php require '../templates/footer.php'; ?>
</div>

<script>
// Update label toggle ticker secara real-time
document.getElementById('tickerAktif').addEventListener('change', function () {
    const label = this.nextElementSibling;
    label.innerHTML = this.checked
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-secondary">Nonaktif</span>';
});
</script>