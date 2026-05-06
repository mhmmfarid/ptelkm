<?php
session_start();

// Pastikan pengguna telah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='../login/login.php';</script>";
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

// Periksa apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $gender = $_POST['gender'];
    $tanggal_lahir = $_POST['tanggal_lahir'];

    // Validasi sederhana
    if (empty($nama) || empty($email) || empty($no_hp) || empty($gender) || empty($tanggal_lahir)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
        exit();
    }

    // Hubungkan ke database
    include '../konek/koneksi.php';

    // Proses upload gambar
    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar'];
        $gambar_name = $gambar['name'];
        $gambar_tmp = $gambar['tmp_name'];
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($gambar_ext, $allowed_ext)) {
            $gambar_new_name = uniqid('profile_', true) . '.' . $gambar_ext;
            $gambar_destination = '../uploads/' . $gambar_new_name;
            move_uploaded_file($gambar_tmp, $gambar_destination);

            // Update gambar di database
            $stmt = $koneksi->prepare("UPDATE login SET nama = ?, email = ?, no_hp = ?, gender = ?, tanggal_lahir = ?, gambar = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $nama, $email, $no_hp, $gender, $tanggal_lahir, $gambar_new_name, $user_id);
        }
    } else {
        // Jika gambar tidak diubah, cukup update data lainnya
        $stmt = $koneksi->prepare("UPDATE login SET nama = ?, email = ?, no_hp = ?, gender = ?, tanggal_lahir = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nama, $email, $no_hp, $gender, $tanggal_lahir, $user_id);
    }

    if ($stmt->execute()) {
        // Ambil status pengguna setelah update data
        $status_query = "SELECT status FROM login WHERE id = ?";
        $stmt_status = $koneksi->prepare($status_query);
        $stmt_status->bind_param("i", $user_id);
        $stmt_status->execute();
        $result_status = $stmt_status->get_result();

        if ($result_status->num_rows > 0) {
            $user_data = $result_status->fetch_assoc();
            $status = $user_data['status'];

            // Redirect berdasarkan status pengguna
            if ($status == 'admin') {
                echo "<script>alert('Data berhasil diperbarui!'); window.location.href='../admin/hal-admin.php';</script>";
            } else {
                echo "<script>alert('Data berhasil diperbarui!'); window.location.href='../user/hal-user.php';</script>";
            }
        }

        // Tutup status query
        $stmt_status->close();
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data!');</script>";
    }

    // Tutup koneksi
    $stmt->close();
    $koneksi->close();
}
?>
