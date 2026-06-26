<?php

include 'config/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    mysqli_query(
        $koneksi,
        "DELETE FROM tugas WHERE id_tugas='$id'"
    );
}

// Check where the request came from
if (isset($_GET['from']) && $_GET['from'] === 'list') {
    header("Location: tugas/index.php?toast=task_deleted");
} else {
    header("Location: index.php?toast=task_deleted");
}
exit;
?>
