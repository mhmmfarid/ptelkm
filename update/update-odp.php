<?php
include '../konek/koneksi.php';
if($_SERVER ['REQUEST_METHOD'] == 'POST'){

    $id_odp = $_POST['id_odp'];
    $kode_jenis = $_POST['kode_jenis'];
    $kode_area = $_POST['kode_area'];
    $nomor_odp = $_POST['nomor_odp'];
    $id_odc = $_POST['id_odc'];
    $tanggal_pengerjaan = $_POST['tanggal_pengerjaan'];
    $koordinat = $_POST['koordinat'];
    $status_odp = $_POST['status_odp'];
    $kode_label = $_POST['kode_label'];
    $waktu = $_POST['waktu'];

    $update_query = "UPDATE odp SET
    kode_jenis = '$kode_jenis',
    kode_area = '$kode_area',
    nomor_odp = '$nomor_odp',
    id_odc = '$id_odc',
    tanggal_pengerjaan = '$tanggal_pengerjaan',
    koordinat = '$koordinat',
    status_odp = '$status_odp',
    kode_label = '$kode_label',
    waktu = '$waktu'
    WHERE id_odp = '$id_odp'";

    $result = mysqli_query($koneksi, $update_query);
    if($result){
        header("location:../admin/data-odp.php");
        exit();
    }else{
        echo "Update Data Gagal: " . mysqli_error($koneksi);  // Corrected function name to mysqli_error()
        exit();
    }
}else{
    header("location:../admin/data-odp.php");
    exit();
}
?>
