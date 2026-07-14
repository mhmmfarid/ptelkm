<?php
session_start(); // Memulai session untuk mengambil username
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}
$username = $_SESSION['username']; // Mengambil username dari session
?>
<?php
include '../konek/koneksi.php';
if(isset($_GET['id'])){
    $id = $_GET['id'];    
    include '../konek/koneksi.php';
    $query = mysqli_query($koneksi, "SELECT * From login where id = '$id'");
    $data =mysqli_fetch_array($query);
    if(!$data){
        echo "Data Tidak Ditemukan";
        exit();
    }
}else{
    header('location:../superadmin/data-akun.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* General Body Styles */
body {
    background-color: #FFE6C3;
    font-family: 'Arial', sans-serif;
}

.signup-form {
    background-color: #fff;
    border-radius: 15px;
    width: 900px;
    margin-bottom: 20px;
}

/* Progress Bar */
.progress-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-bar .step {
    width: 30%;
    text-align: center;
    font-weight: bold;
    color: #ccc;
}

.progress-bar .step.active {
    color: #FD762F;
}

/* Form */
h6 {
    color: #FD762F;
    font-weight: bold;
}

.form-label {
    color: #FD762F;
    font-weight: 500;
}

.btn-photo {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #FFAC6D;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
}

.btn-photo i {
    font-size: 24px;
}

textarea, input[type="text"], input[type="date"], select {
    border-radius: 10px;
    border: 1px solid #FFAC6D;
    padding: 10px;
}

/* Button */
.btn-primary {
    background-color: #FFAC6D;
    border: none;
    padding: 10px 30px;
    border-radius: 50px;
}

</style>

<body>
    <form action="../update/update-akun.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $data['id']?>">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg signup-form">
            <div class="progress-bar">
                <h1>EDIT AKUN</h1>    
            </div>

            <div class="row mb-3">
    <div class="col-md-12">
        <label for="id" class="form-label">ID</label>
        <input type="text" class="form-control" id="id" name="id" value="<?php echo $data['id']?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="username" class="form-label">username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $data['username']?>">
    </div>
    <div class="col-md-6">
        <label for="password" class="form-label">password</label>
        <input type="text" class="form-control" id="password" name="password" value="<?php echo $data['password']?>">
    </div>
</div>

<div class="row mb-3 align-items-center">
    <div class="col-md-4 text-center">
        <?php 
        $current_pic = (!empty($data['gambar']) && file_exists('../uploads/' . $data['gambar'])) ? '../uploads/' . $data['gambar'] : '../aset/pp.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($current_pic, ENT_QUOTES, 'UTF-8'); ?>" id="previewPic" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #FFAC6D;">
    </div>
    <div class="col-md-8">
        <label for="gambar" class="form-label">Foto Profil</label>
        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" onchange="previewImage(event)">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="status" class="form-label">Status</label>
        <select class="form-select" id="status" name="status">
            <option value="s.admin" <?php echo ($data['status'] == 's.admin') ? 'selected' : ''; ?>>S.Admin</option>
            <option value="admin" <?php echo ($data['status'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?php echo ($data['status'] == 'user') ? 'selected' : ''; ?>>User</option>
        </select>
    </div>
</div>


                <div class="d-flex justify-content-end">
                    <button type="submit" value="update" class="btn btn-primary px-4">Save & Continue</button>
                </div>
        </div>
    </div>
</form>
    <script>
        function previewImage(event) {
            const preview = document.getElementById('previewPic');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
