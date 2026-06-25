<?php
include '../config/koneksi.php';

if(isset($_POST['simpan'])){

    $id_user = 1;
    $id_kategori = $_POST['id_kategori'];
    $judul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $deadline = $_POST['deadline'];

    mysqli_query($koneksi,"
        INSERT INTO tugas (
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
            '$deadline',
            'Belum Selesai'
        )
    ");

   header("Location: /lifequest/tugas/index.php");
exit;
}

$kategori = mysqli_query($koneksi,"SELECT * FROM kategori");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Tugas - LifeQuest</title>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

<style>

/* Background gradient lembut dan animasi */
body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #F5E9DA, #FFE4E1);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  position: relative;
}

body::before {
  content: "";
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(135deg, #F5E9DA, #FFE4E1);
  background-size: 400% 400%;
  animation: gradientAnim 15s ease infinite;
  z-index: -1;
}

@keyframes gradientAnim {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Container dengan bayangan lembut dan transisi */
.container {
  max-width: 650px;
  width: 100%;
  margin: 50px auto;
  background: #FFF8F0;
  padding: 30px;
  border-radius: 18px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

/* Judul dan heading */
h1 {
  font-family: "Playfair Display", serif;
  text-align: center;
  margin-bottom: 25px;
  color: #8B5E3C;
  font-size: 28px;
  letter-spacing: 1px;
}

.header-note {
  text-align: center;
  font-size: 13px;
  opacity: 0.7;
  margin-top: -10px;
  margin-bottom: 20px;
  color: #555;
}

label {
  font-weight: 500;
  display: block;
  margin-top: 15px;
  margin-bottom: 6px;
  font-size: 14px;
  color: #3E2C23;
}

input, select, textarea {
  width: 100%;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #d8c3b0;
  font-family: 'Poppins', sans-serif;
  outline: none;
  transition: box-shadow 0.3s, border-color 0.3s;
}

input:focus, select:focus, textarea:focus {
  box-shadow: 0 0 8px rgba(139, 94, 60, 0.3);
  border-color: #8B5E3C;
}

textarea {
  height: 120px;
  resize: none;
}

button {
  margin-top: 20px;
  width: 100%;
  padding: 12px;
  background: #8B5E3C;
  border: none;
  color: white;
  font-size: 15px;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.2s, box-shadow 0.3s;
}

button:hover {
  background-color: #A0522D;
  transform: scale(1.02);
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Link kembali */
.back {
  display: block;
  text-align: center;
  margin-top: 15px;
  color: #8B5E3C;
  text-decoration: none;
  font-weight: 500;
  font-size: 14px;
  transition: color 0.3s, transform 0.2s;
}

.back:hover {
  color: #A0522D;
  transform: translateY(-2px);
}
body{
margin:0;
font-family:Poppins;
background:#F5E9DA;
color:#3E2C23;
}

.container{
max-width:650px;
margin:50px auto;
background:#FFF8F0;
padding:30px;
border-radius:18px;
box-shadow:0 8px 20px rgba(0,0,0,.08);
}

h1{
font-family:"Playfair Display";
text-align:center;
margin-bottom:25px;
color:#8B5E3C;
}

label{
font-weight:500;
display:block;
margin-top:15px;
margin-bottom:6px;
}

input,select,textarea{
width:100%;
padding:12px;
border-radius:10px;
border:1px solid #d8c3b0;
font-family:Poppins;
outline:none;
}

textarea{
height:120px;
resize:none;
}

button{
margin-top:20px;
width:100%;
padding:12px;
background:#8B5E3C;
border:none;
color:white;
font-size:15px;
border-radius:10px;
cursor:pointer;
}

button:hover{
opacity:0.9;
}

.back{
display:block;
text-align:center;
margin-top:15px;
color:#8B5E3C;
text-decoration:none;
font-weight:500;
}

.header-note{
text-align:center;
font-size:13px;
opacity:0.7;
margin-top:-10px;
margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<h1>Tambah Tugas</h1>
<div class="header-note">Tambahkan tugas baru ke LifeQuest</div>

<form method="POST">

<label>Kategori</label>
<select name="id_kategori" required>
<?php while($k = mysqli_fetch_assoc($kategori)){ ?>
<option value="<?= $k['id_kategori'] ?>">
<?= $k['nama_kategori'] ?>
</option>
<?php } ?>
</select>

<label>Judul Tugas</label>
<input type="text" name="judul" required>

<label>Deskripsi</label>
<textarea name="deskripsi"></textarea>

<label>Deadline</label>
<input type="date" name="deadline">

<button type="submit" name="simpan">
Simpan Tugas
</button>

<a class="back" href="index.php">
← Kembali ke Dashboard
</a>

</form>

</div>

</body>
</html>