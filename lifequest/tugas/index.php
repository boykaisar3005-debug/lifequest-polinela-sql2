<?php
include '../config/koneksi.php';

// Fetch all categories for filter dropdown
$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori");

// Fetch tasks with Category names (using LEFT JOIN so tasks with deleted/null categories still show up)
$data = mysqli_query($koneksi, "
    SELECT
        t.id_tugas,
        t.judul,
        t.deskripsi,
        t.deadline,
        t.status,
        t.id_kategori,
        k.nama_kategori
    FROM tugas t
    LEFT JOIN kategori k ON t.id_kategori = k.id_kategori
    ORDER BY t.id_tugas DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Tugas - LifeQuest</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Global CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

    <div class="container">
        <!-- Top Bar -->
        <div class="topbar">
            <div style="display: flex; gap: 10px;">
                <a href="../index.php" class="btn btn-secondary">&larr; Dashboard</a>
                <button id="theme-toggle" class="theme-toggle-btn" title="Ganti Tema"></button>
            </div>
            <div>
                <a href="tambah.php" class="btn">+ Tambah Tugas Baru</a>
            </div>
        </div>

        <!-- Header -->
        <header style="padding-top: 10px; padding-bottom: 20px;">
            <h1>List Tugas Anda</h1>
            <p>Kelola semua tugas harian dan tingkatkan produktivitas Anda</p>
        </header>

        <!-- Filters Panel -->
        <div class="glass-card">
            <div class="filters-panel">
                <div>
                    <input type="text" id="search-input" placeholder="Cari judul atau deskripsi tugas..." />
                </div>
                <div>
                    <select id="category-filter">
                        <option value="all">Semua Kategori</option>
                        <?php while ($kat = mysqli_fetch_assoc($kategori_list)) { ?>
                            <option value="<?= $kat['id_kategori'] ?>">
                                <?= htmlspecialchars($kat['nama_kategori']) ?>
                            </option>
                        <?php } ?>
                        <option value="none">Tanpa Kategori</option>
                    </select>
                </div>
                <div>
                    <select id="status-filter">
                        <option value="all">Semua Status</option>
                        <option value="Belum Selesai">Belum Selesai</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tasks Grid -->
        <div class="tasks-grid" id="tasks-container">
            <?php if (mysqli_num_rows($data) > 0) { ?>
                <?php while ($row = mysqli_fetch_assoc($data)) { 
                    $catId = $row['id_kategori'] ? $row['id_kategori'] : 'none';
                    $isCompleted = $row['status'] === 'Selesai';
                ?>
                    <div class="task-item-card" 
                         data-title="<?= strtolower(htmlspecialchars($row['judul'])) ?>" 
                         data-desc="<?= strtolower(htmlspecialchars($row['deskripsi'] ?? '')) ?>" 
                         data-category="<?= $catId ?>" 
                         data-status="<?= htmlspecialchars($row['status']) ?>">
                        
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                                <span class="badge" style="background: rgba(139, 94, 60, 0.08); color: var(--color-primary); font-size: 0.75rem;">
                                    <?= htmlspecialchars($row['nama_kategori'] ?? 'Tanpa Kategori') ?>
                                </span>
                                <?php if ($isCompleted) { ?>
                                    <span class="badge badge-success">Selesai</span>
                                <?php } else { ?>
                                    <span class="badge badge-pending">Belum Selesai</span>
                                <?php } ?>
                            </div>
                            
                            <h3 class="task-item-title"><?= htmlspecialchars($row['judul']) ?></h3>
                            
                            <p class="task-item-desc">
                                <?= !empty($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : '<em style="color: var(--color-text-muted)">Tidak ada deskripsi.</em>' ?>
                            </p>
                        </div>

                        <div class="task-item-meta">
                            <div>
                                <span>Deadline: </span>
                                <strong style="color: var(--color-primary);">
                                    <?= $row['deadline'] ? date('d M Y', strtotime($row['deadline'])) : 'Tidak ada' ?>
                                </strong>
                            </div>
                            
                            <div style="display: flex; gap: 8px;">
                                <?php if (!$isCompleted) { ?>
                                    <a href="../selesai.php?id=<?= $row['id_tugas'] ?>&from=list" class="btn btn-success btn-sm" title="Tandai Selesai">
                                        Selesai
                                    </a>
                                <?php } ?>
                                <a href="edit.php?id=<?= $row['id_tugas'] ?>" class="btn btn-secondary btn-sm" title="Edit Tugas">
                                    Edit
                                </a>
                                <a href="../hapus.php?id=<?= $row['id_tugas'] ?>&from=list" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')" title="Hapus Tugas">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div id="no-tasks-fallback" style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; color: var(--color-text-muted); background: var(--color-surface); border-radius: var(--radius-lg); border: 1px dashed var(--color-border);">
                    <h2>Belum ada tugas dibuat!</h2>
                    <p style="margin-top: 10px; margin-bottom: 20px;">Tambahkan tugas harian Anda dan mulailah tingkatkan produktivitas!</p>
                    <a href="tambah.php" class="btn">+ Tambah Tugas Pertama</a>
                </div>
            <?php } ?>
            
            <!-- Fallback element when client-side filters result in zero matches -->
            <div id="no-results-message" style="display: none; grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--color-text-muted); background: var(--color-surface); border-radius: var(--radius-lg); border: 1px dashed var(--color-border);">
                <h3>Tidak ada tugas yang cocok dengan filter pencarian Anda.</h3>
            </div>
        </div>
    </div>

    <!-- Script Utilities -->
    <script src="../assets/js/main.js"></script>

    <!-- Client-side Filtering Script -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.getElementById("search-input");
            const categoryFilter = document.getElementById("category-filter");
            const statusFilter = document.getElementById("status-filter");
            const taskCards = document.querySelectorAll(".task-item-card");
            const noResultsMessage = document.getElementById("no-results-message");
            const noTasksFallback = document.getElementById("no-tasks-fallback");

            function filterTasks() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const catVal = categoryFilter.value;
                const statusVal = statusFilter.value;
                
                let visibleCount = 0;

                taskCards.forEach(card => {
                    const title = card.getAttribute("data-title");
                    const desc = card.getAttribute("data-desc");
                    const category = card.getAttribute("data-category");
                    const status = card.getAttribute("data-status");

                    const matchesSearch = title.includes(searchVal) || desc.includes(searchVal);
                    const matchesCategory = (catVal === "all") || (category === catVal);
                    const matchesStatus = (statusVal === "all") || (status === statusVal);

                    if (matchesSearch && matchesCategory && matchesStatus) {
                        card.style.display = "flex";
                        visibleCount++;
                    } else {
                        card.style.display = "none";
                    }
                });

                // Display appropriate placeholder message if all cards are hidden
                if (taskCards.length > 0) {
                    if (visibleCount === 0) {
                        noResultsMessage.style.display = "block";
                    } else {
                        noResultsMessage.style.display = "none";
                    }
                }
            }

            // Bind listeners
            if(searchInput) searchInput.addEventListener("input", filterTasks);
            if(categoryFilter) categoryFilter.addEventListener("change", filterTasks);
            if(statusFilter) statusFilter.addEventListener("change", filterTasks);
        });
    </script>
    
    <?php if (isset($_GET['toast'])) { ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const type = "<?= htmlspecialchars($_GET['toast']) ?>";
                if(type === "task_added") showToast("Tugas baru berhasil ditambahkan!", "success");
                if(type === "task_completed") showToast("Tugas berhasil diselesaikan! (+10 XP)", "success");
                if(type === "task_deleted") showToast("Tugas berhasil dihapus!", "error");
                if(type === "task_updated") showToast("Tugas berhasil diperbarui!", "success");
            });
        </script>
    <?php } ?>
</body>
</html>