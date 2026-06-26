<?php
require_once __DIR__ . '/config/koneksi.php';

echo "<h2>Setup Login SPK SAW</h2>";

// 1. Buat tabel users
$sql_create = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL
)";

if (mysqli_query($conn, $sql_create)) {
    echo "<p>Tabel 'users' berhasil dicek/dibuat.</p>";
} else {
    echo "<p>Gagal membuat tabel users: " . mysqli_error($conn) . "</p>";
}

// 2. Cek apakah admin sudah ada
$cek_admin = mysqli_query($conn, "SELECT id FROM users WHERE username = 'admin'");
if (mysqli_num_rows($cek_admin) == 0) {
    // Buat admin default (password: admin123)
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $sql_insert = "INSERT INTO users (username, password, nama) VALUES ('admin', '$password_hash', 'Administrator')";
    
    if (mysqli_query($conn, $sql_insert)) {
        echo "<p>User admin berhasil ditambahkan!</p>";
        echo "<p><strong>Username:</strong> admin<br><strong>Password:</strong> admin123</p>";
    } else {
        echo "<p>Gagal menambahkan admin: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>User admin sudah ada di database.</p>";
}

echo "<br><a href='halaman/login.php'>Pergi ke halaman Login</a>";
?>
