<?php
if(isset($_GET['kode_label'])){
    $kode_label = $_GET['kode_label'];    
    include '../konek/koneksi.php';
    $query = "Delete From qr where kode_label = $kode_label";
    mysqli_query($koneksi, $query);

    header("location:../admin/hal-qr.php");
    exit();
}
?>