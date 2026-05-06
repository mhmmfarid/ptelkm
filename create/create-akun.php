<?php
session_start(); // Memulai session untuk mengambil username
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}
$username = $_SESSION['username']; // Mengambil username dari session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<style>
    /* General Body Style */
body {
    background:url('../aset/bg.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'poppins', sans-serif;
}
/* Form Section */
.form-section {
    background-color: #FFFFFF;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 65px;
    margin-left: 170px;
}

.form-label {
    font-weight: 500;
    color: #00796B;
}

.input-group-text {
    background-color: #00796B;
    color: white;
    border: none;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #C3E5E3;
}

.btn-success {
    background-color: #00796B;
    border: none;
    padding: 10px 20px;
    border-radius: 50px;
    font-weight: bold;
}
.odp{
    align-items: center;
    justify-content: center;
    display: flex;
}
.sidebar img{
    height: 100px;
    width: 100px;
    margin: -10px;
    margin-top: -25px;
    margin-bottom: -20px;
}
.stepper {
    position: relative;
    padding-left: 30px;
    margin-top: 30px;
}

.step-item {
    position: relative;
    padding-left: 17px;
    padding-bottom: 45px;
    color: #8C92AC; /* Warna teks abu-abu */
}

.step-item:last-child {
    padding-bottom: 0;
}

.step-item::before {
    content: '';    
    position: absolute;
    top: 0;
    left: 12px;
    height: 100%;
    width: 3px;
    background-color: #8C92AC; /* Garis vertikal abu-abu */
}

.step-item.active::before,
.step-item.active .step-circle {
    background-color: #8C92AC; /* Garis dan lingkaran aktif putih */
}

.step-circle {
    position: absolute;
    top: 0;
    left: 0;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background-color: #8C92AC; /* Lingkaran abu-abu */
    border: 3px solid #8C92AC;
}

.step-item.active .step-circle {
    background-color: #00796B; /* Lingkaran aktif transparan */
    border: 3px solid #00796B ; /* Lingkaran aktif putih */
}

.step-text {
    margin-left: 35px;
    font-size: 18px;
}

.step-item.active .step-text {
    color:#00796B; /* Teks aktif berwarna putih */
}
.gambar{
    align-items: right;
    justify-content: right;
    display: flex;
}
.gambar img{
    height: 50px;
    width: 150px;
}
h4{
    text-align: center;
    font-size : 23px;
    font-weight: bold;
    color: #00796B;
    margin-top: 10px;
}
h3{
    font-weight: bold;
    margin-top: -30px;
}
checkbox{
    display: none;
    width: 100px;
    height: 100px;
    margin-top: -20px;
}
</style>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Form Section -->
            <div class="col-md-9 form-section">
                <div class="gambar">
                    <img src="../aset/logo.png" alt="">
                </div>
                <h3 class="">Create New Account</h3>
                <form action="../simpan/simpan-akun.php" method="POST">
    <div class="mb-3">
        <label for="id" class="form-label">ID Account</label>
        <input type="text" class="form-control" id="id" name="id" placeholder="ID Account">
    </div>
    <div class="mb-3">
        <label for="username" class="form-label">Nama</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Nama">
    </div>
    <div class="mb-1">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="mb-3">
        <input type="checkbox" id="showPassword">
        <label for="showPassword">Show Password</label>
    </div>
    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
        var passwordInput = document.getElementById('password');
        if (this.checked) {
            passwordInput.type = 'text';
        } else {
            passwordInput.type = 'password';
        }
        });
    </script>
    <label for="status" class="form-label">Status</label>
    <select class="form-select" id="status" name="status">
        <option value="admin">Admin</option>
        <option value="user">User</option>
    </select>
    <button type="submit" class="btn btn-success mt-3">Submit</button>
</form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
