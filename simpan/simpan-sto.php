<?php
include "../konek/koneksi.php";

// Mendapatkan data dari form
$id_sto = $_POST['id_sto']; // Mengganti nama variabel sesuai dengan data STO
$spesifikasi = $_POST['spesifikasi']; // Menambahkan kolom spesifikasi
$kode_area = $_POST['kode_area']; // Menggunakan kode_area yang sama

// Cek apakah ID STO sudah ada di database
$cek_id_sto = mysqli_query($koneksi, "SELECT * FROM sto WHERE id_sto = '$id_sto'");
if (mysqli_num_rows($cek_id_sto) > 0) {
    // Redirect ke halaman form dengan parameter id_exists=true
    header("Location: ../create/create-sto.php?id_exists=true");
    exit;
} else {
    // Lakukan penyimpanan data jika ID belum ada
    $simpan = mysqli_query($koneksi, "INSERT INTO sto (id_sto, spesifikasi, kode_area) 
                                       VALUES ('$id_sto', '$spesifikasi', '$kode_area')");
    
    if ($simpan) {
        header("Location: ../admin/data-sto.php"); // Redirect ke halaman data setelah berhasil
    } else {
        echo "Gagal menyimpan data.";
    }
}
?>
