<?php
include 'config/koneksi.php';

$totalTugas = mysqli_num_rows(
    mysqli_query($koneksi,"SELECT * FROM tugas")
);

$totalSelesai = mysqli_num_rows(
    mysqli_query(
        $koneksi,
        "SELECT * FROM tugas WHERE status='Selesai'"
    )
);

$totalQuest = mysqli_num_rows(
    mysqli_query($koneksi,"SELECT * FROM quest")
);

$persentase = 0;

if($totalTugas > 0){
    $persentase = round(
        ($totalSelesai / $totalTugas) * 100
    );
}

$questHariIni = mysqli_query(
    $koneksi,
    "SELECT * FROM quest
     WHERE status='Belum'
     ORDER BY RAND()
     LIMIT 3"
);

// Menghapus baris ini karena tidak diperlukan dan dapat menyebabkan error saat looping
// $quest = mysqli_fetch_assoc($questHariIni);

$tugasTerbaru = mysqli_query(
    $koneksi,
    "SELECT * FROM tugas ORDER BY id_tugas DESC LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>LifeQuest</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

<style>
#clock{
margin-top:10px;
font-size:18px;
font-weight:500;
letter-spacing:1px;
}

    .tasks-grid{
display:grid;
grid-template-columns:repeat(2, 1fr);
gap:15px;
margin-top:15px;
}

@media (max-width:768px){
.tasks-grid{
grid-template-columns:1fr;
}
}
.task-card{
background:#FFF8F0;
padding:18px;
border-radius:18px;
box-shadow:0 4px 12px rgba(0,0,0,.08);
transition:all .25s ease;
position:relative;
overflow:hidden;
}

.task-card:hover{
transform:translateY(-5px);
box-shadow:0 10px 25px rgba(0,0,0,.15);
}

.task-title{
font-size:18px;
font-weight:600;
margin-bottom:5px;
}

.task-meta{
font-size:13px;
opacity:0.7;
margin-bottom:10px;
}

.badge{
display:inline-block;
padding:4px 10px;
border-radius:8px;
background:#e7d5c3;
font-size:12px;
margin-bottom:10px;
}

.task-actions a{
text-decoration:none;
margin-right:10px;
color:#8B5E3C;
font-weight:600;
font-size:13px;
}

@keyframes fadeUp{
from{
opacity:0;
transform:translateY(10px);
}
to{
opacity:1;
transform:translateY(0);
}
}

.task-card{
animation:fadeUp .3s ease;
}

body{
margin:0;
font-family:Poppins;
background:#F5E9DA;
color:#3E2C23;
}

header{
background:#8B5E3C;
padding:30px;
text-align:center;
color:white;
}

header h1{
font-family:'Playfair Display', serif;
font-size:42px;
}

.container{
width:90%;
max-width:1200px;
margin:auto;
padding:30px 0;
}

.cards{
display:flex;
gap:20px;
flex-wrap:wrap;
}

