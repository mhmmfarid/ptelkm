<?php
// Sertakan koneksi ke database
include "../konek/koneksi.php";

// Cek apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan array $_POST mengandung data yang sesuai
    if (isset($_POST['id'], $_POST['username'], $_POST['password'], $_POST['status'])) {
        // Ambil data dari form
        $id = trim($_POST['id']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $status = trim($_POST['status']);

        // Validasi input untuk memastikan tidak ada yang kosong
        if (empty($id) || empty($username) || empty($password) || empty($status)) {
            echo "<script>alert('All fields are required.');</script>";
            exit();
        }

        // Hash password sebelum disimpan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gunakan prepared statement untuk menghindari SQL Injection
        $stmt = $koneksi->prepare("INSERT INTO login (id, username, password, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $username, $password, $status);

        // Eksekusi query dan cek apakah berhasil
        if ($stmt->execute()) {
            header("Location: ../superadmin/data-akun.php");
            exit();
        } else {
            echo "<script>alert('Error: Could not create account.');</script>";
        }

        // Tutup statement dan koneksi
        $stmt->close();
        $koneksi->close();
    } else {
        // Jika data tidak ada di POST
        echo "<script>alert('Some form fields are missing.');</script>";
    }
}
?>