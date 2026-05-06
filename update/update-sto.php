<?php
include '../konek/koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id_sto = $_POST['id_sto'];
    $spesifikasi = $_POST['spesifikasi'];
    $kode_area = $_POST['kode_area'];

    $update_query = "UPDATE sto SET
        spesifikasi = '$spesifikasi',
        kode_area = '$kode_area'
    WHERE id_sto = '$id_sto'";

    $result = mysqli_query($koneksi, $update_query);
    if($result){
        header("location:../admin/data-sto.php");
        exit();
    } else {
        echo "Update Data Gagal: " . mysqli_error($koneksi);
        exit();
    }
} else {
    header("location:../admin/data-sto.php");
    exit();
}
?>
