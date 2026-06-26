<?php
include '../config/koneksi.php';

// Handle Add Category
if (isset($_POST['tambah_kategori'])) {
    $nama_kategori = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    if (!empty(trim($nama_kategori))) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: index.php?toast=kat_added");
            exit;
        }
    }
}

// Handle Delete Category
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    // Set all tasks in this category to NULL id_kategori first to avoid foreign key issues
    mysqli_query($koneksi, "UPDATE tugas SET id_kategori = NULL WHERE id_kategori = '$id_hapus'");
    
    // Delete the category
    $query = "DELETE FROM kategori WHERE id_kategori = '$id_hapus'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?toast=kat_deleted");
        exit;
    }
}

// Fetch all categories
$categories = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id_kategori DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Kategori - LifeQuest</title>
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
            <h1>Kelola Kategori</h1>
            <p>Atur kategori tugas untuk mempermudah organisasi aktivitas Anda</p>
        </header>

        <!-- Two Column Layout -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 30px; margin-top: 20px;">
            <!-- Column 1: Add Category -->
            <div class="glass-card">
                <h3 style="font-family: var(--font-title); margin-bottom: 15px; color: var(--color-primary);">Tambah Kategori Baru</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori <span style="color: #e74c3c;">*</span></label>
                        <input type="text" id="nama_kategori" name="nama_kategori" required placeholder="Contoh: Finansial, Olahraga, dll." />
                    </div>
                    <button type="submit" name="tambah_kategori" class="btn" style="width: 100%;">
                        Tambah Kategori
                    </button>
                </form>
            </div>

            <!-- Column 2: Categories List -->
            <div class="glass-card">
                <h3 style="font-family: var(--font-title); margin-bottom: 15px; color: var(--color-primary);">Daftar Kategori</h3>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th style="text-align: right; width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($categories) > 0) { ?>
                                <?php while ($row = mysqli_fetch_assoc($categories)) { ?>
                                    <tr>
                                        <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td style="text-align: right;">
                                            <a href="index.php?hapus=<?= $row['id_kategori'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua tugas dengan kategori ini akan diubah menjadi Tanpa Kategori.')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; padding: 25px; color: var(--color-text-muted);">
                                        Belum ada kategori. Silakan tambahkan kategori pertama Anda di atas!
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
                if(type === "kat_added") showToast("Kategori baru berhasil ditambahkan!", "success");
                if(type === "kat_deleted") showToast("Kategori berhasil dihapus!", "error");
            });
        </script>
    <?php } ?>
</body>
</html>
