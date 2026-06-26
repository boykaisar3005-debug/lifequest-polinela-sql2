<?php
include 'config/koneksi.php';

// Get User XP & details
$user_query = mysqli_query($koneksi, "SELECT xp, username FROM users WHERE id_user = 1");
if (mysqli_num_rows($user_query) === 0) {
    mysqli_query($koneksi, "INSERT INTO users (id_user, username, xp) VALUES (1, 'Chaesaar', 0)");
    $xp = 0;
    $username = 'Chaesaar';
} else {
    $user = mysqli_fetch_assoc($user_query);
    $xp = (int)$user['xp'];
    $username = htmlspecialchars($user['username']);
}

// Calculate level (100 XP per level)
$level = floor($xp / 100) + 1;
$level_xp = $xp % 100;

// Fetch Statistics
$totalTugas = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tugas"));
$totalSelesai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tugas WHERE status='Selesai'"));
$totalQuest = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM quest"));

$persentase = 0;
if ($totalTugas > 0) {
    $persentase = round(($totalSelesai / $totalTugas) * 100);
}

// Fetch 3 random uncompleted quests for today
$questHariIni = mysqli_query(
    $koneksi,
    "SELECT * FROM quest WHERE status='Belum' ORDER BY RAND() LIMIT 3"
);

