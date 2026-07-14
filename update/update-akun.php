<?php
include '../konek/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $status = $_POST['status'];

    // Check if new image is uploaded
    if (isset($_FILES['gambar']) && $_FILES['gambar']['name'] != '') {
        $gambar = $_FILES['gambar'];
        $gambar_name = $gambar['name'];
        $gambar_tmp = $gambar['tmp_name'];
        $gambar_ext = strtolower(pathinfo($gambar_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($gambar_ext, $allowed_ext)) {
            $gambar_new_name = uniqid('profile_', true) . '.' . $gambar_ext;
            $gambar_destination = '../uploads/' . $gambar_new_name;
            
            if (move_uploaded_file($gambar_tmp, $gambar_destination)) {
                // Delete old image if it exists and is valid
                $old_img_query = mysqli_query($koneksi, "SELECT gambar FROM login WHERE id = '$id'");
                if ($old_img_row = mysqli_fetch_assoc($old_img_query)) {
                    $old_gambar = $old_img_row['gambar'];
                    if (!empty($old_gambar) && file_exists('../uploads/' . $old_gambar)) {
                        unlink('../uploads/' . $old_gambar);
                    }
                }
                
                // Update with new image
                $update_query = "UPDATE login SET
                    username = '$username',
                    password = '$password',
                    status = '$status',
                    gambar = '$gambar_new_name'
                WHERE id = '$id'";
            } else {
                // Fallback to update without image if upload failed
                $update_query = "UPDATE login SET
                    username = '$username',
                    password = '$password',
                    status = '$status'
                WHERE id = '$id'";
            }
        } else {
            // Invalid extension, update without image
            $update_query = "UPDATE login SET
                username = '$username',
                password = '$password',
                status = '$status'
            WHERE id = '$id'";
        }
    } else {
        // No new image, update other details
        $update_query = "UPDATE login SET
            username = '$username',
            password = '$password',
            status = '$status'
        WHERE id = '$id'";
    }

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
