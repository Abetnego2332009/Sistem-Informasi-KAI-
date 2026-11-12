<?php

/**
 * proses_faq.php
 * ─────────────────────────────────────────────────────────────
 * Menangani seluruh logika CRUD untuk tabel `faq`:
 *   POST aksi=tambah  → INSERT
 *   POST aksi=edit    → UPDATE
 *   GET  aksi=hapus   → DELETE (soft-check: pastikan data ada)
 * ─────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/../koneksi.php';
session_start();

// ── Pastikan ada sesi admin yang login ───────────────────────────────────────
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

$admin_id = (int) $_SESSION['id'];

// ── Ambil aksi ───────────────────────────────────────────────────────────────
$aksi = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = trim($_POST['aksi'] ?? '');
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $aksi = trim($_GET['aksi'] ?? '');
}

// ════════════════════════════════════════════════════════════════════════════
//  HELPER: redirect dengan pesan sukses / error
// ════════════════════════════════════════════════════════════════════════════
function redirectSuccess(string $msg, string $to = ''): void
{
    
    global $base_url;
    if ($to === '') {
        $to = $base_url . '/faq';
    }
    $_SESSION['success'] = $msg;
    header("Location: $to");
    exit;
}

function redirectError(string $msg, string $to = ''): void
{
    global $base_url;

    if ($to === '') {
        $to = $base_url . '/faq';
    }

    $_SESSION['error'] = $msg;
    header("Location: $to");
    exit;
}


// ════════════════════════════════════════════════════════════════════════════
//  HELPER: sanitasi & validasi input POST
// ════════════════════════════════════════════════════════════════════════════
/**
 * Mengembalikan array ['data' => [...], 'errors' => [...]]
 * data  → nilai bersih siap pakai
 * errors → daftar pesan kesalahan (kosong = valid)
 */
function validateFaqInput(array $post, mysqli $db): array
{
    $errors = [];
    $data   = [];

    // ── Pertanyaan ────────────────────────────────────────────
    $pertanyaan = trim($post['pertanyaan'] ?? '');
    if ($pertanyaan === '') {
        $errors[] = 'Pertanyaan tidak boleh kosong.';
    } elseif (mb_strlen($pertanyaan) < 10) {
        $errors[] = 'Pertanyaan terlalu pendek (minimal 10 karakter).';
    } elseif (mb_strlen($pertanyaan) > 1000) {
        $errors[] = 'Pertanyaan terlalu panjang (maksimal 1000 karakter).';
    }
    $data['pertanyaan'] = mysqli_real_escape_string($db, $pertanyaan);

    // ── Jawaban ───────────────────────────────────────────────
    $jawaban = trim($post['jawaban'] ?? '');
    if ($jawaban === '') {
        $errors[] = 'Jawaban tidak boleh kosong.';
    } elseif (mb_strlen($jawaban) < 10) {
        $errors[] = 'Jawaban terlalu pendek (minimal 10 karakter).';
    } elseif (mb_strlen($jawaban) > 5000) {
        $errors[] = 'Jawaban terlalu panjang (maksimal 5000 karakter).';
    }
    $data['jawaban'] = mysqli_real_escape_string($db, $jawaban);

    // ── Kategori ──────────────────────────────────────────────
    $allowedKategori = ['Umum', 'Tiket', 'Bagasi', 'Jadwal', 'Fasilitas', 'Kontak'];
    $kategori = trim($post['kategori'] ?? 'Umum');
    if (!in_array($kategori, $allowedKategori, true)) {
        $errors[] = 'Kategori tidak valid.';
        $kategori = 'Umum';
    }
    $data['kategori'] = mysqli_real_escape_string($db, $kategori);

    // ── Urutan ────────────────────────────────────────────────
    $urutan = $post['urutan'] ?? '0';
    if (!ctype_digit((string)$urutan) && !($urutan === '0')) {
        $errors[] = 'Urutan harus berupa angka (0–127).';
        $urutan = 0;
    }
    $urutan = max(0, min(127, (int)$urutan));
    $data['urutan'] = $urutan;

    // ── Status (is_active) ────────────────────────────────────
    $is_active = isset($post['is_active']) ? (int)$post['is_active'] : 1;
    $is_active = in_array($is_active, [0, 1], true) ? $is_active : 1;
    $data['is_active'] = $is_active;

    return ['data' => $data, 'errors' => $errors];
}


