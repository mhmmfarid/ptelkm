<?php
// Include koneksi database
include '../konek/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_odp'], $_POST['kode_label'])) {
    // Ambil data dari form
    $id_odp = $_POST['id_odp'];
    $kode_label = $_POST['kode_label'];

    // Query untuk mendapatkan status dari tabel qr berdasarkan kode_label
    $query = "SELECT status FROM qr WHERE kode_label = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $kode_label); // Bind kode_label sebagai parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status_qr = $row['status']; // Ambil status dari tabel qr

        // Update status_odp pada tabel odp berdasarkan id_odp
        $update_query = "UPDATE odp SET kode_label = ?, status_odp = ? WHERE id_odp = ?";
        $update_stmt = $koneksi->prepare($update_query);
        $update_stmt->bind_param("sss", $kode_label, $status_qr, $id_odp);

        if ($update_stmt->execute()) {
            echo "Kode Label dan Status berhasil diperbarui!";
        } else {
            // Error pada query update
            echo "Error updating data: " . $update_stmt->error;
        }
    } else {
        echo "Kode Label tidak ditemukan.";
    }
} else {
    echo "Data tidak lengkap.";
}
?>
