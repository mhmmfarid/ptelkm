<?php
include '../konek/koneksi.php';
    if($_SERVER ['REQUEST_METHOD'] == 'POST'){

        $id_odc = $_POST['id_odc'];
        $nama_odc = $_POST['nama_odc'];
        $kode_area = $_POST['kode_area'];
        $kode_jenis = $_POST['kode_jenis'];
        $isi_port = $_POST['isi_port'];
        $lokasi = $_POST['lokasi'];
        $koordinat = $_POST['koordinat'];
        $status_odc = $_POST['status_odc'];
        $tanggal_pemasangan = $_POST['tanggal_pemasangan'];
       

        $update_querry = "UPDATE odc SET
        nama_odc = '$nama_odc',
        kode_area = '$kode_area',
        kode_jenis = '$kode_jenis',
        isi_port = '$isi_port',
        lokasi = '$lokasi',
        koordinat = '$koordinat',
        status_odc = '$status_odc',
        tanggal_pemasangan = '$tanggal_pemasangan'
    WHERE id_odc = '$id_odc'";

        $result = mysqli_query($koneksi, $update_querry);
        if($result){
            header("location:../admin/data-odc.php");
            exit();
        }else{
            echo "Update Data Gagal" . mysqli_eror($koneksi);
            exit();
        }
    }else{
        header("location:../admin/data-odc.php");
        exit();
    }
?>
