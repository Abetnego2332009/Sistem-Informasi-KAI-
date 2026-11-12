<?php 
$host = "localhost";
$user = "root";
$pass = "";
$database = "kai_perlanaan";

$koneksi = mysqli_connect($host, $user, $pass, $database);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


$base_url = "http://localhost/KAI"; 



