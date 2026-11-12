<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ERD – Database KAI Perlanaan</title>
  <script src="https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.min.js"></script>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: #0f1117;
      color: #e2e8f0;
      min-height: 100vh;
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #C0392B 0%, #8B1A1A 100%);
      padding: 20px 32px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .header-left { display: flex; align-items: center; gap: 12px; }
    .header-icon {
      width: 40px; height: 40px;
      background: rgba(255,255,255,0.15);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px;
    }
    .header h1 { font-size: 1.2rem; font-weight: 700; color: #fff; }
    .header p  { font-size: 0.78rem; color: rgba(255,255,255,0.7); margin-top: 2px; }
    .badge {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.25);
      color: #fff;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }

    /* ── Legend ── */
    .legend {
      display: flex;
      gap: 24px;
      padding: 12px 32px;
      background: #1a1d2e;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      flex-wrap: wrap;
    }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 0.78rem; color: #94a3b8; }
    .legend-dot {
      width: 10px; height: 10px; border-radius: 3px;
    }

    /* ── Diagram Container ── */
    .diagram-wrapper {
      padding: 32px;
      overflow-x: auto;
    }
    .diagram-card {
      background: #1a1d2e;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 16px;
      padding: 32px;
      min-width: 900px;
    }
    #erd-diagram svg {
      max-width: 100%;
      height: auto;
    }

    /* ── Relasi Info Cards ── */
    .info-section {
      padding: 0 32px 32px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 16px;
    }
    .info-card {
      background: #1a1d2e;
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 16px 20px;
    }
    .info-card h3 {
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .dot-red    { background: #e74c3c; }
    .dot-blue   { background: #3498db; }
    .dot-green  { background: #2ecc71; }
    .dot-yellow { background: #f1c40f; }
    .info-card ul { list-style: none; }
    .info-card ul li {
      font-size: 0.8rem;
      color: #94a3b8;
      padding: 4px 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .info-card ul li:last-child { border-bottom: none; }
    .info-card ul li code {
      background: rgba(255,255,255,0.08);
      padding: 1px 6px;
      border-radius: 4px;
      font-size: 0.75rem;
      color: #a78bfa;
      font-family: 'Courier New', monospace;
    }
    .arrow-sym { color: #e74c3c; font-weight: bold; }

    /* ── Footer ── */
    footer {
      text-align: center;
      padding: 20px;
      color: rgba(255,255,255,0.2);
      font-size: 0.75rem;
      border-top: 1px solid rgba(255,255,255,0.05);
    }
  </style>
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="header-left">
    <div class="header-icon">🚂</div>
    <div>
      <h1>Entity Relationship Diagram</h1>
      <p>Database KAI Perlanaan — kai_perlanaan</p>
    </div>
  </div>
  <div class="badge">15 TABEL</div>
</div>

<!-- Legend -->
<div class="legend">
  <div class="legend-item"><div class="legend-dot" style="background:#e74c3c"></div> Tabel Utama (admin, kereta, stasiun)</div>
  <div class="legend-item"><div class="legend-dot" style="background:#3498db"></div> Tabel Transaksi (jadwal, harga)</div>
  <div class="legend-item"><div class="legend-dot" style="background:#2ecc71"></div> Tabel Konten (berita, faq, pengumuman)</div>
  <div class="legend-item"><div class="legend-dot" style="background:#f1c40f"></div> Tabel Sistem (pengaturan, statistik)</div>
  <div class="legend-item" style="margin-left:auto; color:#64748b;">||--o{ = One to Many &nbsp;|&nbsp; FK = Foreign Key</div>
</div>

<!-- Diagram -->
<div class="diagram-wrapper">
  <div class="diagram-card">
    <div id="erd-diagram" class="mermaid">
erDiagram
  admin {
    int id PK
    varchar nama
    varchar username
    varchar email
    varchar password
    enum role
    datetime last_login
    datetime created_at
  }

  info_stasiun {
    int id PK
    varchar nama_stasiun
    varchar kode_stasiun
    enum kelas_stasiun
    text alamat
    varchar kabupaten
    varchar provinsi
    decimal latitude
    decimal longitude
    varchar telepon
    varchar email
    int updated_by FK
  }

  jam_operasional {
    int id PK
    varchar layanan
    time jam_buka
    time jam_tutup
    varchar keterangan
    tinyint is_active
    tinyint urutan
    int admin_id FK
  }

  fasilitas {
    int id PK
    varchar nama
    varchar ikon
    text deskripsi
    tinyint is_active
    tinyint urutan
    int admin_id FK
  }

  kereta {
    int id PK
    varchar nomor_ka
    varchar nama_ka
    enum jenis
    varchar keterangan
    tinyint is_active
    datetime created_at
  }

  stasiun_rute {
    int id PK
    varchar kode
    varchar nama
    varchar kota
    tinyint is_active
  }

  jadwal_kereta {
    int id PK
    int kereta_id FK
    enum tipe
    int stasiun_asal_id FK
    int stasiun_tujuan_id FK
    varchar via
    time jam
    enum hari_operasi
    enum status
    smallint keterlambatan_menit
    tinyint is_active
  }

  kelas_tiket {
    int id PK
    enum nama_kelas
    text deskripsi
    varchar warna_badge
  }

  harga_tiket {
    int id PK
    int stasiun_asal_id FK
    int stasiun_tujuan_id FK
    int kelas_id FK
    int harga
    tinyint is_active
    datetime created_at
  }

  tarif_khusus {
    int id PK
    varchar kategori
    varchar ikon_emoji
    tinyint persen_diskon
    text syarat
    varchar keterangan
    tinyint is_active
    int admin_id FK
  }

  pengumuman {
    int id PK
    varchar judul
    text isi
    enum tipe
    enum prioritas
    date tgl_mulai
    date tgl_selesai
    tinyint is_active
    int admin_id FK
    datetime created_at
  }

  berita {
    int id PK
    varchar judul
    varchar slug
    text ringkasan
    longtext isi
    enum kategori
    date tgl_tayang
    tinyint is_active
    int views
    int admin_id FK
  }

  faq {
    int id PK
    text pertanyaan
    text jawaban
    varchar kategori
    tinyint urutan
    tinyint is_active
    int admin_id FK
  }

  statistik_pengunjung {
    int id PK
    date tanggal
    int total_kunjungan
    int halaman_beranda
    int halaman_jadwal
    int halaman_harga
    int halaman_stasiun
    int halaman_berita
    int halaman_faq
  }

  pengaturan_sistem {
    int id PK
    varchar kunci
    text nilai
    varchar label
  }

  admin ||--o{ info_stasiun : "updated_by (SET NULL)"
  admin ||--o{ jam_operasional : "admin_id (SET NULL)"
  admin ||--o{ fasilitas : "admin_id (SET NULL)"
  admin ||--o{ tarif_khusus : "admin_id (SET NULL)"
  admin ||--o{ pengumuman : "admin_id (RESTRICT)"
  admin ||--o{ berita : "admin_id (RESTRICT)"
  admin ||--o{ faq : "admin_id (RESTRICT)"

  kereta ||--o{ jadwal_kereta : "kereta_id (CASCADE)"
  stasiun_rute ||--o{ jadwal_kereta : "stasiun_asal_id"
  stasiun_rute ||--o{ jadwal_kereta : "stasiun_tujuan_id"

  stasiun_rute ||--o{ harga_tiket : "stasiun_asal_id (RESTRICT)"
  stasiun_rute ||--o{ harga_tiket : "stasiun_tujuan_id (RESTRICT)"
  kelas_tiket  ||--o{ harga_tiket : "kelas_id (RESTRICT)"
    </div>
  </div>
</div>

<!-- Relasi Info Cards -->
<div class="info-section">
  <div class="info-card">
    <h3><span class="dot dot-red"></span> Relasi Admin (Audit Trail)</h3>
    <ul>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>info_stasiun</code> via updated_by</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>jam_operasional</code> via admin_id</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>fasilitas</code> via admin_id</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>tarif_khusus</code> via admin_id</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>pengumuman</code> via admin_id</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>berita</code> via admin_id</li>
      <li><span class="arrow-sym">→</span> <code>admin</code> ke <code>faq</code> via admin_id</li>
    </ul>
  </div>
  <div class="info-card">
    <h3><span class="dot dot-blue"></span> Relasi Jadwal Kereta</h3>
    <ul>
      <li><span class="arrow-sym">→</span> <code>kereta</code> ke <code>jadwal_kereta</code> ON CASCADE</li>
      <li><span class="arrow-sym">→</span> <code>stasiun_rute</code> ke <code>jadwal_kereta</code> (asal)</li>
      <li><span class="arrow-sym">→</span> <code>stasiun_rute</code> ke <code>jadwal_kereta</code> (tujuan)</li>
      <li><span class="arrow-sym">⚠</span> <code>jadwal_kereta</code> belum relasi ke <code>kelas_tiket</code></li>
    </ul>
  </div>
  <div class="info-card">
    <h3><span class="dot dot-green"></span> Relasi Harga Tiket</h3>
    <ul>
      <li><span class="arrow-sym">→</span> <code>stasiun_rute</code> ke <code>harga_tiket</code> (asal)</li>
      <li><span class="arrow-sym">→</span> <code>stasiun_rute</code> ke <code>harga_tiket</code> (tujuan)</li>
      <li><span class="arrow-sym">→</span> <code>kelas_tiket</code> ke <code>harga_tiket</code> via kelas_id</li>
      <li><span class="arrow-sym">✓</span> UNIQUE KEY (asal, tujuan, kelas) — no duplikat</li>
    </ul>
  </div>
  <div class="info-card">
    <h3><span class="dot dot-yellow"></span> Tabel Mandiri (No FK)</h3>
    <ul>
      <li><span class="arrow-sym">—</span> <code>statistik_pengunjung</code> — analytics harian</li>
      <li><span class="arrow-sym">—</span> <code>pengaturan_sistem</code> — key-value config</li>
      <li><span class="arrow-sym">—</span> <code>kelas_tiket</code> — parent only, tidak punya FK</li>
      <li><span class="arrow-sym">—</span> <code>kereta</code> — parent only, tidak punya FK</li>
    </ul>
  </div>
</div>

<footer>
  ERD Database KAI Perlanaan &nbsp;•&nbsp; Generated for Akademik &nbsp;•&nbsp; PT Kereta Api Indonesia (Persero) – Divre I Sumatera Utara &amp; Aceh
</footer>

<script>
  mermaid.initialize({
    startOnLoad: true,
    theme: 'dark',
    er: {
      diagramPadding: 30,
      layoutDirection: 'TB',
      minEntityWidth: 100,
      minEntityHeight: 75,
      entityPadding: 15,
      useMaxWidth: true,
    },
    themeVariables: {
      darkMode: true,
      fontSize: '13px',
      fontFamily: "'Segoe UI', sans-serif",
      lineColor: '#64748b',
      textColor: '#e2e8f0',
      primaryColor: '#1e2a3a',
      primaryBorderColor: '#3b82f6',
      primaryTextColor: '#e2e8f0',
      secondaryColor: '#1a2535',
      tertiaryColor: '#1e293b',
    }
  });
</script>

</body>
</html>