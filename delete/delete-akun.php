<?php
if(isset($_GET['id'])){
    $id = $_GET['id'];    
    include '../konek/koneksi.php';
    $query = "Delete From login where id = $id";
    mysqli_query($koneksi, $query);

    header("location:../superadmin/data-akun.php");
    exit();
}
?>