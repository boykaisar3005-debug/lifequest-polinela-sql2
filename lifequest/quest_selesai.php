<?php

include 'config/koneksi.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Get quest details to add points to user XP
    $q_query = mysqli_query($koneksi, "SELECT poin FROM quest WHERE id_quest='$id'");
    if ($quest = mysqli_fetch_assoc($q_query)) {
        $poin = (int)$quest['poin'];
        
        // Mark quest as completed
        mysqli_query($koneksi, "UPDATE quest SET status='Selesai' WHERE id_quest='$id'");
        
        // Add quest points to User XP
        mysqli_query($koneksi, "INSERT INTO users (id_user, username, xp) 
                                 VALUES (1, 'Chaesaar', $poin) 
                                 ON DUPLICATE KEY UPDATE xp = xp + $poin");
    }
}

header("Location: index.php?toast=completed");
exit;
?>