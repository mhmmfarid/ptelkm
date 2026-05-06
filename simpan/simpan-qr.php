<?php
include "../konek/koneksi.php";

// Mengambil data dari form
$kode_label = $_POST['kode_label'];
$waktu = $_POST['waktu'];
$kode_area = $_POST['kode_area'];
$status = $_POST['status'];

// Query untuk menyimpan data ke dalam tabel qr
$simpan = mysqli_query($koneksi, "INSERT INTO qr (kode_label, waktu, kode_area, status) VALUES ('$kode_label', '$waktu', '$kode_area', '$status')");

// Mengecek apakah data berhasil disimpan
if ($simpan) {
    // Jika berhasil, redirect ke halaman data QR
    header("Location: ../admin/hal-qr.php");
} else {
    // Jika gagal, tampilkan error
    echo "Error: " . mysqli_error($koneksi);
}
?>
