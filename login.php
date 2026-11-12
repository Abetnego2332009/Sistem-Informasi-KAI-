<?php 
session_start();
require_once __DIR__ . '/koneksi.php';



// Kalau sudah login, cegah akses ke login
if (isset($_SESSION['nama']) && isset($_SESSION['role'])) {
  echo "<script>
  alert('Anda sudah login sebagai {$_SESSION['nama']} ({$_SESSION['role']})');
  window.location = 'dashboard.php';
  </script>";
  exit;
  }
  ?>


<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · InvestSmart</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
      rel="icon"
      href="<?= $base_url ?>/assets/assets/img/gambarKai.png"
      type="image/x-icon"
    />
    <style>
      :root {
        --gold: #D4A017;
        --gold-light: #F7D774;
        --gold-dark: #B8860B;
        
        --dark: #F4F7FB;
        --dark-2: #FFFFFF;
        --dark-3: #EEF3FA;
        --dark-card: rgba(255,255,255,0.88);
        
        --text-primary: #1E293B;
        --text-secondary: #475569;
        --text-muted: #64748B;
        
        --accent-green: #16A34A;
        --accent-red: #DC2626;
        
        --border: rgba(212,160,23,0.25);
        --border-light: rgba(15,23,42,0.08);
        
        --radius: 12px;
        --radius-lg: 20px;
        
        --shadow: 0 8px 32px rgba(15,23,42,0.08);
        --shadow-gold: 0 8px 24px rgba(212,160,23,0.18);
      }
      
      *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
      
    body {
  font-family: 'DM Sans', sans-serif;
  background:
    linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
  color: var(--text-primary);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

    /* Noise */
    body::before {
      content: '';
      position: fixed; inset: 0;
      background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
      pointer-events: none; z-index: 9999; opacity: 0.4;
    }

    /* Grid BG */
    .bg-grid {
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(201,168,76,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,168,76,0.03) 1px, transparent 1px);
      background-size: 60px 60px;
      pointer-events: none;
    }

    /* Gold radial center glow */
   .bg-glow {
  position: fixed;
  width: 700px;
  height: 700px;

  top: 50%;
  left: 50%;

  transform: translate(-50%, -50%);

  background: radial-gradient(
    circle,
    rgba(250,204,21,0.12) 0%,
    transparent 70%
  );

  border-radius: 50%;
  pointer-events: none;

  animation: pulseGlow 6s ease-in-out infinite;
}
    @keyframes pulseGlow {
      0%,100% { transform: translate(-50%,-50%) scale(1); opacity: 0.8; }
      50%      { transform: translate(-50%,-50%) scale(1.12); opacity: 1; }
    }

    /* Corner brackets */
    .corner {
      position: fixed;
      width: 100px; height: 100px;
      pointer-events: none;
      opacity: 0.3;
    }
    .c-tl { top: 20px; left: 20px; border-top: 1px solid var(--gold); border-left: 1px solid var(--gold); }
    .c-tr { top: 20px; right: 20px; border-top: 1px solid var(--gold); border-right: 1px solid var(--gold); }
    .c-bl { bottom: 20px; left: 20px; border-bottom: 1px solid var(--gold); border-left: 1px solid var(--gold); }
    .c-br { bottom: 20px; right: 20px; border-bottom: 1px solid var(--gold); border-right: 1px solid var(--gold); }

    /* ========== ADMIN CARD ========== */
    .admin-wrap {
      position: relative; z-index: 1;
      width: 100%;
      max-width: 440px;
      padding: 20px;
    }

    .admin-card {
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(24px);
  -webkit-backdrop-filter: blur(24px);

  border: 1px solid rgba(212,160,23,0.18);
  border-radius: var(--radius-lg);

  padding: 48px 44px;

  box-shadow:
    0 10px 40px rgba(15,23,42,0.08),
    0 0 30px rgba(212,160,23,0.08);

  position: relative;
  overflow: hidden;
}

    /* gold top accent line */
    .admin-card::before {
      content: '';
      position: absolute; top: 0; left: 0; right: 0; height: 2px;
      background: linear-gradient(90deg, transparent 0%, var(--gold-dark) 20%, var(--gold) 50%, var(--gold-light) 70%, transparent 100%);
    }

    /* inner glow corner */
    .admin-card::after {
      content: '';
      position: absolute; top: 0; left: 0;
      width: 200px; height: 200px;
      background: radial-gradient(circle at 0 0, rgba(201,168,76,0.06), transparent 70%);
      pointer-events: none;
    }

    /* shield icon */
    .shield-wrap {
      display: flex; justify-content: center;
      margin-bottom: 28px;
    }
    .shield-icon {
      width: 70px; height: 70px;
      background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.04));
      border: 1px solid var(--border);
      border-radius: 18px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; color: var(--gold);
      box-shadow: var(--shadow-gold);
      position: relative;
      animation: shieldPulse 4s ease-in-out infinite;
    }
    .shield-icon::before {
      content: '';
      position: absolute; inset: -5px;
      border-radius: 22px;
      border: 1px solid rgba(201,168,76,0.12);
    }
    .shield-icon::after {
      content: '';
      position: absolute; inset: -10px;
      border-radius: 26px;
      border: 1px solid rgba(201,168,76,0.06);
    }
    @keyframes shieldPulse {
      0%,100% { box-shadow: var(--shadow-gold); }
      50%      { box-shadow: 0 8px 40px rgba(201,168,76,0.35); }
    }

    /* header text */
    .admin-header { text-align: center; margin-bottom: 32px; }
    .badge-pill {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: 10px; font-weight: 600; letter-spacing: 3px;
      text-transform: uppercase; color: var(--gold);
      background: rgba(201,168,76,0.1);
      border: 1px solid var(--border);
      padding: 5px 14px; border-radius: 50px;
      margin-bottom: 14px;
    }
    .badge-dot {
      width: 5px; height: 5px; border-radius: 50%;
      background: var(--gold);
      animation: pulseDot 2s ease-in-out infinite;
    }
    @keyframes pulseDot {
      0%,100% { opacity: 1; } 50% { opacity: 0.35; }
    }
    .admin-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem; margin-bottom: 6px;
    }
    .admin-sub { font-size: 0.8rem; color: var(--text-muted); }

    /* sys info strip */
    .sys-strip {
      display: flex; gap: 8px;
      margin-bottom: 28px;
    }
    .sys-chip {
  flex: 1;
  text-align: center;

  background: #FFFFFF;
  border: 1px solid rgba(212,160,23,0.15);

  border-radius: 9px;
  padding: 10px 8px;
}
    .sys-val {
      font-size: 1.05rem; font-weight: 700;
      color: var(--gold); line-height: 1;
    }
    .sys-lbl {
      font-size: 0.65rem; color: var(--text-muted);
      text-transform: uppercase; letter-spacing: 0.5px;
      margin-top: 3px;
    }

    /* error msg */
    .msg-box {
      display: none; align-items: center; gap: 10px;
      padding: 12px 16px; border-radius: var(--radius);
      font-size: 0.82rem; margin-bottom: 20px;
    }
    .msg-box.error {
      background: rgba(249, 249, 249, 0.1);
      border: 1px solid rgba(248,81,73,0.3);
      color: #F85149;
    }
    .msg-box.show { display: flex; }

    /* form */
    .fg { margin-bottom: 18px; }
    .flabel {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.75rem; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.5px;
      color: var(--text-secondary); margin-bottom: 8px;
    }
    .flabel i { font-size: 0.7rem; }
    .input-wrap { position: relative; }
    .ico-left {
      position: absolute; left: 14px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted); font-size: 0.9rem;
      pointer-events: none; transition: color 0.2s;
    }
    .ico-right {
      position: absolute; right: 14px; top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted); font-size: 0.85rem;
      cursor: pointer; transition: color 0.2s;
    }
    .ico-right:hover { color: var(--gold); }
    .input-wrap:focus-within .ico-left { color: var(--gold); }

   .finput {
  width: 100%;
  background: #FFFFFF;
  border: 1px solid #CBD5E1;
  border-radius: var(--radius);

  padding: 13px 14px 13px 42px;

  color: #0F172A;
  font-family: 'DM Sans', sans-serif;
  font-size: 0.9rem;
  outline: none;

  transition: 0.25s;
}

