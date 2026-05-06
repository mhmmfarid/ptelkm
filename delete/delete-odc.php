<?php
if(isset($_GET['id_odc'])){
    $id_odc = $_GET['id_odc'];    
    include '../konek/koneksi.php';
    $query = "Delete From odc where id_odc = $id_odc";
    mysqli_query($koneksi, $query);

    header("location:../admin/data-odc.php");
    exit();
}
?>