// Fetch 5 latest tasks
$tugasTerbaru = mysqli_query(
    $koneksi,
    "SELECT t.*, k.nama_kategori 
     FROM tugas t 
     LEFT JOIN kategori k ON t.id_kategori = k.id_kategori 
     ORDER BY t.id_tugas DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - LifeQuest</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>

    <div class="container">
        <!-- Top Navigation Bar -->
        <div class="topbar">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button id="theme-toggle" class="theme-toggle-btn" title="Ganti Tema"></button>
                <div style="font-weight: 500; font-size: 1.1rem;">
                    Halo, <span style="color: var(--color-primary); font-weight: 700;"><?= $username ?></span>!
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="tugas/index.php" class="btn btn-secondary">Semua Tugas</a>
                <a href="tugas/tambah.php" class="btn">+ Tambah Tugas</a>
            </div>
        </div>

        <!-- Header -->
        <header>
            <h1>LifeQuest</h1>
            <p>Jadilah Versi Dirimu Yang Terbaik Setiap Hari</p>
        </header>

        <!-- Clock Widget -->
        <div class="clock-container">
            <div id="clock">00:00:00</div>
        </div>

        <!-- Main Stats Widgets Row -->
        <div class="widgets-row">
            <!-- XP Circle Widget -->
            <div class="widget-card xp-level-widget">
                <div class="xp-ring-container">
                    <svg class="xp-ring-svg">
                        <circle class="xp-ring-circle-bg" cx="60" cy="60" r="50" />
                        <circle id="xp-ring-fill" class="xp-ring-circle-fill" cx="60" cy="60" r="50" data-percent="<?= $level_xp ?>" />
                    </svg>
                    <div class="xp-ring-text">
                        <span class="lvl-label">Level</span>
                        <span><?= $level ?></span>
                    </div>
                </div>
                <div style="font-size: 0.9rem; font-weight: 500; color: var(--color-text-muted);">
                    XP: <?= $xp ?> (<?= $level_xp ?>/100)
                </div>
            </div>

            <!-- Stats: Total Tasks -->
            <div class="widget-card">
                <div class="widget-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
                <div class="widget-info">
                    <h3>Total Tugas</h3>
                    <div class="number"><?= $totalTugas ?></div>
                </div>
            </div>

            <!-- Stats: Tasks Completed -->
            <div class="widget-card">
                <div class="widget-icon" style="color: #2ecc71; background: rgba(46, 204, 113, 0.1);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="widget-info">
                    <h3>Tugas Selesai</h3>
                    <div class="number" style="color: #2ecc71;"><?= $totalSelesai ?></div>
                </div>
            </div>
        </div>

        <!-- Dashboard Layout Grid -->
        <div class="dashboard-grid">
            
            <!-- Left Side: Quests & Tasks List -->
            <div>
                <!-- Productivity Progress Bar -->
                <div class="glass-card">
                    <h2 style="font-family: var(--font-title); margin-bottom: 10px; color: var(--color-primary);">Progress Produktivitas</h2>
                    <p style="font-size: 0.95rem; color: var(--color-text-muted); margin-bottom: 15px;">
                        <?= $persentase ?>% dari tugas Anda telah diselesaikan hari ini. Tetap semangat!
                    </p>
                    <div class="progress">
                        <div class="progress-fill" style="width: <?= $persentase ?>%"></div>
                    </div>
                </div>

                <!-- Daily Quests Grid -->
                <div class="glass-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                        <h2 style="font-family: var(--font-title); color: var(--color-primary);">Quest Produktivitas Hari Ini</h2>
                        <a href="reset_quest.php" class="btn btn-secondary btn-sm" onclick="return confirm('Reset semua quest menjadi Belum Selesai?')">
                            Reset Quest
                        </a>
                    </div>
                    
                    <div class="quests-grid">
                        <?php if (mysqli_num_rows($questHariIni) > 0) { ?>
                            <?php while ($q = mysqli_fetch_assoc($questHariIni)) { ?>
                                <div class="quest-card">
                                    <div>
                                        <div class="quest-title"><?= htmlspecialchars($q['nama_quest']) ?></div>
                                        <div class="quest-reward">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                            Reward: <?= $q['poin'] ?> Poin XP
                                        </div>
                                    </div>
                                    <div class="quest-actions">
                                        <a href="quest_selesai.php?id=<?= $q['id_quest'] ?>" class="btn btn-success btn-sm" style="width: 100%;">
                                            Selesaikan
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div style="grid-column: 1 / -1; text-align: center; padding: 30px; color: var(--color-text-muted); background: var(--color-surface-solid); border-radius: var(--radius-md); border: 1px dashed var(--color-border);">
                                Semua quest hari ini telah diselesaikan! Tekan tombol "Reset Quest" untuk memulai ulang.
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Latest Tasks Table -->
                <div class="glass-card">
                    <h2 style="font-family: var(--font-title); margin-bottom: 15px; color: var(--color-primary);">Tugas Terbaru</h2>
                    
                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Judul Tugas</th>
                                    <th>Kategori</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($tugasTerbaru) > 0) { ?>
                                    <?php while ($t = mysqli_fetch_assoc($tugasTerbaru)) { ?>
                                        <tr>
                                            <td style="font-weight: 500;"><?= htmlspecialchars($t['judul']) ?></td>
                                            <td>
                                                <span class="badge" style="background: rgba(139, 94, 60, 0.08); color: var(--color-primary);">
                                                    <?= htmlspecialchars($t['nama_kategori'] ?? 'Tanpa Kategori') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $t['deadline'] ? date('d M Y', strtotime($t['deadline'])) : '<span style="color: var(--color-text-muted)">-</span>' ?>
                                            </td>
                                            <td>
                                                <?php if ($t['status'] === 'Selesai') { ?>
                                                    <span class="badge badge-success">Selesai</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-pending">Belum</span>
                                                <?php } ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <div style="display: inline-flex; gap: 8px;">
                                                    <?php if ($t['status'] !== 'Selesai') { ?>
                                                        <a href="selesai.php?id=<?= $t['id_tugas'] ?>" class="btn btn-success btn-sm" title="Selesaikan">
                                                            Selesai
                                                        </a>
                                                    <?php } ?>
                                                    <a href="tugas/edit.php?id=<?= $t['id_tugas'] ?>" class="btn btn-secondary btn-sm" style="padding: 6px 10px;" title="Edit">
                                                        Edit
                                                    </a>
                                                    <a href="hapus.php?id=<?= $t['id_tugas'] ?>" class="btn btn-danger btn-sm" style="padding: 6px 10px;" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" title="Hapus">
                                                        Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 30px; color: var(--color-text-muted);">
                                            Belum ada tugas. Klik "+ Tambah Tugas" untuk membuat tugas pertama Anda!
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Side: Side Panel for Admin Tools -->
            <div>
                <div class="glass-card">
                    <h3 style="font-family: var(--font-title); margin-bottom: 20px; color: var(--color-primary); border-bottom: 1px solid var(--color-border); padding-bottom: 10px;">
                        Menu Pengaturan
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="kategori/index.php" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; gap: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                            Kelola Kategori
                        </a>
                        <a href="quest/index.php" class="btn btn-secondary" style="justify-content: flex-start; text-align: left; gap: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                            Kelola Quest
                        </a>
                    </div>
                </div>
                
                <div class="glass-card" style="font-size: 0.9rem;">
                    <h4 style="font-weight: 600; margin-bottom: 10px;">Petunjuk Leveling:</h4>
                    <p style="color: var(--color-text-muted); margin-bottom: 8px;">
                        🚀 <strong>Tugas:</strong> Selesaikan tugas harian Anda untuk mendapatkan <strong>+10 XP</strong>.
                    </p>
                    <p style="color: var(--color-text-muted);">
                        🎯 <strong>Quest:</strong> Selesaikan quest produktivitas hari ini untuk mendapatkan bonus XP sesuai reward quest tersebut!
                    </p>
                </div>
            </div>

        </div>

        <!-- Footer -->
        <footer>
            LifeQuest Web App &copy; <?= date('Y') ?> | Crafted by <a href="https://github.com/Chaesaar" target="_blank">@Chaesaar_</a>
        </footer>
    </div>

    <!-- Script Utilities -->
    <script src="assets/js/main.js"></script>
    
    <?php if (isset($_GET['toast'])) { ?>
        <script>
            // Show toast based on status parameter
            document.addEventListener("DOMContentLoaded", () => {
                const type = "<?= htmlspecialchars($_GET['toast']) ?>";
                if(type === "completed") showToast("Quest berhasil diselesaikan!", "success");
                if(type === "reset") showToast("Quest hari ini telah di-reset!", "info");
                if(type === "task_completed") showToast("Tugas berhasil diselesaikan! (+10 XP)", "success");
                if(type === "task_deleted") showToast("Tugas berhasil dihapus!", "error");
            });
        </script>
    <?php } ?>
</body>
</html>