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
if(isset($_GET['id_sto'])){
    $id_sto = $_GET['id_sto'];    
    include '../konek/koneksi.php';
    $query = mysqli_query($koneksi, "SELECT * From sto where id_sto = '$id_sto'");
    $data =mysqli_fetch_array($query);
    if(!$data){
        echo "Data Tidak Ditemukan";
        exit();
    }
}else{
    header('location:../admin/data-sto.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit STO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* General Body Styles */
body {
    background: url('../aset/bg.jpg') no-repeat center center fixed;
    font-family: 'Arial', sans-serif;
    background-size: cover;
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
<form action="../update/update-sto.php" method="post">
        <input type="hidden" name="id_sto" value="<?php echo $data['id_sto']?>">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4 shadow-lg signup-form">
                <div class="progress-bar">
                    <h1>Edit STO</h1>    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="id_sto" class="form-label">ID STO</label>
                        <input type="text" class="form-control" id="id_sto" name="id_sto" value="<?php echo $data['id_sto']?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="spesifikasi" class="form-label">Spesifikasi</label>
                        <input type="text" class="form-control" id="spesifikasi" name="spesifikasi" value="<?php echo $data['spesifikasi']?>">
                    </div>
                    <div class="col-md-6">
                        <label for="kode_area" class="form-label">Kode Area</label>
                        <input type="text" class="form-control" id="kode_area" name="kode_area" value="<?php echo $data['kode_area']?>">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" value="update" class="btn btn-primary px-4">Save & Continue</button>
                </div>
            </div>
        </div>
    </form>
        </div>
    </div>
</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