.card{
flex:1;
min-width:250px;
background:#FFF8F0;
padding:25px;
border-radius:20px;
box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.number{
font-size:42px;
font-weight:bold;
color:#8B5E3C;
}

.quest{
margin-top:25px;
background:#FFF8F0;
padding:25px;
border-radius:20px;
box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.quest-name{
font-size:22px;
font-weight:600;
}

.progress{
width:100%;
height:20px;
background:#e5d6c8;
border-radius:20px;
overflow:hidden;
margin-top:10px;
}

.progress-fill{
height:100%;
background:#8B5E3C;
}

.btn{
display:inline-block;
margin-top:15px;
padding:12px 20px;
background:#8B5E3C;
color:white;
text-decoration:none;
border-radius:10px;
}

.tasks{
margin-top:25px;
background:#FFF8F0;
padding:25px;
border-radius:20px;
box-shadow:0 4px 12px rgba(0,0,0,.08);
}

table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

table th{
background:#8B5E3C;
color:white;
padding:12px;
}

table td{
padding:12px;
border-bottom:1px solid #ddd;
}

.status{
font-weight:bold;
}

.action a{
text-decoration:none;
margin-right:10px;
color:#8B5E3C;
font-weight:600;
}

footer{
text-align:center;
padding:20px;
margin-top:30px;
color:#777;
}
<!-- Tambahkan di bagian <style> sebelum penutup </style> -->
<style>
  /* Background gradient lembut dan animasi */
  body {
    background: linear-gradient(135deg, #F5E9DA, #FFE4E1);
    background-size: 400% 400%;
    animation: gradientAnimation 30s ease infinite;
  }

  @keyframes gradientAnimation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* Card efek hover lebih halus dan menarik */
  .card {
    transition: all 0.3s ease;
  }
  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.2);
  }

  /* Efek hover dan focus untuk tombol */
  .btn {
    transition: background-color 0.3s, transform 0.2s;
  }
  .btn:hover {
    background-color: #A0522D;
    transform: scale(1.05);
  }

  /* Efek pada tabel saat hover */
  table tr:hover {
    background-color: #ffe4e1;
  }

  /* Efek transisi untuk progress bar */
  .progress-fill {
    transition: width 1s ease;
  }
</style>
</style>
</head>

<body>
<script>
function updateClock() {
    const now = new Date();
    const jam = String(now.getHours()).padStart(2,'0');
    const menit = String(now.getMinutes()).padStart(2,'0');
    const detik = String(now.getSeconds()).padStart(2,'0');

    document.getElementById("clock").innerHTML =
        jam + ":" + menit + ":" + detik;
}
updateClock();
setInterval(updateClock,1000);
</script>

<header>
<h1>LifeQuest</h1>
<p>Jadilah Versi Dirimu Yang Terbaik</p>
</header>
<div id="clock"></div>

<div class="container">

<div class="cards">

<div class="card">
<h3>Total Tugas</h3>
<div class="number"><?= $totalTugas ?></div>
</div>

<div class="card">
<h3>Tugas Selesai</h3>
<div class="number"><?= $totalSelesai ?></div>
</div>

<div class="card">
<h3>Total Quest</h3>
<div class="number"><?= $totalQuest ?></div>
</div>

</div>

<div class="quest">
<h2>Progress Produktivitas</h2>
<p><?= $persentase ?>% tugas telah diselesaikan</p>
<div class="progress">
<div class="progress-fill" style="width:<?= $persentase ?>%"></div>
</div>
</div>

<div class="quest">
<h2>Quest Produktivitas Hari Ini</h2>
<div class="tasks-grid">
<?php while($q = mysqli_fetch_assoc($questHariIni)){ ?>
<div class="task-card">
<div class="task-title">
<?= $q['nama_quest'] ?>
</div>
<p>
Reward: <?= $q['poin'] ?> poin
</p>
<a href="quest_selesai.php?id=<?= $q['id_quest'] ?>" class="btn">Selesaikan</a>
<a href="tugas/tambah.php" class="btn">Tambah Tugas</a>
<a href="reset_quest.php" class="btn">Reset Quest</a>
</div>
<?php } ?>
</div>
</div>

<div class="tasks">
<h2>Tugas Terbaru</h2>
<div class="tasks-grid">
<?php while($t=mysqli_fetch_assoc($tugasTerbaru)){ ?>
<div class="task-card">
<div class="task-title">
<?= $t['judul'] ?>
</div>
<div class="task-meta">
Deadline: <?= $t['deadline'] ?>
</div>
<div class="badge">
<?= $t['status'] ?>
</div>
<div class="task-actions">
<?php if($t['status'] != 'Selesai'){ ?>
<a href="selesai.php?id=<?= $t['id_tugas'] ?>">Selesai</a>
<?php } ?>
<a href="hapus.php?id=<?= $t['id_tugas'] ?>">Hapus</a>
</div>
</div>
<?php } ?>
</div>

<table>
<tr>
<th>List</th>
<th>Tugas</th>
<th>Beserta</th>
<th>Deadline</th>
</tr>
<?php mysqli_data_seek($tugasTerbaru, 0); // Reset pointer agar loop ini ulang lagi ?>
<?php while($t=mysqli_fetch_assoc($tugasTerbaru)){ ?>
<tr>
<td><?= $t['judul'] ?></td>
<td><?= $t['deadline'] ?></td>
<td class="status"><?= $t['status'] ?></td>
<td class="action">
<?php if($t['status'] != 'Selesai'){ ?>
<a href="selesai.php?id=<?= $t['id_tugas'] ?>">Selesai</a>
<?php } ?>
<a href="hapus.php?id=<?= $t['id_tugas'] ?>">Hapus</a>
</td>
</tr>
<?php } ?>
</table>

</div>

<footer>
Web by : @Chaesaar_
</footer>

</body>
</html>