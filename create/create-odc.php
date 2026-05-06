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
    <title>Create ODC</title>
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
    margin-left:80px;
    
}

/* Sidebar */
.sidebar {
    background-color: #faf9f4;
    padding: 30px;
    border-radius: 15px;
    width: 230px;
}

.progress-list {
    list-style-type: none;
    padding: 0;
    margin-top: 30px;
}

.progress-list li {
    font-size: 18px;
    padding: 10px 0;
    color: #555;
}

.progress-list li.active {
    color: #00796B;
    font-weight: bold;
}

/* Form Section */
.form-section {
    background-color: #FFFFFF;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    color: #8C92AC; /* Warna teks abu-abu */
}


.step-item::before {
    content: '';    
    position: absolute;
    left: 12px;
    height: 100%;
    width: 3px;
    background-color: #8C92AC; /* Garis vertikal abu-abu */
}


.step-text {
    font-size: 25px;
    margin-top: 10px;
    font-weight: bold;
    margin-left: -20px;
}

.step-item.active .step-text {
    color:#00796B; /* Teks aktif berwarna putih */
    align-items: center;
    justify-content: center;
    display: flex;
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
</style>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar (Progress) -->
            <div class="col-md-2 sidebar">
            <h4>Create <br> Alat Produksi</h4>

        <ul class="stepper list-unstyled">
            <li class="step-item active">
                <div class="step-circle"></div>
                <span class="step-text">ODC</span>
            </li>
        </ul>
            </div>

            <!-- Form Section -->
            <div class="col-md-9 form-section">
                <div class="gambar">
                    <img src="../aset/logo.png" alt="">
                </div>
                <h3 class="">Create New ODC</h3>
                <form id="odcForm" onsubmit="return validateKoordinat()" action="../simpan/simpan-odc.php" method="POST">
                    <div class="mb-3">
                        <label for="id_odc" class="form-label">ID ODC</label>
                        <input type="text" class="form-control" id="id_odc" name="id_odc" placeholder="ID ODC">
                    </div>
                    
                   

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="kode_jenis" class="form-label">Kode Jenis</label>
                            <input type="text" class="form-control" id="kode_jenis" name="kode_jenis" placeholder="Kode Jenis">
                        </div>
                       
                        <div class="col-md-6">
                        <label for="kode_area" class="form-label">Kode Area</label>
                        <select class="form-control" id="kode_area" name="kode_area">
                            <option value="">Pilih Kode Area</option>
                            <?php
                            include "../konek/koneksi.php"; // Include your database connection

                            // Query untuk mendapatkan kode_area dari tabel sto
                            $query = "SELECT kode_area FROM sto";
                            $result = mysqli_query($koneksi, $query);

                            // Mengisi dropdown dengan data dari database
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['kode_area'] . "'>" . $row['kode_area'] . "</option>";
                            }
                            ?>
                        </select>
</div>

                        <div class="col-md-6">
                            <label for="no_odc" class="form-label">no odc</label>
                            <input type="number" class="form-control" id="no_odc" name="no_odc" placeholder="">
                        </div>
                            <div class="col-md-6">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Lokasi">
                        </div>
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-6">
    <label for="nama_odc" class="form-label">Nama ODC</label>
    <input type="text" class="form-control" id="nama_odc" name="nama_odc" placeholder="Nama ODC" readonly>
</div>

                        <div class="col-md-6">
                            <label for="isi_port" class="form-label">Isi Port</label>
                            <input type="text" class="form-control" id="isi_port" name="isi_port" placeholder="Isi Port">
                        </div>
                       
                    </div>
                    <div class="row mb-3">
                    <div class="col-md-6">
                    <label for="tanggal_pemasangan" class="form-label">Tanggal Pemasangan</label>
                    <input type="date" class="form-control" id="tanggal_pemasangan" name="tanggal_pemasangan">
                       
                        </div>
                        <div class="col-md-6">
                            <label for="status_odc" class="form-label">Status ODC</label>
                            <select class="form-control" id="status_odc" name="status_odc">
                                <option value="Pending">Pending</option>
                                <option value="Active">Active</option>
                                <option value="Error">Error</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                    <label for="koordinat" class="form-label">Koordinat</label>
                    <input type="text" class="form-control" id="koordinat" name="koordinat" placeholder="Koordinat">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Ambil elemen input
    const kodeJenisInput = document.getElementById('kode_jenis');
    const kodeAreaInput = document.getElementById('kode_area');
    const noodcinput = document.getElementById('no_odc');
    const namaOdcInput = document.getElementById('nama_odc');

    // Fungsi untuk mengisi otomatis nama ODC
    function updateNamaODC() {
        const kodeJenis = kodeJenisInput.value.trim();
        const kodeArea = kodeAreaInput.value.trim();
        const noodc = noodcinput.value.trim();
        if (kodeJenis && kodeArea && noodc) {
            namaOdcInput.value = `ODC-${kodeJenis}-${kodeArea}/${noodc}`;
        } else {
            namaOdcInput.value = ''; // Kosongkan jika salah satu belum diisi
        }
    }

    // Tambahkan event listener pada input
    kodeJenisInput.addEventListener('input', updateNamaODC);
    kodeAreaInput.addEventListener('input', updateNamaODC);
    noodcinput.addEventListener('input', updateNamaODC);
</script>

</body>
</html>
