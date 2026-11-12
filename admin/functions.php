<?php
// File: admin/functions.php

/**
 * Mendapatkan URL foto profil dengan fallback ke default
 * @param string|null $foto Nama file foto dari database
 * @param string $base_url Base URL aplikasi (misal '' atau '/kai')
 * @return string URL foto (profile atau default)
 */
function getFotoAdmin($foto, $base_url = '') {
    if (!empty($foto)) {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/admin/' . $foto;
        if (file_exists($path)) {
            return $base_url . '/uploads/admin/' . $foto;
        }
    }
    return $base_url . '/assets/assets/img/defauld.png';
}
?>