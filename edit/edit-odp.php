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
if(isset($_GET['id_odp'])){
    $id_odp = $_GET['id_odp'];    
    include '../konek/koneksi.php';
    $query = mysqli_query($koneksi, "SELECT * From odp where id_odp = '$id_odp'");
    $data =mysqli_fetch_array($query);
    if(!$data){
        echo "Data Tidak Ditemukan";
        exit();
    }
}else{
    header('location:../admin/hal-data.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit ODP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    /* General Body Styles */
body {
    background : url('../aset/bg.jpg') no-repeat center center fixed;
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
    <form action="../update/update-odp.php" method="post">
    <input type="hidden" name="id_odp" value="<?php echo $data['id_odp']?>">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg signup-form">
            <div class="progress-bar">
                <h1>EDIT ODP</h1>    
            </div>

            <div class="row mb-3">
    <div class="col-md-12">
        <label for="id_odp" class="form-label">ID ODP</label>
        <input type="text" class="form-control" id="id_odp" name="id_odp" value="<?php echo $data['id_odp']?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="kode_jenis" class="form-label">Kode Jenis</label>
        <input type="text" class="form-control" id="kode_jenis" name="kode_jenis" value="<?php echo $data['kode_jenis']?>">
    </div>
    <div class="col-md-6">
        <label for="kode_area" class="form-label">Kode Area</label>
        <input type="text" class="form-control" id="kode_area" name="kode_area" value="<?php echo $data['kode_area']?>">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="nomor_odp" class="form-label">Nomor ODP</label>
        <input type="text" class="form-control" id="nomor_odp" name="nomor_odp" value="<?php echo $data['nomor_odp']?>">
    </div>
    <div class="col-md-6">
        <label for="id_odc" class="form-label">ID ODC</label>
        <input type="text" class="form-control" id="id_odc" name="id_odc" value="<?php echo $data['id_odc']?>">
    </div>
</div>

<div class="row mb-3">
<div class="col-md-6">
        <?php if (isset($_GET['kode_label_invalid']) && $_GET['kode_label_invalid'] == 'true') : ?>
            <span class="text-danger">Kode Label dan Waktu tidak valid.</span>
        <?php endif; ?>
        <label for="kode_label" class="form-label">Kode Label</label>
        <input type="text" class="form-control" id="kode_label" name="kode_label" placeholder="Kode Label" onkeyup="getWaktu()">

    </div>
    <div class="col-md-6">
        <label for="waktu" class="form-label">Waktu</label>
        <input type="datetime-local" class="form-control" id="waktu" name="waktu" value="<?php echo $data['waktu']?>">
    </div>
</div>

<div class="mb-3">
    <label for="tanggal_pengerjaan" class="form-label">Tanggal Pengerjaan</label>
    <input type="date" class="form-control" id="tanggal_pengerjaan" name="tanggal_pengerjaan" value="<?php echo $data['tanggal_pengerjaan']?>">
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="koordinat" class="form-label">Koordinat</label>
        <input type="text" class="form-control" id="koordinat" name="koordinat" value="<?php echo $data['koordinat']?>">
    </div>
    <div class="col-md-6">
        <label for="status_odp" class="form-label">Status ODP</label>
        <select class="form-control" id="status_odp" name="status_odp">
            <option value="Pending">Pending</option>
            <option value="on">on</option> 
            <option value="off">off</option> 
        </select>
    </div>

</div>


                <div class="d-flex justify-content-end">
                    <button type="submit" value="update" class="btn btn-primary px-4">Save & Continue</button>
                </div>
            </form>
        </div>
    </div>
</form>
<script>
    function getWaktu() {
    var kodeLabel = document.getElementById('kode_label').value;
    
    if(kodeLabel) {
        // Membuat AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "../create/get-waktu.php?kode_label=" + kodeLabel, true);
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                
                // Jika waktu ditemukan
                if(response.success) {
                    // Format waktu ke datetime-local format (YYYY-MM-DDTHH:MM)
                    var waktu = response.waktu;
                    var formattedWaktu = waktu.replace(' ', 'T').slice(0, 16); // Mengubah format jika perlu
                    document.getElementById('waktu').value = formattedWaktu;
                    updateStatusODP(); // Perbarui status berdasarkan waktu yang terisi
                } else {
                    // Kosongkan jika tidak ditemukan
                    document.getElementById('waktu').value = '';
                }
            }
        };
        xhr.send();
    } else {
        // Kosongkan jika kode_label kosong
        document.getElementById('waktu').value = '';
    }
}

// Fungsi untuk memeriksa waktu dan mengupdate status ODP
function updateStatusODP() {
    var waktuInput = document.getElementById('waktu').value;
    var statusODP = document.getElementById('status_odp');
    
    if (waktuInput) {
        var currentTime = new Date(); // Waktu saat ini
        var inputTime = new Date(waktuInput); // Waktu yang dimasukkan
        
        // Jika waktu lebih dari sekarang, set status ODP ke "on"
        if (inputTime > currentTime) {
            statusODP.value = "on"; // Menentukan status ODP menjadi "on"
        } else {
            statusODP.value = "Pending"; // Jika waktu lebih kecil dari sekarang, set status ke "Pending"
        }
    }
}

</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
