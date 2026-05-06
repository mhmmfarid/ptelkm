<?php
include '../konek/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    $update_query = "UPDATE login SET
        username = '$username',
        password = '$password',
        status = '$status'
    WHERE id = '$id'";

    $result = mysqli_query($koneksi, $update_query);
    if ($result) {
        header("Location: ../superadmin/data-akun.php");
        exit();
    } else {
        echo "Update Data Gagal: " . mysqli_error($koneksi);
        exit();
    }
} else {
    header("Location: ../superadmin/data-akun.php");
    exit();
}
?>