.finput::placeholder {
  color: #94A3B8;
}

.finput:focus {
  border-color: rgba(212,160,23,0.6);
  box-shadow: 0 0 0 4px rgba(212,160,23,0.12);
}

    /* remember */
    .remember-wrap {
      display: flex; align-items: center; gap: 8px;
      margin-top: 14px; margin-bottom: 4px;
      font-size: 0.78rem; color: var(--text-muted);
    }
    .remember-wrap input { accent-color: var(--gold); cursor: pointer; }

    /* submit */
   .btn-submit {
  width: 100%;
  margin-top: 26px;

  background: linear-gradient(
    135deg,
    #FACC15,
    #EAB308,
    #FDE68A
  );

  color: #1E293B;
  border: none;
  border-radius: var(--radius);

  padding: 14px 24px;

  font-family: 'DM Sans', sans-serif;
  font-size: 0.92rem;
  font-weight: 700;

  cursor: pointer;

  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;

  transition: 0.2s;

  box-shadow: 0 10px 25px rgba(234,179,8,0.25);
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(234,179,8,0.35);
}

    /* security notice */
    .sec-notice {
      display: flex; align-items: flex-start; gap: 10px;
      margin-top: 22px; padding: 12px 14px;
      background: rgba(201,168,76,0.04);
      border: 1px solid rgba(201,168,76,0.1);
      border-radius: var(--radius);
      font-size: 0.73rem; color: var(--text-muted);
      line-height: 1.6;
    }
    .sec-notice i { color: var(--gold); font-size: 0.85rem; margin-top: 2px; min-width: 14px; }

    /* back link */
    .back-row {
      text-align: center; margin-top: 24px;
    }
    .back-row a {
      font-size: 0.78rem; color: var(--text-muted);
      text-decoration: none;
      display: inline-flex; align-items: center; gap: 6px;
      transition: color 0.2s;
    }
    .back-row a:hover { color: var(--gold); }

    @media (max-width: 500px) {
      .admin-card { padding: 36px 22px; }
    }
  </style>
