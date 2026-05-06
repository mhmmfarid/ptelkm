<?php
if(isset($_GET['id_odp'])){
    $id_odp = $_GET['id_odp'];    
    include '../konek/koneksi.php';
    $query = "Delete From odp where id_odp = $id_odp";
    mysqli_query($koneksi, $query);

    header("location:../admin/data-odp.php");
    exit();
}
?>