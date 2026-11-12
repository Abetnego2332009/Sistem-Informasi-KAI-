<?php

require_once __DIR__ . '/../koneksi.php';

if (!isset($_POST['simpan'])) {
    header('Location: index.php');
    exit;
}

// ── Daftar kunci yang boleh diupdate + sanitasi ──────────────────
$fields = [
    'nama_website'           => 'string',
    'deskripsi_website'      => 'string',
    'email_kontak'           => 'email',
    'telepon_utama'          => 'string',
    'call_center'            => 'string',
    'whatsapp_cs'            => 'string',
    'ticker_aktif'           => 'bool',
    'max_berita_beranda'     => 'int',
    'max_pengumuman_beranda' => 'int',
    'google_maps_embed'      => 'url',
];

$errors = [];

foreach ($fields as $kunci => $tipe) {
    // Ambil nilai dari POST
    switch ($tipe) {
        case 'bool':
            $nilai = isset($_POST[$kunci]) ? '1' : '0';
            break;
        case 'int':
            $nilai = intval($_POST[$kunci] ?? 0);
            if ($nilai < 1 || $nilai > 20) $nilai = 4; // batas wajar
            $nilai = (string)$nilai;
            break;
        case 'email':
            $nilai = filter_var(trim($_POST[$kunci] ?? ''), FILTER_SANITIZE_EMAIL);
            break;
        case 'url':
            $nilai = trim($_POST[$kunci] ?? '');
            // Hanya izinkan URL Google Maps embed
            if (!empty($nilai) && strpos($nilai, 'google.com/maps') === false) {
                $errors[] = "URL Google Maps tidak valid.";
                $nilai = '';
            }
            break;
        default:
            $nilai = trim($_POST[$kunci] ?? '');
            break;
    }

    $nilai_escaped = mysqli_real_escape_string($koneksi, $nilai);
    $kunci_escaped = mysqli_real_escape_string($koneksi, $kunci);

    // UPSERT: update jika ada, insert jika belum ada
    $sql = "INSERT INTO pengaturan_sistem (kunci, nilai)
            VALUES ('$kunci_escaped', '$nilai_escaped')
            ON DUPLICATE KEY UPDATE nilai = '$nilai_escaped'";

    if (!mysqli_query($koneksi, $sql)) {
        $errors[] = "Gagal menyimpan kunci: $kunci";
    }
}

if (!empty($errors)) {
    $pesan = urlencode(implode('; ', $errors));
    header("Location: index.php?error=1&pesan=$pesan");
} else {
    header('Location: index.php?success=1');
}
exit;