</head>
<body>

<div class="bg-grid"></div>
<div class="bg-glow"></div>
<div class="corner c-tl"></div>
<div class="corner c-tr"></div>
<div class="corner c-bl"></div>
<div class="corner c-br"></div>

<div class="admin-wrap">
  <div class="admin-card">

    <!-- Shield -->
    <div class="shield-wrap">
      <div class="shield-icon"><i class="bi bi-shield-lock-fill"></i></div>
    </div>

    <!-- Header -->
    <div class="admin-header">
      <div class="badge-pill"><div class="badge-dot"></div> Panel Administrator</div>
      <h2 class="admin-title">Akses Admin</h2>
      <p class="admin-sub">Masukkan kredensial administrator untuk melanjutkan</p>
    </div>

    <?php 
    
 
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
    
    
    ?>
    <!-- Sys info -->
    <div class="sys-strip">
      <div class="sys-chip"><div class="sys-val"><?= count($jadwal_berangkat) ?></div><div class="sys-lbl">Jadwal Keberangkatan</div></div>
      <div class="sys-chip"><div class="sys-val"><?= count($jadwal_datang) ?></div><div class="sys-lbl">Jadwal Kedatangan</div></div>
      <div class="sys-chip"><div class="sys-val"><?= count($jadwal_berangkat) + count($jadwal_datang) ?></div><div class="sys-lbl">Total Jadwal</div></div>
      <!-- <div class="sys-chip"><div class="sys-val"><span style="color:var(--accent-green); font-size:0.8rem;">●</span> ON</div><div class="sys-lbl">Status</div></div> -->
    </div>

    <!-- Error msg (contoh) -->
    <div class="msg-box error" id="msgError">
      <i class="bi bi-shield-exclamation"></i>
      <span>Email atau password admin tidak valid.</span>
    </div>

    <!-- Form -->
    <form action="proses_login.php" method="POST" autocomplete="off">

      <div class="fg">
        <label class="flabel" for="email">
          <i class="bi bi-person-badge"></i> Email Administrator
        </label>
        <div class="input-wrap">
          <i class="bi bi-envelope ico-left"></i>
          <input type="email" id="email" name="email" class="finput"
            placeholder="admin@investsmart.id" required autofocus>
        </div>
      </div>

      <div class="fg">
        <label class="flabel" for="password">
          <i class="bi bi-key"></i> Password
        </label>
        <div class="input-wrap">
          <i class="bi bi-lock ico-left"></i>
          <input type="password" id="password" name="password" class="finput"
            placeholder="••••••••••••" required>
          <i class="bi bi-eye ico-right" id="togglePass"></i>
        </div>
      </div>

      <label class="remember-wrap">
        <input type="checkbox" name="ingat"> Ingat sesi login ini
      </label>

      <button type="submit" name="login" class="btn-submit">
        <i class="bi bi-shield-lock"></i>
        Masuk ke Dashboard Admin
      </button>
    </form>

    <!-- Security notice -->
    <div class="sec-notice">
      <i class="bi bi-lock-fill"></i>
      <span>Halaman ini hanya untuk administrator yang berwenang. Semua aktivitas login direkam dan dipantau sistem.</span>
    </div>

    <div class="back-row">
      <a href="index.php">
        <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
      </a>
    </div>

  </div>
</div>

<script>
  const togglePass = document.getElementById('togglePass');
  const passInput  = document.getElementById('password');
  togglePass.addEventListener('click', () => {
    const show = passInput.type === 'password';
    passInput.type = show ? 'text' : 'password';
    togglePass.className = show ? 'bi bi-eye-slash ico-right' : 'bi bi-eye ico-right';
  });
</script>
</body>
</html>