// ════════════════════════════════════════════════════════════════════════════
//  AKSI: TAMBAH
// ════════════════════════════════════════════════════════════════════════════
if ($aksi === 'tambah') {

    $validated = validateFaqInput($_POST, $koneksi);

    if (!empty($validated['errors'])) {
        // Simpan old input supaya form tidak kosong
        $_SESSION['old']   = $_POST;
        $_SESSION['error'] = implode('<br>', $validated['errors']);
        header('Location: tambah_faq.php');
        exit;
    }

    $d = $validated['data'];

    $sql = "INSERT INTO faq
                (pertanyaan, jawaban, kategori, urutan, is_active, admin_id, created_at, updated_at)
            VALUES
                ('{$d['pertanyaan']}', '{$d['jawaban']}', '{$d['kategori']}',
                 {$d['urutan']}, {$d['is_active']}, $admin_id,
                 NOW(), NOW())";

    if (mysqli_query($koneksi, $sql)) {
        redirectSuccess('FAQ berhasil ditambahkan.');
    } else {
        $_SESSION['old']   = $_POST;
        $_SESSION['error'] = 'Gagal menyimpan ke database: ' . mysqli_error($koneksi);
        header('Location: tambah_faq.php');
        exit;
    }
}


// ════════════════════════════════════════════════════════════════════════════
//  AKSI: EDIT
// ════════════════════════════════════════════════════════════════════════════
if ($aksi === 'edit') {

    $id = (int)($_POST['id'] ?? 0);

    // Validasi ID
    if ($id <= 0) {
        redirectError('ID FAQ tidak valid.');
    }

    // Pastikan data ada di DB
    $cek = mysqli_query($koneksi, "SELECT id FROM faq WHERE id = $id LIMIT 1");
    if (mysqli_num_rows($cek) === 0) {
        redirectError('Data FAQ tidak ditemukan.');
    }

    $validated = validateFaqInput($_POST, $koneksi);

    if (!empty($validated['errors'])) {
        $_SESSION['old']   = $_POST;
        $_SESSION['error'] = implode('<br>', $validated['errors']);
        header("Location: edit_faq.php?id=$id");
        exit;
    }

    $d = $validated['data'];

    $sql = "UPDATE faq SET
                pertanyaan = '{$d['pertanyaan']}',
                jawaban    = '{$d['jawaban']}',
                kategori   = '{$d['kategori']}',
                urutan     = {$d['urutan']},
                is_active  = {$d['is_active']},
                updated_at = NOW()
            WHERE id = $id
            LIMIT 1";

    if (mysqli_query($koneksi, $sql)) {
        redirectSuccess('FAQ berhasil diperbarui.');
    } else {
        $_SESSION['old']   = $_POST;
        $_SESSION['error'] = 'Gagal memperbarui database: ' . mysqli_error($koneksi);
        header("Location: edit_faq.php?id=$id");
        exit;
    }
}


// ════════════════════════════════════════════════════════════════════════════
//  AKSI: HAPUS
// ════════════════════════════════════════════════════════════════════════════
if ($aksi === 'hapus') {

    $id = (int)($_GET['id'] ?? 0);

    // Validasi ID
    if ($id <= 0) {
        redirectError('ID FAQ tidak valid.');
    }

    // Pastikan data ada di DB sebelum dihapus
    $cek = mysqli_query($koneksi, "SELECT id FROM faq WHERE id = $id LIMIT 1");
    if (mysqli_num_rows($cek) === 0) {
        redirectError('Data FAQ tidak ditemukan atau sudah dihapus.');
    }

    $sql = "DELETE FROM faq WHERE id = $id LIMIT 1";

    if (mysqli_query($koneksi, $sql)) {
        redirectSuccess('FAQ berhasil dihapus.');
    } else {
        redirectError('Gagal menghapus data: ' . mysqli_error($koneksi));
    }
}


// ════════════════════════════════════════════════════════════════════════════
//  AKSI TIDAK DIKENAL
// ════════════════════════════════════════════════════════════════════════════
redirectError('Aksi tidak dikenali.');
