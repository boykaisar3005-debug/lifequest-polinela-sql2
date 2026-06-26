<?php
include '../config/koneksi.php';

if (isset($_POST['simpan'])) {
    $id_user = 1;
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline = mysqli_real_escape_string($koneksi, $_POST['deadline']);

    // Handle empty deadline
    $deadline_val = !empty($deadline) ? "'$deadline'" : "NULL";

    $query = "INSERT INTO tugas (
                id_user,
                id_kategori,
                judul,
                deskripsi,
                deadline,
                status
              ) VALUES (
                '$id_user',
                '$id_kategori',
                '$judul',
                '$deskripsi',
                $deadline_val,
                'Belum Selesai'
              )";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php?toast=task_added");
        exit;
    }
}

$kategori = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Tugas - LifeQuest</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Global CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

    <div class="container" style="max-width: 700px;">
        <!-- Top Bar -->
        <div class="topbar">
            <a href="index.php" class="btn btn-secondary">&larr; Kembali ke List</a>
            <button id="theme-toggle" class="theme-toggle-btn" title="Ganti Tema"></button>
        </div>

        <!-- Form Card -->
        <div class="glass-card">
            <h1 style="font-family: var(--font-title); text-align: center; margin-bottom: 10px; color: var(--color-primary);">
                Tambah Tugas Baru
            </h1>
            <p style="text-align: center; color: var(--color-text-muted); margin-bottom: 30px;">
                Tambahkan tugas baru untuk meningkatkan level dan XP Anda!
            </p>

            <form method="POST">
                <div class="form-group">
                    <label for="judul">Judul Tugas <span style="color: #e74c3c;">*</span></label>
                    <input type="text" id="judul" name="judul" required placeholder="Contoh: Belajar Pemrograman PHP" />
                </div>

                <div class="form-group">
                    <label for="id_kategori">Kategori <span style="color: #e74c3c;">*</span></label>
                    <select id="id_kategori" name="id_kategori" required>
                        <option value="" disabled selected>-- Pilih Kategori --</option>
                        <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                            <option value="<?= $k['id_kategori'] ?>">
                                <?= htmlspecialchars($k['nama_kategori']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" placeholder="Tulis rincian tugas Anda di sini (opsional)..."></textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" id="deadline" name="deadline" />
                </div>

                <button type="submit" name="simpan" class="btn" style="width: 100%; margin-top: 10px;">
                    Simpan Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- Script Utilities -->
    <script src="../assets/js/main.js"></script>
</body>
</html>