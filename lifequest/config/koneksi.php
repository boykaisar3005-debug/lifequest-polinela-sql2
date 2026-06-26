<?php

$is_localhost = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($is_localhost) {
    // Kredensial Database Lokal (XAMPP)
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "lifequest";
} else {
    // Kredensial Database Baru InfinityFree
    $host = "sql113.infinityfree.com";      // <-- Silakan ganti HOST ini jika di panel InfinityFree Anda tertulis berbeda (misal: sql110.infinityfree.com)
    $user = "if0_42275427";                // Kredensial Baru Anda
    $pass = "ewg1ok89fRm9Q";                // Password Baru Anda
    $db   = "if0_42275427_lifequest";       // Nama database Anda (Asumsi nama DB saat dibuat adalah 'lifequest')
}

// Menonaktifkan error mysqli exception agar tidak langsung crash HTTP 500 jika koneksi gagal,
// sehingga kita bisa menampilkan error penjelas di bawah.
mysqli_report(MYSQLI_REPORT_OFF);

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    echo "
    <div style='font-family: sans-serif; padding: 30px; max-width: 600px; margin: 50px auto; border: 1px solid #e74c3c; border-radius: 10px; background: #fdf2f2; color: #c0392b;'>
        <h2 style='margin-top: 0;'>Gagal Menghubungkan ke Database Hosting!</h2>
        <p>Aplikasi mendeteksi Anda sedang mengakses lewat internet (InfinityFree), namun koneksi ke database MySQL gagal.</p>
        <hr style='border: 0; border-top: 1px solid #f5c6cb; margin: 20px 0;'>
        <p><strong>Detail Teknis Error:</strong><br><code style='background: #f8d7da; padding: 3px 6px; border-radius: 4px; display: inline-block; margin-top: 5px;'>" . mysqli_connect_error() . "</code></p>
        <p><strong>Kredensial yang digunakan saat ini:</strong></p>
        <ul style='line-height: 1.6;'>
            <li><strong>MySQL Host:</strong> <code>$host</code></li>
            <li><strong>MySQL User:</strong> <code>$user</code></li>
            <li><strong>Database Name:</strong> <code>$db</code></li>
        </ul>
        <p style='font-size: 0.9rem; color: #721c24; margin-top: 20px;'>
            💡 <strong>Solusi:</strong> Periksa kembali tab <strong>MySQL Databases</strong> di akun InfinityFree Anda. Jika nama Hostname (misalnya <code>sql300.infinityfree.com</code>) atau nama database berbeda, silakan edit file <strong>config/koneksi.php</strong> dan sesuaikan nilainya, lalu upload kembali.
        </p>
    </div>
    ";
    die();
}

?>