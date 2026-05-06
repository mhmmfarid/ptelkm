<?php
if(isset($_GET['id_sto'])){
    $id_sto = $_GET['id_sto'];    
    include '../konek/koneksi.php';
    $query = "Delete From sto where id_sto = $id_sto";
    mysqli_query($koneksi, $query);

    header("location:../admin/data-sto.php");
    exit();
}
?>