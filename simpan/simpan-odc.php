<?php
include "../konek/koneksi.php";

// Mendapatkan data dari form
$id_odc = $_POST['id_odc'];
$nama_odc = $_POST['nama_odc'];
$kode_area = $_POST['kode_area'];
$kode_jenis = $_POST['kode_jenis'];
$isi_port = $_POST['isi_port'];
$no_odc = $_POST['no_odc'];
$lokasi = $_POST['lokasi'];
$koordinat = $_POST['koordinat'];
$status_odc = $_POST['status$status_odc'];
$tanggal_pemasangan = $_POST['tanggal_pemasangan'];

// Cek apakah ID ODC sudah ada di database
$cek_id_odc = mysqli_query($koneksi, "SELECT * FROM odc WHERE id_odc = '$id_odc'");
if (mysqli_num_rows($cek_id_odc) > 0) {
    // Redirect ke halaman form dengan parameter id_exists=true
    header("Location: ../create/create-odc.php?id_exists=true");
    exit;
} else {
    // Lakukan penyimpanan data jika ID belum ada
    $simpan = mysqli_query($koneksi, "INSERT INTO odc (id_odc, nama_odc, kode_area, kode_jenis, isi_port, no_odc, lokasi, koordinat, status_odc, tanggal_pemasangan) 
                                       VALUES ('$id_odc', '$nama_odc', '$kode_area', '$kode_jenis', '$isi_port','$no_odc', '$lokasi', '$koordinat', '$status_odc', '$tanggal_pemasangan')");
    
    if ($simpan) {
        header("Location: ../admin/data-odc.php"); // Redirect ke halaman data setelah berhasil
        exit;
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>
