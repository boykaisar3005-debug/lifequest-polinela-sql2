<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = mysqli_query($koneksi, "SELECT * FROM tugas WHERE id_tugas = '$id'");

if (mysqli_num_rows($query) === 0) {
    header("Location: index.php");
    exit;
}

$task = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $id_kategori = mysqli_real_escape_string($koneksi, $_POST['id_kategori']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline = mysqli_real_escape_string($koneksi, $_POST['deadline']);
    $status = mysqli_real_escape_string($koneksi, $_POST['status']);

    $deadline_val = !empty($deadline) ? "'$deadline'" : "NULL";

    $update_query = "UPDATE tugas SET 
                        id_kategori = '$id_kategori',
                        judul = '$judul',
                        deskripsi = '$deskripsi',
                        deadline = $deadline_val,
                        status = '$status'
                     WHERE id_tugas = '$id'";

    if (mysqli_query($koneksi, $update_query)) {
        header("Location: index.php?toast=task_updated");
        exit;
    }
}

$kategori_list = mysqli_query($koneksi, "SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Tugas - LifeQuest</title>
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
                Edit Tugas
            </h1>
            <p style="text-align: center; color: var(--color-text-muted); margin-bottom: 30px;">
                Perbarui detail tugas Anda di bawah ini
            </p>

            <form method="POST">
                <div class="form-group">
                    <label for="judul">Judul Tugas <span style="color: #e74c3c;">*</span></label>
                    <input type="text" id="judul" name="judul" required value="<?= htmlspecialchars($task['judul']) ?>" />
                </div>

                <div class="form-group">
                    <label for="id_kategori">Kategori <span style="color: #e74c3c;">*</span></label>
                    <select id="id_kategori" name="id_kategori" required>
                        <?php while ($k = mysqli_fetch_assoc($kategori_list)) { 
                            $selected = ($k['id_kategori'] == $task['id_kategori']) ? 'selected' : '';
                        ?>
                            <option value="<?= $k['id_kategori'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($k['nama_kategori']) ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi"><?= htmlspecialchars($task['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" id="deadline" name="deadline" value="<?= $task['deadline'] ?>" />
                </div>

                <div class="form-group">
                    <label for="status">Status Tugas <span style="color: #e74c3c;">*</span></label>
                    <select id="status" name="status" required>
                        <option value="Belum Selesai" <?= ($task['status'] === 'Belum Selesai') ? 'selected' : '' ?>>Belum Selesai</option>
                        <option value="Selesai" <?= ($task['status'] === 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                    </select>
                </div>

                <button type="submit" name="update" class="btn" style="width: 100%; margin-top: 10px;">
                    Perbarui Tugas
                </button>
            </form>
        </div>
    </div>

    <!-- Script Utilities -->
    <script src="../assets/js/main.js"></script>
</body>
</html>
