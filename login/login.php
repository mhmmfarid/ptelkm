<?php
session_start(); // Memulai session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $password = $_POST["password"];
    $status = $_POST["status"]; // Mendapatkan status dari combo box (User/Admin/Superadmin)

    // Sertakan file koneksi yang ada di dalam folder konek
    include '../konek/koneksi.php';

    // Menggunakan prepared statement untuk menghindari SQL Injection
    $stmt = $koneksi->prepare("SELECT * FROM login WHERE id = ? AND password = ? AND status = ?");
    $stmt->bind_param("sss", $id, $password, $status);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika data ditemukan
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $row['username'];
        $_SESSION['status'] = $row['status']; // Simpan status ke sesi

        // Redirect ke halaman sesuai status
        if ($status == "s.admin") {
            header("Location: ../superadmin/hal-sadmin.php"); // Halaman super admin
        } elseif ($status == "admin") {
            header("Location: ../admin/hal-admin.php"); // Halaman admin
        } else {
            header("Location: ../user/hal-user.php"); // Halaman user
        }
        exit();
    } else {
        // Jika login gagal, tampilkan pop-up
        echo "<script>alert('Login failed. Invalid credentials or incorrect status. Please try again.');</script>";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $koneksi->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login Telkom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    background-color: #f0f5ff; /* Light blue background */
}

.bg-primary {
    background-color: #1a2a6c !important; /* Dark blue */
}

.btn-primary {
    background-color: #1a2a6c;
    border-color: #1a2a6c;
}

img {
    max-width: 100%; /* Make images responsive */
}

input[type="email"], input[type="password"] {
    background-color: #f7f9fc;
    border-radius: 5px;
}
div .logo{
    width: 200px;
    align-items: left;
    padding-left: 0px;
    justify-content: left;
    margin-top: -5px;
}
h3{
    color: #1a2a6c;
    font-family: 'Lilita One', cursive;
    font-size: 30px;
    font-weight: bold;
    margin-top: -15px;
}
/* Styling untuk Combo Box Status */
select#status {
    background-color: #f7f9fc;
    color: #1a2a6c;
    border: 1px solid #1a2a6c;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
    appearance: none; /* Menghapus default browser dropdown arrow */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><polygon points="5,6 15,6 10,12" fill="%231a2a6c"/></svg>');
    background-position: right 10px center;
    background-repeat: no-repeat;
    background-size: 12px;
    cursor: pointer;
}

select#status:focus {
    outline: none;
    border-color: #00aaff;
    box-shadow: 0 0 5px rgba(0, 170, 255, 0.5);
}

select#status:hover {
    background-color: #e0ebff;
}

.gam{
    background : url(../aset/pp.jpg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: 100% 100%;
    background-color: #f0f5ff;
}

.container-fluid{
    height: 100vh;
    width: 100%;
}

label{
    font-size: 15px;
}
</style>
<body>
    <div class="awal container-fluid d-flex align-items-center justify-content-center bg-dark">
        <div class="row w-75">
            <!-- Left Section (Login Form) -->
            <div class="col-md-6 bg-light p-5">
            <div class="mb-2">
                       <img src="../aset/logo.png" alt="Logo" class="logo mb-5"> <!-- Replace with your logo -->
                </div>
                <h3 class="text-center mb-4">Welcome</h3>
               <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
                <div class="mb-3">
                    <input type="text" name="id" id="id" class="form-control" placeholder="Your ID" required>
                </div>
                <div class="mb-1">
                <input type="password" name="password" id="password" class="form-control" placeholder="Your Password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center ml-3 mb-3">
                <div>
                    <input type="checkbox" id="showPassword">
                    <label for="showPassword">Show your password</label>
                </div>
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

                <div class="mb-3">
                    <!-- Combo Box untuk memilih status (Admin/User) -->
                    <label for="status">Select Status:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="s.admin">s.admin</option>
                        <option value="user">User</option>
                       <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Log in</button>
                 </form>

            </div>

            <!-- Right Section (Illustration and Info) -->
            <div class="gam col-md-6 bg-primary">
            </div>
        </div>
    </div>
    <script>
        <?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika sesi tidak valid
    header("Location: ../login/login.php");
    exit();
}
?>

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
