<?php
session_start(); // Memulai session untuk mengambil username
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}
$username = $_SESSION['username']; // Mengambil username dari session

include "../konek/koneksi.php"; // Koneksi ke database

// Mendapatkan data dari form dan menghindari SQL injection
$id_odp = mysqli_real_escape_string($koneksi, $_POST['id_odp']);
$kode_jenis = mysqli_real_escape_string($koneksi, $_POST['kode_jenis']);
$kode_area = mysqli_real_escape_string($koneksi, $_POST['kode_area']);
$nomor_odp = mysqli_real_escape_string($koneksi, $_POST['nomor_odp']);
$id_odc = mysqli_real_escape_string($koneksi, $_POST['id_odc']);
$tanggal_pengerjaan = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengerjaan']);
$koordinat = mysqli_real_escape_string($koneksi, $_POST['koordinat']);
$kode_label = mysqli_real_escape_string($koneksi, $_POST['kode_label']);
$waktu = mysqli_real_escape_string($koneksi, $_POST['waktu']);
$status_odp = mysqli_real_escape_string($koneksi, $_POST['status_odp']);
$nama_odp = mysqli_real_escape_string($koneksi, $_POST['nama_odp']);

// Validasi kode_label dan waktu di tabel qr
$cek_qr = mysqli_query($koneksi, "SELECT * FROM qr WHERE kode_label = '$kode_label' AND waktu = '$waktu'");
if (mysqli_num_rows($cek_qr) > 0) {
    // Jika data ada di tabel qr, lanjutkan validasi ID ODP
    $cek_id_odp = mysqli_query($koneksi, "SELECT * FROM odp WHERE id_odp = '$id_odp'");
    if (mysqli_num_rows($cek_id_odp) > 0) {
        // Redirect ke halaman form jika ID ODP sudah ada
        header("Location: ../create/create-odp.php?id_exists=true");
        exit;
    } else {
        // Simpan data ke tabel odp jika ID belum ada
        $simpan = mysqli_query($koneksi, "INSERT INTO odp (id_odp, kode_jenis, kode_area, nomor_odp, id_odc, tanggal_pengerjaan, koordinat, kode_label, waktu, status_odp, nama_odp) 
                                           VALUES ('$id_odp', '$kode_jenis', '$kode_area', '$nomor_odp', '$id_odc', '$tanggal_pengerjaan', '$koordinat', '$kode_label', '$waktu', '$status_odp', '$nama_odp')");
        
        if ($simpan) {
            // Redirect ke halaman data setelah berhasil
            header("Location: ../admin/data-odp.php");
            exit;
        } else {
            // Tampilkan pesan error jika gagal menyimpan
            echo "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
} else {
    // Jika kode_label dan waktu tidak ditemukan di tabel qr, tampilkan pesan error
    header("Location: ../create/create-odp.php?kode_label_invalid=true");
    exit;
}

?>
