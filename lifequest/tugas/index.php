<?php
include '../config/koneksi.php';

$data = mysqli_query($koneksi,"
SELECT
t.id_tugas,
t.judul,
t.deadline,
t.status,
k.nama_kategori
FROM tugas t
JOIN kategori k ON t.id_kategori = k.id_kategori
ORDER BY t.id_tugas DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>LifeQuest</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>

body{
margin:0;
font-family:Poppins;
background:#F5E9DA;
color:#3E2C23;
}

header{
background:#8B5E3C;
color:white;
padding:25px;
text-align:center;
font-family:"Playfair Display";
font-size:28px;
}

.container{
max-width:900px;
margin:30px auto;
padding:20px;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
margin-bottom:20px;
}

.btn{
background:#8B5E3C;
color:white;
padding:10px 15px;
text-decoration:none;
border-radius:10px;
}

.card{
background:#FFF8F0;
padding:18px;
border-radius:15px;
margin-bottom:15px;
box-shadow:0 6px 14px rgba(0,0,0,.08);
}

.title{
font-size:18px;
font-weight:600;
}

.meta{
font-size:13px;
opacity:0.7;
margin-top:5px;
}

.status{
margin-top:10px;
font-weight:600;
color:#8B5E3C;
}

.actions{
margin-top:10px;
}

.actions a{
text-decoration:none;
color:#8B5E3C;
font-weight:500;
margin-right:10px;
}

.badge{
display:inline-block;
padding:4px 10px;
background:#e7d5c3;
border-radius:8px;
font-size:12px;
margin-top:8px;
}

</style>

</head>

<body>

<header>
List Tugas
</header>

<div class="container">

<div class="topbar">
<a class="btn" href="tambah.php">+ Tambah Tugas</a>
<a class="btn" href="../index.php">Dashboard</a>
</div>

<?php while($row = mysqli_fetch_assoc($data)){ ?>

<div class="card">

<div class="title">
<?= $row['judul'] ?>
</div>

<div class="meta">
Kategori: <?= $row['nama_kategori'] ?> | Deadline: <?= $row['deadline'] ?>
</div>

<div class="badge">
<?= $row['status'] ?>
</div>

<div class="actions">

<a href="hapus.php?id=<?= $row['id_tugas'] ?>">Hapus</a>

</div>

</div>

<?php } ?>

</div>

</body>
</html>