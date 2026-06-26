<?php
include '../config/koneksi.php';

// Handle Add Quest
if (isset($_POST['tambah_quest'])) {
    $nama_quest = mysqli_real_escape_string($koneksi, $_POST['nama_quest']);
    $poin = (int)$_POST['poin'];
    
    if (!empty(trim($nama_quest)) && $poin > 0) {
        $query = "INSERT INTO quest (nama_quest, poin, status) VALUES ('$nama_quest', $poin, 'Belum')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?toast=quest_added");
            exit;
        }
    }
}

// Handle Delete Quest
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $query = "DELETE FROM quest WHERE id_quest = '$id_hapus'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?toast=quest_deleted");
        exit;
    }
}

// Fetch all quests
$quests = mysqli_query($koneksi, "SELECT * FROM quest ORDER BY id_quest DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Quest - LifeQuest</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Global CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

    <div class="container" style="max-width: 900px;">
        <!-- Top Bar -->
        <div class="topbar">
            <div style="display: flex; gap: 10px;">
                <a href="../index.php" class="btn btn-secondary">&larr; Kembali ke Dashboard</a>
                <button id="theme-toggle" class="theme-toggle-btn" title="Ganti Tema"></button>
            </div>
        </div>

        <!-- Header -->
        <header style="padding-top: 10px; padding-bottom: 20px;">
            <h1>Kelola Quest Produktivitas</h1>
            <p>Tambahkan quest produktivitas harian Anda sendiri untuk mendapatkan XP tambahan</p>
        </header>

        <!-- Two Column Layout -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 20px;">
            <!-- Column 1: Add Quest -->
            <div class="glass-card">
                <h3 style="font-family: var(--font-title); margin-bottom: 15px; color: var(--color-primary);">Tambah Quest Baru</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="nama_quest">Nama/Deskripsi Quest <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="nama_quest" name="nama_quest" required placeholder="Contoh: Belajar bahasa Inggris 10 menit" />
                    </div>
                    <div class="form-group">
                        <label for="poin">XP / Poin Reward <span style="color: #e74c3c;">*</span></label>
                        <input type="number" id="poin" name="poin" required min="5" max="100" value="20" />
                    </div>
                    <button type="submit" name="tambah_quest" class="btn" style="width: 100%;">
                        Tambah Quest
                    </button>
                </form>
            </div>

            <!-- Column 2: Quests List -->
            <div class="glass-card">
                <h3 style="font-family: var(--font-title); margin-bottom: 15px; color: var(--color-primary);">Daftar Quest</h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Quest</th>
                                <th>Reward XP</th>
                                <th style="text-align: right; width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($quests) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($quests)) { ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_quest']) ?></td>
                                        <td>
                                            <span style="color: #f1c40f; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                <?= $row['poin'] ?> XP
                                            </span>
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="index.php?hapus=<?= $row['id_quest'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus quest ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 25px; color: var(--color-text-muted);">
                                        Belum ada quest. Silakan buat quest pertama Anda di atas!
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Utilities -->
    <script src="../assets/js/main.js"></script>
    
    <?php if (isset($_GET['toast'])) { ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const type = "<?= htmlspecialchars($_GET['toast']) ?>";
                if(type === "quest_added") showToast("Quest baru berhasil ditambahkan!", "success");
                if(type === "quest_deleted") showToast("Quest berhasil dihapus!", "error");
            });
        </script>
    <?php } ?>
</body>
</html>
