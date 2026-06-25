<?php

include 'config/koneksi.php';

$id = $_GET['id'];
mysqli_query(
$koneksi,
"UPDATE tugas
SET status='Selesai'
WHERE id_tugas='$id'"
);

mysqli_query(
$koneksi,
"UPDATE users 
SET xp = xp + 10 
WHERE id_user=1"
);
header("Location:index.php");
exit;
?>
