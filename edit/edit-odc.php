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
if(isset($_GET['id_odc'])){
    $id_odc = $_GET['id_odc'];    
    include '../konek/koneksi.php';
    $query = mysqli_query($koneksi, "SELECT * From odc where id_odc = '$id_odc'");
    $data =mysqli_fetch_array($query);
    if(!$data){
        echo "Data Tidak Ditemukan";
        exit();
    }
}else{
    header('location:../admin/data-odc.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ODC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* General Body Styles */
body {
    background: url('../aset/bg.jpg') no-repeat center center fixed;
    background-size: cover;
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
    <form action="../update/update-odc.php" method="post">
    <input type="hidden" name="id_odc" value="<?php echo $data['id_odc']?>">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg signup-form">
            <div class="progress-bar">
                <h1>EDIT ODC</h1>    
            </div>

            <div class="row mb-3">
    <div class="col-md-12">
        <label for="id_odc" class="form-label">ID ODC</label>
        <input type="text" class="form-control" id="id_odc" name="id_odc" value="<?php echo $data['id_odc']?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="nama_odc" class="form-label">Nama ODC</label>
        <input type="text" class="form-control" id="nama_odc" name="nama_odc" value="<?php echo $data['nama_odc']?>">
    </div>
    <div class="col-md-6">
        <label for="kode_area" class="form-label">Kode Area</label>
        <input type="text" class="form-control" id="kode_area" name="kode_area" value="<?php echo $data['kode_area']?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="kode_jenis" class="form-label">Kode Jenis</label>
        <input type="text" class="form-control" id="kode_jenis" name="kode_jenis" value="<?php echo $data['kode_jenis']?>">
    </div>  
    <div class="col-md-6">
        <label for="isi_port" class="form-label">Isi Port</label>
        <input type="text" class="form-control" id="isi_port" name="isi_port" value="<?php echo $data['isi_port']?>">
    </div>
</div>

<div class="mb-3">
    <label for="lokasi" class="form-label">Lokasi</label>
    <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo $data['lokasi']?>">
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="koordinat" class="form-label">Koordinat</label>
        <input type="text" class="form-control" id="koordinat" name="koordinat" value="<?php echo $data['koordinat']?>">
    </div>
    <div class="col-md-6">
        <label for="status_odp" class="form-label">Status</label>
        <select class="form-control" id="status_odp" name="status_odp">
            <option value="Pending" <?php echo ($data['status_odc'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Active" <?php echo ($data['status_odc'] == 'Active') ? 'selected' : ''; ?>>Active</option>
            <option value="Error" <?php echo ($data['status_odc'] == 'Error') ? 'selected' : ''; ?>>Error</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan</label>
        <input type="date" class="form-control" id="tanggal_pemasangan" name="tanggal_pemasangan" value="<?php echo $data['tanggal_pemasangan']?>">
    </div>
    
</div>


                <div class="d-flex justify-content-end">
                    <button type="submit" value="update" class="btn btn-primary px-4">Save & Continue</button>
                </div>
            </form>
        </div>
    </div>
</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
