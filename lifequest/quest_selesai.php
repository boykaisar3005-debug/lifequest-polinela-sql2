<?php

include 'config/koneksi.php';

$id = $_GET['id'];

mysqli_query(
$koneksi,
"UPDATE quest
SET status='Selesai'
WHERE id_quest='$id'"
);

header("Location:index.php");
exit;

?>