<?php
$conn = mysqli_connect("localhost", "root", "", "spk_saw");
// $conn = mysqli_connect("sql311.infinityfree.com", "if0_42274170", "Spkkelompok2", "if0_42274170_spk_saw");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>