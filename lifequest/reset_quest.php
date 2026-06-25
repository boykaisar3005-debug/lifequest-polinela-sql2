<?php

include 'config/koneksi.php';

mysqli_query(
$koneksi,
"UPDATE quest SET status='Belum'"
);

header("Location:index.php");
exit;

?>