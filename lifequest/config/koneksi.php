<?php

$host = "sql208.infinityfree.com";
$user = "if0_42269133";
$pass = "25TcPTaBzOxwQ";
$db   = "if0_42269133_lifequest";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

?>