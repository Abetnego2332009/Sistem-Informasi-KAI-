<?php
require 'koneksi.php';

// ── Data dari database

// Pengaturan sistem
$pengaturan = [];
$res = mysqli_query($koneksi, "SELECT kunci, nilai FROM pengaturan_sistem");
while ($r = mysqli_fetch_assoc($res)) $pengaturan[$r['kunci']] = $r['nilai'];

// Pengumuman aktif (untuk ticker)
$ticker_items = [];
if (!empty($pengaturan['ticker_aktif']) && $pengaturan['ticker_aktif'] == 1) {
  $res = mysqli_query($koneksi, "SELECT judul, tipe FROM pengumuman WHERE is_active = 1 ORDER BY prioritas='Tinggi' DESC, created_at DESC");
  while ($r = mysqli_fetch_assoc($res)) $ticker_items[] = $r;
}

// Info Stasiun
$info = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM info_stasiun LIMIT 1"));

// Jam Operasional
$jam_ops = [];
$res = mysqli_query($koneksi, "SELECT * FROM jam_operasional WHERE is_active = 1 ORDER BY urutan ASC");
while ($r = mysqli_fetch_assoc($res)) $jam_ops[] = $r;

// Fasilitas
$fasilitas_list = [];
$res = mysqli_query($koneksi, "SELECT * FROM fasilitas WHERE is_active = 1 ORDER BY urutan ASC");
while ($r = mysqli_fetch_assoc($res)) $fasilitas_list[] = $r;

