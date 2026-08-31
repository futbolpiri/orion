<?php
$servername = "localhost"; // Sunucu adı
$username = "u2671260_user"; // Veritabanı kullanıcı adı
$password = "!foqOZmUI}%8Gb*L"; // Veritabanı şifresi 
$dbname = "u2671260_vb"; // Veritabanı adı

// Bağlantıyı kur
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Bağlantıyı kontrol et
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>