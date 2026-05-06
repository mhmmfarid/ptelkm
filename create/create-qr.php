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
            <img src="../aset/logo.png" alt="Logo" style="width: 100px;">
        </div>
        <h3>Create New QR</h3>
        <form action="../simpan/simpan-qr.php" method="POST">
            <!-- Kode Label -->
            <div class="mb-3">
                <label for="kode_label" class="form-label">Kode Label</label>
                <input type="text" class="form-control" id="kode_label" name="kode_label" placeholder="Kode Label" required>
            </div>
            
            <!-- Waktu -->
            <div class="mb-3">
        <label for="waktu" class="form-label">Waktu</label>
        <input type="datetime-local" class="form-control" id="waktu" name="waktu" placeholder="Waktu" required onchange="updateStatus()">
    </div>

            <!-- Kode Area (Dropdown) -->
            <div class="mb-3">
                <label for="kode_area" class="form-label">Kode Area</label>
                <select class="form-select" id="kode_area" name="kode_area" required>
                    <option value="">Pilih Kode Area</option>
                    <?php
                    // Koneksi ke database dan query untuk ambil kode_area dari tabel sto
                    include '../konek/koneksi.php';
                    $sto_query = "SELECT * FROM sto";
                    $sto_result = mysqli_query($koneksi, $sto_query);

                    // Menampilkan kode_area dari tabel sto
                    while ($sto = mysqli_fetch_assoc($sto_result)) {
                        echo "<option value='" . $sto['kode_area'] . "'>" . $sto['kode_area'] . " - " . $sto['id_sto'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status" placeholder="Status" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success mt-3" name="submit">Simpan</button>
        </form>
    </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to update the status based on the selected time
      // Function to update the status based on the selected datetime
function updateStatus() {
    var waktu = document.getElementById('waktu').value;
    var statusField = document.getElementById('status');
    var currentDateTime = new Date().toISOString().slice(0, 16); // Get current date and time in 'YYYY-MM-DDTHH:MM' format

    if (waktu > currentDateTime) {
        statusField.value = "On"; // Set status to 'ON' if the datetime is in the future
    } else {
        statusField.value = "Pending"; // Set status to 'pending' if the datetime is in the past or present
    }
}

    </script>
</body>
</html>