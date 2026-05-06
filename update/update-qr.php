<?php
include '../konek/koneksi.php';

if (isset($_POST['kode_label'])) {
    $kode_label = $_POST['kode_label'];
    $waktu = $_POST['waktu'];
    $kode_area = $_POST['kode_area'];
    $status = $_POST['status'];

    // Update the qr table
    $query = "UPDATE qr SET waktu = '$waktu', kode_area = '$kode_area', status = '$status' WHERE kode_label = '$kode_label'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Redirect or display a success message
        header("Location: ../admin/hal-qr.php?message=update_success");
        exit();
    } else {
        // Display an error message
        echo "Error updating record: " . mysqli_error($koneksi);
    }
} else {
    header("Location: ../admin/hal-qr.php?message=update_error");
    exit();
}
?>
