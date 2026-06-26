<?php

include 'config/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Update task status to Selesai
    mysqli_query(
        $koneksi,
        "UPDATE tugas SET status='Selesai' WHERE id_tugas='$id'"
    );

    // Increase User XP (use upsert to handle case where user is not created yet)
    mysqli_query(
        $koneksi,
        "INSERT INTO users (id_user, username, xp) 
         VALUES (1, 'Chaesaar', 10) 
         ON DUPLICATE KEY UPDATE xp = xp + 10"
    );
}

// Handle conditional redirect target based on calling page
if (isset($_GET['from']) && $_GET['from'] === 'list') {
    header("Location: tugas/index.php?toast=task_completed");
} else {
    header("Location: index.php?toast=task_completed");
}
exit;
?>