// Harga Tiket per kelas (asal = stasiun PLN id=1)
$harga_per_kelas = [1 => [], 2 => [], 3 => []];
$res = mysqli_query($koneksi, "
    SELECT h.harga, h.kelas_id, sr.nama AS tujuan
    FROM harga_tiket h
    JOIN stasiun_rute sr ON sr.id = h.stasiun_tujuan_id
    WHERE h.stasiun_asal_id = 1 AND h.is_active = 1
    ORDER BY h.kelas_id, sr.nama
");
while ($r = mysqli_fetch_assoc($res)) {
  $harga_per_kelas[$r['kelas_id']][] = $r;
}

// Tarif Khusus
$tarif_khusus = [];
$res = mysqli_query($koneksi, "SELECT * FROM tarif_khusus WHERE is_active = 1 ORDER BY id ASC");
while ($r = mysqli_fetch_assoc($res)) $tarif_khusus[] = $r;

// Jadwal Kereta
$jadwal_berangkat = [];
$res = mysqli_query($koneksi, "
    SELECT j.*, k.nomor_ka, k.nama_ka, k.jenis,
           sa.nama AS nama_asal, st.nama AS nama_tujuan
    FROM jadwal_kereta j
    JOIN kereta k ON k.id = j.kereta_id
    JOIN stasiun_rute sa ON sa.id = j.stasiun_asal_id
    JOIN stasiun_rute st ON st.id = j.stasiun_tujuan_id
    WHERE j.tipe = 'keberangkatan' AND j.is_active = 1
    ORDER BY j.jam ASC
");
while ($r = mysqli_fetch_assoc($res)) $jadwal_berangkat[] = $r;

$jadwal_datang = [];
$res = mysqli_query($koneksi, "
    SELECT j.*, k.nomor_ka, k.nama_ka, k.jenis,
           sa.nama AS nama_asal, st.nama AS nama_tujuan
    FROM jadwal_kereta j
    JOIN kereta k ON k.id = j.kereta_id
    JOIN stasiun_rute sa ON sa.id = j.stasiun_asal_id
    JOIN stasiun_rute st ON st.id = j.stasiun_tujuan_id
    WHERE j.tipe = 'kedatangan' AND j.is_active = 1
    ORDER BY j.jam ASC
");
while ($r = mysqli_fetch_assoc($res)) $jadwal_datang[] = $r;

// Berita & Pengumuman
$berita_list = [];
$res = mysqli_query($koneksi, "SELECT * FROM berita WHERE is_active = 1 ORDER BY tgl_tayang DESC LIMIT " . (int)($pengaturan['max_berita_beranda'] ?? 3));
while ($r = mysqli_fetch_assoc($res)) $berita_list[] = $r;

$pengumuman_list = [];

$limit = (int)($pengaturan['max_pengumuman_beranda'] ?? 4);

$query = "
    SELECT 
        id,
        judul,
        isi,
        tipe,
        prioritas,
        tgl_mulai,
        tgl_selesai,
        created_at
    FROM pengumuman
    WHERE is_active = 1
    ORDER BY 
        FIELD(prioritas, 'Tinggi', 'Sedang', 'Rendah') ASC,
        created_at DESC
    LIMIT {$limit}
";

$res = mysqli_query($koneksi, $query);

if ($res) {
  while ($r = mysqli_fetch_assoc($res)) {
    $pengumuman_list[] = $r;
  }
}


// FAQ
$faq_list = [];
$res = mysqli_query($koneksi, "SELECT * FROM faq WHERE is_active = 1 ORDER BY urutan ASC");
while ($r = mysqli_fetch_assoc($res)) $faq_list[] = $r;

// Helper: format jam
function fmtJam($t)
{
  return substr($t, 0, 5);
}

// Helper: badge kelas
function badgeKelas($jenis)
{
  $map = ['Lokal' => 'badge-ekonomi', 'Ekspres' => 'badge-bisnis', 'Campuran' => 'badge-eksekutif'];
  $label = ['Lokal' => 'Ekonomi', 'Ekspres' => 'Bisnis', 'Campuran' => 'Eksekutif'];
  $cls = $map[$jenis] ?? 'badge-ekonomi';
  $lbl = $label[$jenis] ?? $jenis;
  return "<span class=\"badge {$cls} rounded-pill\">{$lbl}</span>";
}

// Helper: badge status
function badgeStatus($s)
{
  if ($s === 'Terlambat') return '<span class="badge bg-warning text-dark rounded-pill">Terlambat</span>';
  if ($s === 'Dibatalkan') return '<span class="badge bg-danger rounded-pill">Dibatalkan</span>';
  return '<span class="badge bg-success rounded-pill">Normal</span>';
}

// Helper: ikon tipe berita (sesuai enum DB: Berita, Promo, Informasi, Kegiatan)
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
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pengaturan['nama_website'] ?? 'Stasiun Perlanaan – KAI Info Layanan') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pengaturan['deskripsi_website'] ?? '') ?>" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/landingpage/style.css" />
  <link rel="icon" type="image/x-icon" href="assets/gambarKai.png" />
</head>

<body>

  <!-- ── Announcement Ticker ── -->
  <?php if (!empty($ticker_items)): ?>
    <div class="ann-ticker">
      <span class="ticker-inner">
        <?php foreach ($ticker_items as $t):
          $ikon = match ($t['tipe']) {
            'Gangguan'      => '⚠️',
            'Keterlambatan' => '🚨',
            'Darurat'       => '🔴',
            default         => '📢',
          };
        ?>
          &nbsp;&nbsp;&nbsp;<?= $ikon ?> <?= htmlspecialchars($t['judul']) ?> &nbsp;&nbsp;&nbsp;
        <?php endforeach; ?>
      </span>
    </div>
  <?php endif; ?>

  <!-- ── Navbar ── -->
  <nav class="navbar navbar-kai navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <i class="bi bi-train-front-fill text-danger fs-5"></i>
        KAI <span style="color:var(--kai-gold);margin-left:4px">
          <?= htmlspecialchars($info['kode_stasiun'] === 'PLN' ? 'Perlanaan' : ($info['nama_stasiun'] ?? 'Perlanaan')) ?>
        </span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto gap-1">
          <li class="nav-item"><a class="nav-link active" href="#beranda">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#jadwal">Jadwal</a></li>
          <li class="nav-item"><a class="nav-link" href="#harga">Harga Tiket</a></li>
          <li class="nav-item"><a class="nav-link" href="#stasiun">Info Stasiun</a></li>
          <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
          <li class="nav-item"><a class="nav-link" href="#faq">FAQ</a></li>
          <li class="nav-item ms-2">
            <a class="btn btn-kai-red btn-sm" href="https://kai.id" target="_blank">Beli Tiket</a>
          </li>
          <li class="nav-item ms-1">
            <a href="login.php"
              title="Portal Petugas"
              style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:8px;color:rgba(255,255,255,.35);transition:all .2s;border:1px solid rgba(255,255,255,.12);"
              onmouseenter="this.style.color='rgba(255,255,255,.85)';this.style.borderColor='rgba(255,255,255,.35)'"
              onmouseleave="this.style.color='rgba(255,255,255,.35)';this.style.borderColor='rgba(255,255,255,.12)'">
              <i class="bi bi-lock-fill" style="font-size:.8rem;"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ── HERO ── -->
  <section class="hero" id="beranda">
    <div class="hero-rail-lines"></div>
    <div class="container py-5 position-relative">
      <div class="row align-items-center g-5">
        <div class="col-lg-7">
          <div class="hero-badge"><?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?> • KAI Indonesia</div>
          <h1 class="mb-4">
            Layanan Informasi<br>Kereta Api <span>Terpercaya</span><br>untuk Masyarakat
          </h1>
          <p class="lead mb-4">
            <?= htmlspecialchars($pengaturan['deskripsi_website'] ?? 'Portal resmi informasi jadwal, harga tiket, fasilitas, dan pengumuman Stasiun Kereta Api Perlanaan.') ?>
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="#jadwal" class="btn btn-kai-red"><i class="bi bi-search me-2"></i>Cek Jadwal Kereta</a>
            <a href="#harga" class="btn btn-kai-outline"><i class="bi bi-tags me-2"></i>Lihat Harga Tiket</a>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="row g-3">
            <div class="col-6">
              <div class="stat-pill">
                <div class="num"><?= count($jadwal_berangkat) + count($jadwal_datang) ?>+</div>
                <div class="lbl">Jadwal/Hari</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-pill">
                <div class="num">24/7</div>
                <div class="lbl">Info Online</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-pill">
                <div class="num"><?= count($jadwal_berangkat) ?></div>
                <div class="lbl">Keberangkatan</div>
              </div>
            </div>
            <div class="col-6">
              <div class="stat-pill">
                <div class="num">3</div>
                <div class="lbl">Kelas Layanan</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ── JADWAL KERETA ── -->
  <section class="py-5 schedule-section" id="jadwal">
    <div class="container">
      <div class="fade-up text-center mb-4">
        <h2 class="section-heading center">Jadwal Kereta Api</h2>
        <p class="text-muted mt-3" style="font-size:.93rem;">Informasi jadwal keberangkatan dan kedatangan di <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?></p>
      </div>

      <!-- Filter Keberangkatan -->
      <div id="filterKeberangkatan" class="row g-2 mb-4 fade-up">
        <div class="col-md-4">
          <select class="form-select form-select-sm" id="filterTujuan">
            <option value="">— Semua Tujuan —</option>
            <?php
            $tujuan_unik = array_unique(array_map(fn($j) => $j['nama_tujuan'], $jadwal_berangkat));
            foreach ($tujuan_unik as $t): ?>
              <option><?= htmlspecialchars($t) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select form-select-sm" id="filterHariBerangkat">
            <option value="">— Semua Hari —</option>
            <option>Setiap Hari</option>
            <option>Senin-Jumat</option>
            <option>Sabtu-Minggu</option>
            <option>Senin-Sabtu</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-kai-red btn-sm w-100" onclick="filterJadwalBerangkat()">
            <i class="bi bi-funnel me-1"></i>Filter
          </button>
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-secondary btn-sm w-100" onclick="resetFilterBerangkat()">
            <i class="bi bi-x-circle me-1"></i>Reset
          </button>
        </div>
      </div>

      <!-- Filter Kedatangan (tersembunyi saat tab Keberangkatan aktif) -->
      <div id="filterKedatangan" class="row g-2 mb-4 fade-up d-none">
        <div class="col-md-4">
          <select class="form-select form-select-sm" id="filterAsal">
            <option value="">— Semua Asal —</option>
            <?php
            $asal_unik = array_unique(array_map(fn($j) => $j['nama_asal'], $jadwal_datang));
            foreach ($asal_unik as $a): ?>
              <option><?= htmlspecialchars($a) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <select class="form-select form-select-sm" id="filterHariDatang">
            <option value="">— Semua Hari —</option>
            <option>Setiap Hari</option>
            <option>Senin-Jumat</option>
            <option>Sabtu-Minggu</option>
            <option>Senin-Sabtu</option>
          </select>
        </div>
        <div class="col-md-2">
          <button class="btn btn-kai-red btn-sm w-100" onclick="filterJadwalDatang()">
            <i class="bi bi-funnel me-1"></i>Filter
          </button>
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-secondary btn-sm w-100" onclick="resetFilterDatang()">
            <i class="bi bi-x-circle me-1"></i>Reset
          </button>
        </div>
      </div>

      <!-- Tabs -->
      <ul class="nav tab-kai border-bottom mb-4 fade-up" id="jadwalTab">
        <li class="nav-item">
          <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#keberangkatan">
            <i class="bi bi-arrow-right-circle me-2"></i>Keberangkatan
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-bs-toggle="tab" data-bs-target="#kedatangan">
            <i class="bi bi-arrow-left-circle me-2"></i>Kedatangan
          </button>
        </li>
      </ul>

      <div class="tab-content fade-up">
        <!-- Keberangkatan -->
        <div class="tab-pane fade show active" id="keberangkatan">
          <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover mb-0 schedule-table bg-white">
              <thead>
                <tr>
                  <th>No. KA</th>
                  <th>Nama Kereta</th>
                  <th>Tujuan</th>
                  <th>Berangkat</th>
                  <th>Via</th>
                  <th>Kelas</th>
                  <th>Hari</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="tblKeberangkatan">
                <?php if (empty($jadwal_berangkat)): ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada jadwal keberangkatan</td>
                  </tr>
                  <?php else: foreach ($jadwal_berangkat as $j): ?>
                    <tr data-tujuan="<?= htmlspecialchars($j['nama_tujuan']) ?>" data-hari="<?= htmlspecialchars($j['hari_operasi']) ?>">
                      <td><strong><?= htmlspecialchars($j['nomor_ka']) ?></strong></td>
                      <td><?= htmlspecialchars($j['nama_ka']) ?></td>
                      <td><?= htmlspecialchars($j['nama_tujuan']) ?></td>
                      <td><strong><?= fmtJam($j['jam']) ?></strong>
                        <?php if ($j['keterlambatan_menit'] > 0): ?>
                          <small class="text-danger">(+<?= $j['keterlambatan_menit'] ?>m)</small>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($j['via'] ?? 'Langsung') ?></td>
                      <td><?= badgeKelas($j['jenis']) ?></td>
                      <td><?= htmlspecialchars($j['hari_operasi']) ?></td>
                      <td><?= badgeStatus($j['status']) ?></td>
                    </tr>
                <?php endforeach;
                endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Kedatangan -->
        <div class="tab-pane fade" id="kedatangan">
          <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover mb-0 schedule-table bg-white">
              <thead>
                <tr>
                  <th>No. KA</th>
                  <th>Nama Kereta</th>
                  <th>Asal</th>
                  <th>Tiba</th>
                  <th>Via</th>
                  <th>Kelas</th>
                  <th>Hari</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="tblKedatangan">
                <?php if (empty($jadwal_datang)): ?>
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada jadwal kedatangan</td>
                  </tr>
                  <?php else: foreach ($jadwal_datang as $j): ?>
                    <tr data-asal="<?= htmlspecialchars($j['nama_asal']) ?>" data-hari="<?= htmlspecialchars($j['hari_operasi']) ?>">
                      <td><strong><?= htmlspecialchars($j['nomor_ka']) ?></strong></td>
                      <td><?= htmlspecialchars($j['nama_ka']) ?></td>
                      <td><?= htmlspecialchars($j['nama_asal']) ?></td>
                      <td><strong><?= fmtJam($j['jam']) ?></strong>
                        <?php if ($j['keterlambatan_menit'] > 0): ?>
                          <small class="text-danger">(+<?= $j['keterlambatan_menit'] ?>m)</small>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($j['via'] ?? 'Langsung') ?></td>
                      <td><?= badgeKelas($j['jenis']) ?></td>
                      <td><?= htmlspecialchars($j['hari_operasi']) ?></td>
                      <td><?= badgeStatus($j['status']) ?></td>
                    </tr>
                <?php endforeach;
                endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <p class="text-muted mt-3 fade-up" style="font-size:.78rem;">
        <i class="bi bi-info-circle me-1"></i>
        Jadwal dapat berubah sewaktu-waktu. Selalu cek informasi terbaru di loket atau aplikasi KAI Access.
      </p>
    </div>
  </section>

  <!-- ── HARGA TIKET ── -->
  <section class="py-5 bg-white" id="harga">
    <div class="container">
      <div class="fade-up text-center mb-5">
        <h2 class="section-heading center">Harga Tiket</h2>
        <p class="text-muted mt-3" style="font-size:.93rem;">Tarif resmi perjalanan dari <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?></p>
      </div>

      <div class="row g-4 mb-5">
        <?php
        $kelas_config = [
          1 => ['label' => 'Kelas Ekonomi', 'icon' => 'bi-person-fill', 'header_cls' => 'card-ekonomi'],
          2 => ['label' => 'Kelas Bisnis', 'icon' => 'bi-person-badge-fill', 'header_cls' => 'card-bisnis'],
          3 => ['label' => 'Kelas Eksekutif', 'icon' => 'bi-star-fill', 'header_cls' => 'card-eksekutif'],
        ];
        foreach ($kelas_config as $kelas_id => $cfg): ?>
          <div class="col-md-4 fade-up">
            <div class="card price-card shadow-sm">
              <div class="card-header <?= $cfg['header_cls'] ?> text-center">
                <i class="bi <?= $cfg['icon'] ?> fs-2 mb-2 d-block opacity-75"></i>
                <?= $cfg['label'] ?>
              </div>
              <div class="card-body p-4">
                <?php if (empty($harga_per_kelas[$kelas_id])): ?>
                  <p class="text-muted text-center">Belum ada data tarif</p>
                <?php else: ?>
                  <table class="table table-sm mb-0">
                    <thead>
                      <tr>
                        <th style="font-size:.75rem;color:var(--kai-muted);">RUTE</th>
                        <th style="font-size:.75rem;color:var(--kai-muted);" class="text-end">TARIF</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($harga_per_kelas[$kelas_id] as $h): ?>
                        <tr>
                          <td>Perlanaan → <?= htmlspecialchars($h['tujuan']) ?></td>
                          <td class="text-end fw-bold">Rp <?= number_format($h['harga'], 0, ',', '.') ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Tarif Khusus -->
      <?php if (!empty($tarif_khusus)): ?>
        <div class="tarif-khusus fade-up">
          <div class="row align-items-center">
            <div class="col-md-3 mb-3 mb-md-0">
              <h4 class="display-font text-white mb-1">Tarif Khusus</h4>
              <p style="color:rgba(255,255,255,.55);font-size:.85rem;margin:0;">Berlaku dengan menunjukkan dokumen resmi yang valid</p>
            </div>
            <div class="col-md-9">
              <div class="row g-0">
                <?php foreach ($tarif_khusus as $tk): ?>
                  <div class="col-6 col-md-<?= count($tarif_khusus) <= 4 ? '3' : '2' ?>">
                    <div class="tarif-khusus-item text-center px-2">
                      <div style="font-size:1.8rem;"><?= $tk['ikon_emoji'] ?></div>
                      <div style="color:var(--kai-gold);font-weight:700;font-size:.95rem;"><?= htmlspecialchars($tk['kategori']) ?></div>
                      <div style="color:rgba(255,255,255,.65);font-size:.78rem;">Diskon <?= $tk['persen_diskon'] ?>%</div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ── INFO STASIUN & FASILITAS ── -->
  <section class="py-5 facility-section" id="stasiun">
    <div class="container">
      <div class="fade-up mb-5">
        <h2 class="section-heading text-white">Info Stasiun &amp; Fasilitas</h2>
      </div>

      <!-- Profil -->
      <div class="row g-4 mb-4 fade-up">

        <!-- Kolom Kiri: Foto + Identitas -->
        <div class="col-md-5">

          <!-- Foto Stasiun -->
          <?php if (!empty($info['foto'])): ?>
            <img src="uploads/stasiun/<?= htmlspecialchars($info['foto']) ?>"
              alt="Foto <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun') ?>"
              class="w-100 rounded mb-4"
              style="max-height:220px;object-fit:cover;border:2px solid rgba(255,255,255,.15);">
          <?php endif; ?>

          <h5 class="text-white fw-bold mb-3">
            <i class="bi bi-building me-2" style="color:var(--kai-gold);"></i>
            Profil <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?>
          </h5>

          <p style="color:rgba(255,255,255,.7);font-size:.9rem;line-height:1.85;">
            <?= htmlspecialchars($info['deskripsi'] ?? '') ?>
          </p>

          <ul style="color:rgba(255,255,255,.65);font-size:.88rem;padding-left:1.2rem;line-height:2.2;">
            <li>Kode Stasiun: <strong style="color:#fff;"><?= htmlspecialchars($info['kode_stasiun'] ?? '-') ?></strong></li>
            <?php if ($info['kelas_stasiun']): ?>
              <li>Kelas Stasiun: <strong style="color:#fff;"><?= htmlspecialchars($info['kelas_stasiun']) ?></strong></li>
            <?php endif; ?>
            <?php if ($info['jumlah_jalur']): ?>
              <li>Jumlah Jalur: <strong style="color:#fff;"><?= $info['jumlah_jalur'] ?> jalur aktif</strong></li>
            <?php endif; ?>
            <?php if ($info['ketinggian_mdpl']): ?>
              <li>Ketinggian: <strong style="color:#fff;">±<?= $info['ketinggian_mdpl'] ?> mdpl</strong></li>
            <?php endif; ?>
            <?php if ($info['divre']): ?>
              <li>Divisi Regional: <strong style="color:#fff;"><?= htmlspecialchars($info['divre']) ?></strong></li>
            <?php endif; ?>
            <?php if ($info['desa'] || $info['kecamatan']): ?>
              <li>Lokasi: <strong style="color:#fff;">
                  <?= implode(', ', array_filter([
                    htmlspecialchars($info['desa']      ?? ''),
                    htmlspecialchars($info['kecamatan'] ?? ''),
                    htmlspecialchars($info['kabupaten'] ?? ''),
                    htmlspecialchars($info['provinsi']  ?? ''),
                  ])) ?>
                  <?= $info['kode_pos'] ? '(' . $info['kode_pos'] . ')' : '' ?>
                </strong></li>
            <?php endif; ?>
            <?php if ($info['latitude'] && $info['longitude']): ?>
              <li>
                <a href="https://www.google.com/maps?q=<?= $info['latitude'] ?>,<?= $info['longitude'] ?>"
                  target="_blank"
                  style="color:var(--kai-gold);">
                  <i class="bi bi-geo-alt-fill me-1"></i>Buka di Google Maps
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Kolom Tengah: Sejarah -->
        <div class="col-md-4">
          <?php if (!empty($info['sejarah'])): ?>
            <div style="background:rgba(255,255,255,.07);border-radius:14px;padding:1.5rem;height:100%;">
              <h6 class="text-white fw-bold mb-3">
                <i class="bi bi-clock-history me-2" style="color:var(--kai-gold);"></i>Sejarah Stasiun
              </h6>
              <p style="color:rgba(255,255,255,.65);font-size:.88rem;line-height:1.9;margin:0;">
                <?= nl2br(htmlspecialchars($info['sejarah'])) ?>
              </p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Kolom Kanan: Jam Operasional -->
        <div class="col-md-3">
          <div class="hours-card p-4">
            <h6 class="text-white fw-bold mb-3">
              <i class="bi bi-clock me-2" style="color:var(--kai-gold);"></i>Jam Operasional
            </h6>
            <?php foreach ($jam_ops as $jam): ?>
              <div class="hours-row d-flex justify-content-between">
                <span><?= htmlspecialchars($jam['layanan']) ?></span>
                <strong style="color:var(--kai-gold);">
                  <?php
                  $buka  = fmtJam($jam['jam_buka']);
                  $tutup = fmtJam($jam['jam_tutup']);
                  if ($buka === '00:00' && $tutup === '23:59') echo '24 Jam';
                  else echo $buka . ' – ' . $tutup . ' WIB';
                  ?>
                </strong>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Fasilitas Grid -->
      <?php if (!empty($fasilitas_list)): ?>
        <h5 class="text-white fw-bold mb-4 fade-up"><i class="bi bi-grid me-2" style="color:var(--kai-gold);"></i>Fasilitas Tersedia</h5>
        <div class="row g-3 fade-up">
          <?php foreach ($fasilitas_list as $f): ?>
            <div class="col-6 col-md-3 col-lg-2">
              <div class="facility-card">
                <div class="facility-icon"><i class="bi <?= htmlspecialchars($f['ikon'] ?? 'bi-check-circle') ?>"></i></div>
                <h6><?= htmlspecialchars($f['nama']) ?></h6>
                <p><?= htmlspecialchars($f['deskripsi'] ?? '') ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- ── LOKASI & KONTAK ── -->
  <section class="py-5 bg-white">
    <div class="container">
      <div class="fade-up mb-4">
        <h2 class="section-heading">Lokasi &amp; Kontak Stasiun</h2>
      </div>
      <div class="row g-4 fade-up">
        <div class="col-md-7">
          <div class="map-embed">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3796.9557679991826!2d99.36235957474373!3d3.1757141967995817!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3033d02ad15fb337%3A0x39aec37836bbb522!2sPerlanaan!5e1!3m2!1sid!2sid!4v1777653353456!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
              style="border:0;"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
          <p class="text-muted mt-2" style="font-size:.8rem;">
            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($info['alamat'] ?? '') ?>
          </p>
        </div>
        <div class="col-md-5">
          <?php if ($info['telepon']): ?>
            <div class="contact-item">
              <div class="icon"><i class="bi bi-telephone-fill"></i></div>
              <div class="info">
                <small>Telepon Stasiun</small>
                <strong><?= htmlspecialchars($info['telepon']) ?></strong>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($info['email']): ?>
            <div class="contact-item">
              <div class="icon"><i class="bi bi-envelope-fill"></i></div>
              <div class="info">
                <small>Email</small>
                <strong><?= htmlspecialchars($info['email']) ?></strong>
              </div>
            </div>
          <?php endif; ?>
          <?php if (!empty($pengaturan['call_center'])): ?>
            <div class="contact-item">
              <div class="icon"><i class="bi bi-headset"></i></div>
              <div class="info">
                <small>Call Center KAI</small>
                <strong><?= htmlspecialchars($pengaturan['call_center']) ?> (24 Jam)</strong>
              </div>
            </div>
          <?php endif; ?>
          <?php if (!empty($pengaturan['whatsapp_cs'])): ?>
            <div class="contact-item">
              <div class="icon"><i class="bi bi-whatsapp"></i></div>
              <div class="info">
                <small>WhatsApp CS</small>
                <strong><?= htmlspecialchars($pengaturan['whatsapp_cs']) ?></strong>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($info['website']): ?>
            <div class="contact-item">
              <div class="icon"><i class="bi bi-globe2"></i></div>
              <div class="info">
                <small>Website Resmi</small>
                <strong><?= htmlspecialchars($info['website']) ?></strong>
              </div>
            </div>
          <?php endif; ?>
          <?php if ($info['email']): ?>
            <a href="mailto:<?= htmlspecialchars($info['email']) ?>" class="btn btn-kai-red mt-2">
              <i class="bi bi-envelope me-2"></i>Kirim Pesan
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── PENGUMUMAN & BERITA ── -->
  <!-- ── PENGUMUMAN & BERITA ── -->
  <section class="py-5" style="background:var(--kai-smoke);" id="berita">
    <div class="container">

      <div class="fade-up mb-5">
        <h2 class="section-heading">Pengumuman &amp; Berita</h2>
      </div>

      <!-- ================================================
         KARTU PENGUMUMAN
    ================================================ -->
      <?php if (!empty($pengumuman_list)): ?>
        <h5 class="fw-bold mb-3 fade-up">
          <i class="bi bi-megaphone-fill text-danger me-2"></i>Pengumuman
        </h5>
        <div class="row g-3 mb-5 fade-up">
          <?php foreach ($pengumuman_list as $peng):
            $tipe      = $peng['tipe']      ?? 'Umum';
            $prioritas = $peng['prioritas'] ?? 'Rendah';
            $tgl_mulai  = $peng['tgl_mulai']   ?? null;
            $tgl_selesai = $peng['tgl_selesai'] ?? null;

            $gradient_map = [
              'Darurat'       => 'linear-gradient(135deg,#C0392B,#8B1A1A)',
              'Keterlambatan' => 'linear-gradient(135deg,#F39C12,#8B6914)',
              'Gangguan'      => 'linear-gradient(135deg,#E67E22,#9D4E10)',
              'Umum'          => 'linear-gradient(135deg,#2980B9,#1A3A5C)',
            ];
            $ikon_map = [
              'Darurat'       => 'bi-exclamation-octagon-fill',
              'Keterlambatan' => 'bi-clock-history',
              'Gangguan'      => 'bi-exclamation-triangle-fill',
              'Umum'          => 'bi-megaphone-fill',
            ];
            $prioritas_class = match ($prioritas) {
              'Tinggi' => 'bg-danger',
              'Sedang' => 'bg-warning text-dark',
              default  => 'bg-secondary',
            };
            $gradient  = $gradient_map[$tipe]  ?? $gradient_map['Umum'];
            $ikon_tipe = $ikon_map[$tipe]       ?? $ikon_map['Umum'];
          ?>
            <div class="col-md-6 col-lg-4">
              <div class="card h-100 border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
                <!-- Header -->
                <div class="d-flex align-items-center justify-content-between py-2 px-3"
                  style="background:<?= $gradient ?>;">
                  <span class="text-white fw-bold" style="font-size:.8rem;letter-spacing:.4px;">
                    <i class="bi <?= $ikon_tipe ?> me-1"></i>
                    <?= strtoupper($tipe) ?>
                  </span>
                  <span class="badge <?= $prioritas_class ?>" style="font-size:.68rem;">
                    <?= $prioritas ?>
                  </span>
                </div>
                <!-- Body -->
                <div class="card-body p-3 d-flex flex-column">
                  <h6 class="fw-bold mb-2" style="font-size:.9rem;line-height:1.45;">
                    <?= htmlspecialchars($peng['judul']) ?>
                  </h6>
                  <p class="text-muted flex-grow-1" style="font-size:.8rem;line-height:1.65;">
                    <?= htmlspecialchars(mb_strimwidth($peng['isi'], 0, 130, '…')) ?>
                  </p>
                  <?php if ($tgl_mulai): ?>
                    <small class="text-muted mt-1" style="font-size:.74rem;">
                      <i class="bi bi-calendar3 me-1"></i>
                      <?= date('d M Y', strtotime($tgl_mulai)) ?>
                      <?php if ($tgl_selesai): ?> – <?= date('d M Y', strtotime($tgl_selesai)) ?><?php endif; ?>
                    </small>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- ================================================
         KARTU BERITA
    ================================================ -->
      <?php if (!empty($berita_list)): ?>
        <h5 class="fw-bold mb-4 fade-up">
          <i class="bi bi-newspaper text-danger me-2"></i>Berita Terbaru
        </h5>
        <div class="row g-4 fade-up">
          <?php foreach ($berita_list as $b):
            $ikon_data = ikonBerita($b['kategori']);
            [$teks_cls, $ikon_cls, $label, $gradient, $bg_ikon] = $ikon_data;
          ?>
            <div class="col-md-4">
              <div class="card news-card shadow-sm h-100 d-flex flex-column border-0"
                style="border-radius:14px;overflow:hidden;transition:transform .2s,box-shadow .2s;"
                onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,.13)'"
                onmouseleave="this.style.transform='';this.style.boxShadow=''">

                <!-- Thumbnail -->
                <div style="height:200px;overflow:hidden;flex-shrink:0;position:relative;">
                  <?php if (!empty($b['foto'])): ?>
                    <img src="uploads/berita/<?= htmlspecialchars($b['foto']) ?>"
                      alt="<?= htmlspecialchars($b['judul']) ?>"
                      style="width:100%;height:100%;object-fit:cover;transition:transform .4s;"
                      onmouseenter="this.style.transform='scale(1.04)'"
                      onmouseleave="this.style.transform=''">
                  <?php else: ?>
                    <div style="height:100%;background:<?= $gradient ?>;display:flex;align-items:center;justify-content:center;">
                      <i class="bi <?= $bg_ikon ?> text-white" style="font-size:5rem;opacity:.25;"></i>
                    </div>
                  <?php endif; ?>
                  <!-- Kategori label overlay -->
                  <span class="position-absolute top-0 start-0 m-2 badge"
                    style="background:rgba(0,0,0,.55);backdrop-filter:blur(4px);font-size:.72rem;border-radius:6px;">
                    <i class="bi <?= $ikon_cls ?> me-1 <?= $teks_cls ?>"></i>
                    <span class="text-white"><?= $label ?></span>
                  </span>
                </div>

                <!-- Body -->
                <div class="card-body d-flex flex-column p-4">
                  <!-- Judul -->
                  <h6 class="fw-bold mb-2" style="font-size:.95rem;line-height:1.45;color:var(--kai-dark);">
                    <?= htmlspecialchars($b['judul']) ?>
                  </h6>

                  <!-- Ringkasan -->
                  <p class="text-muted mb-3 flex-grow-1" style="font-size:.83rem;line-height:1.7;">
                    <?= htmlspecialchars(mb_strimwidth($b['ringkasan'] ?? strip_tags($b['isi']), 0, 115, '…')) ?>
                  </p>

                  <!-- Footer -->
                  <div class="d-flex align-items-center justify-content-between pt-2"
                    style="border-top:1px solid #f0f0f0;">
                    <small class="text-muted" style="font-size:.75rem;">
                      <i class="bi bi-calendar3 me-1"></i>
                      <?= date('d F Y', strtotime($b['tgl_tayang'])) ?>
                    </small>
                    <a href="detail_berita.php?id=<?= $b['id'] ?>"
                      class="btn btn-sm btn-kai-red"
                      style="font-size:.78rem;padding:.3rem .85rem;border-radius:8px;">
                      Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <div class="col-12 text-center text-muted py-4 fade-up">
          <i class="bi bi-newspaper" style="font-size:2.5rem;opacity:.3;"></i>
          <p class="mt-2 mb-0">Belum ada berita terbaru</p>
        </div>
      <?php endif; ?>

    </div>
  </section>


  <!-- ── FAQ ── -->
  <section class="py-5 faq-section" id="faq">
    <div class="container">
      <div class="fade-up text-center mb-5">
        <h2 class="section-heading center">Pertanyaan Umum (FAQ)</h2>
        <p class="text-muted mt-3" style="font-size:.93rem;">Jawaban atas pertanyaan yang sering diajukan seputar layanan <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?></p>
      </div>
      <div class="row justify-content-center fade-up">
        <div class="col-lg-9">
          <?php if (empty($faq_list)): ?>
            <p class="text-center text-muted">Belum ada FAQ tersedia</p>
          <?php else: ?>
            <div class="accordion accordion-kai" id="faqAccordion">
              <?php foreach ($faq_list as $i => $faq): ?>
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?>"
                      type="button" data-bs-toggle="collapse"
                      data-bs-target="#faqItem<?= $faq['id'] ?>">
                      <?= htmlspecialchars($faq['pertanyaan']) ?>
                    </button>
                  </h2>
                  <div id="faqItem<?= $faq['id'] ?>"
                    class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                    data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                      <?= nl2br(htmlspecialchars($faq['jawaban'])) ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── FOOTER ── -->
  <footer class="footer pt-5 pb-3">
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-train-front-fill text-danger fs-4"></i>
            <span class="display-font text-white fs-5">KAI <span style="color:var(--kai-gold)"><?= htmlspecialchars($info['kode_stasiun'] === 'PLN' ? 'Perlanaan' : ($info['nama_stasiun'] ?? 'Perlanaan')) ?></span></span>
          </div>
          <p>Portal resmi informasi layanan <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?>. Hadir untuk masyarakat <?= htmlspecialchars($info['kabupaten'] ?? 'Serdang Bedagai') ?> dan sekitarnya.</p>
          <p style="color:rgba(255,255,255,.3);font-size:.78rem;">Bagian dari PT Kereta Api Indonesia (Persero) — <?= htmlspecialchars($info['divre'] ?? 'Divre I Sumatera Utara & Aceh') ?></p>
        </div>
        <div class="col-6 col-md-2">
          <h6>Layanan</h6>
          <a href="#jadwal">Jadwal Kereta</a>
          <a href="#harga">Harga Tiket</a>
          <a href="#stasiun">Info Stasiun</a>
          <a href="#berita">Berita & Promo</a>
          <a href="#faq">FAQ</a>
        </div>
        <div class="col-6 col-md-2">
          <h6>KAI Group</h6>
          <a href="https://kai.id" target="_blank">KAI.id</a>
          <a href="#">KAI Access (App)</a>
          <a href="#">KAI Commuter</a>
          <a href="#">KAI Wisata</a>
          <a href="#">KAI Logistik</a>
        </div>
        <div class="col-md-4">
          <h6>Kontak &amp; Darurat</h6>
          <?php if ($info['telepon']): ?>
            <p class="mb-1"><i class="bi bi-telephone me-2" style="color:var(--kai-gold);"></i><?= htmlspecialchars($info['telepon']) ?></p>
          <?php endif; ?>
          <?php if (!empty($pengaturan['call_center'])): ?>
            <p class="mb-1"><i class="bi bi-headset me-2" style="color:var(--kai-gold);"></i>Call Center: <strong style="color:#fff;"><?= htmlspecialchars($pengaturan['call_center']) ?></strong></p>
          <?php endif; ?>
          <?php if (!empty($pengaturan['whatsapp_cs'])): ?>
            <p class="mb-1"><i class="bi bi-whatsapp me-2" style="color:var(--kai-gold);"></i><?= htmlspecialchars($pengaturan['whatsapp_cs']) ?></p>
          <?php endif; ?>
          <?php if ($info['email']): ?>
            <p class="mb-3"><i class="bi bi-envelope me-2" style="color:var(--kai-gold);"></i><?= htmlspecialchars($info['email']) ?></p>
          <?php endif; ?>
          <div class="d-flex gap-2">
            <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border-radius:6px;"><i class="bi bi-facebook"></i></a>
            <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border-radius:6px;"><i class="bi bi-twitter-x"></i></a>
            <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border-radius:6px;"><i class="bi bi-instagram"></i></a>
            <a href="#" class="btn btn-sm" style="background:rgba(255,255,255,.1);color:#fff;border-radius:6px;"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
      <hr style="border-color:rgba(255,255,255,.1);">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <p class="mb-0" style="font-size:.8rem;">© <?= date('Y') ?> PT Kereta Api Indonesia (Persero) – <?= htmlspecialchars($info['nama_stasiun'] ?? 'Stasiun Perlanaan') ?>. Hak Cipta Dilindungi.</p>
        <div class="d-flex align-items-center gap-3">
          <p class="mb-0" style="font-size:.78rem;color:rgba(255,255,255,.25);">Informasi bersifat ilustratif untuk keperluan akademik.</p>
          <a href="login.php"
            style="font-size:.75rem;color:rgba(255,255,255,.2);text-decoration:none;transition:color .2s;"
            onmouseenter="this.style.color='rgba(255,255,255,.55)'"
            onmouseleave="this.style.color='rgba(255,255,255,.2)'">
            <i class="bi bi-person-lock me-1"></i>Portal Petugas
          </a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Back to Top -->
  <button id="backTop" title="Kembali ke atas"><i class="bi bi-arrow-up"></i></button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/landingpage/script.js"></script>
  <script>
    // ── Filter Keberangkatan ──
    function filterJadwalBerangkat() {
      const tujuan = document.getElementById('filterTujuan').value.toLowerCase();
      const hari = document.getElementById('filterHariBerangkat').value.toLowerCase();
      document.querySelectorAll('#tblKeberangkatan tr').forEach(row => {
        const okTujuan = !tujuan || (row.dataset.tujuan || '').toLowerCase().includes(tujuan);
        const okHari = !hari || (row.dataset.hari || '').toLowerCase().includes(hari);
        row.style.display = (okTujuan && okHari) ? '' : 'none';
      });
    }

    function resetFilterBerangkat() {
      document.getElementById('filterTujuan').value = '';
      document.getElementById('filterHariBerangkat').value = '';
      document.querySelectorAll('#tblKeberangkatan tr').forEach(r => r.style.display = '');
    }

    // ── Filter Kedatangan ──
    function filterJadwalDatang() {
      const asal = document.getElementById('filterAsal').value.toLowerCase();
      const hari = document.getElementById('filterHariDatang').value.toLowerCase();
      document.querySelectorAll('#tblKedatangan tr').forEach(row => {
        const okAsal = !asal || (row.dataset.asal || '').toLowerCase().includes(asal);
        const okHari = !hari || (row.dataset.hari || '').toLowerCase().includes(hari);
        row.style.display = (okAsal && okHari) ? '' : 'none';
      });
    }

    function resetFilterDatang() {
      document.getElementById('filterAsal').value = '';
      document.getElementById('filterHariDatang').value = '';
      document.querySelectorAll('#tblKedatangan tr').forEach(r => r.style.display = '');
    }

    // ── Tampilkan filter sesuai tab aktif ──
    document.querySelectorAll('[data-bs-target="#keberangkatan"], [data-bs-target="#kedatangan"]')
      .forEach(btn => {
        btn.addEventListener('shown.bs.tab', function() {
          const isBerangkat = this.dataset.bsTarget === '#keberangkatan';
          document.getElementById('filterKeberangkatan').classList.toggle('d-none', !isBerangkat);
          document.getElementById('filterKedatangan').classList.toggle('d-none', isBerangkat);
          // reset kedua filter saat ganti tab
          resetFilterBerangkat();
          resetFilterDatang();
        });
      });
  </script>
</body>

</html>