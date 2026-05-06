<?php
session_start(); // Memulai session untuk mengambil username
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}
$username = $_SESSION['username'];
 // Mengambil username dari session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create ODP</title>
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
    margin-top:80px;
    margin-left:50px;
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

<!-- Modal Pop-Up untuk Peringatan -->
<div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="warningModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Format koordinat yang Anda masukkan tidak valid. Harap masukkan koordinat yang benar, seperti -6.2000, 106.8167.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Pop-Up untuk Peringatan ID ODP Sudah Ada -->
<div class="modal fade" id="idExistsModal" tabindex="-1" aria-labelledby="idExistsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="idExistsModalLabel">Peringatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ID ODP yang Anda masukkan sudah ada dalam database. Silakan gunakan ID yang berbeda.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar (Progress) -->
            <div class="col-md-2 sidebar">
            <h4>Create <br> Alat Produksi</h4>

        <ul class="stepper list-unstyled">
            <li class="step-item active">
                <div class="step-circle"></div>
                <span class="step-text">ODP</span>
            </li>
        </ul>
            </div>

            <!-- Form Section -->
            <div class="col-md-9 form-section">
                <div class="gambar">
                    <img src="../aset/logo.png" alt="">
                </div>
                <h3 class="">Create New ODP</h3>
                <form id="odpForm" onsubmit="return validateKoordinat()" action="../simpan/simpan-odp.php" method="POST">

                    <!-- National Identity Number -->
                    <div class="mb-3">
                    <label for="id_odp" class="form-label">ID ODP</label>
                    <input type="text" class="form-control" id="id_odp" name="id_odp" placeholder="ID ODP">
                    </div>

                    <!-- Name Inputs -->
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
                    </div>

                    <!-- Residential Address -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                        <label for="nomor_odp" class="form-label">Nomor ODP</label>
                            <input type="text" class="form-control" id="nomor_odp" name="nomor_odp" placeholder="Nomor ODP">
                        </div>
                        <div class="col-md-6">
                        <label for="id_odc" class="form-label">ID ODC</label>
                            <select class="form-control" id="id_odc" name="id_odc">
                             <option value="">Pilih ID ODC</option>
                            <?php
                            // Query untuk mendapatkan id_odc dari tabel odc
                            $query_odc = "SELECT id_odc FROM odc";
                             $result_odc = mysqli_query($koneksi, $query_odc);

                             // Mengisi dropdown dengan data dari database
                            while ($row_odc = mysqli_fetch_assoc($result_odc)) {
                                echo "<option value='" . $row_odc['id_odc'] . "'>" . $row_odc['id_odc'] . "</option>";
                            }
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_pengerjaan" class="form-label">Tanggal Pengerjaan</label>
                            <input type="date" class="form-control" id="tanggal_pengerjaan" name="tanggal_pengerjaan" placeholder="Koordinat">
                        </div>
                        <div class="col-md-6">
                            <label for="koordinat" class="form-label">Koordinat</label>
                            <input type="text" class="form-control" id="koordinat" name="koordinat" placeholder="Koordinat">
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
                            <input type="datetime-local" class="form-control" id="waktu" name="waktu" placeholder="waktu">
                        </div>
                    </div>

                    <div class="row mb-3">
                    <div class="col-md-6">
        <label for="status_odp" class="form-label">Status ODP</label>
        <select class="form-control" id="status_odp" name="status_odp">
            <option value="Pending">Pending</option>
            <option value="on">on</option> 
            <option value="off">off</option> 
        </select>
    </div>
                        <div class="col-md-6">
                            <label for="nama_odp" class="form-label">Nama ODP Otomatis</label>
                            <input type="text" class="form-control" id="nama_odp" name="nama_odp" placeholder="Nama ODP" readonly>
                        </div>                        
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success mt-3">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateNamaODP() {
            const kodeArea = document.getElementById("kode_area").value;
            const kodeJenis = document.getElementById("kode_jenis").value;
            const nomorODP = document.getElementById("nomor_odp").value;

            // Concatenate the values to create nama_odp
            const namaODP = `ODP-${kodeArea}-${kodeJenis}/${nomorODP}`.toUpperCase();
            
            // Set the value of nama_odp input field
            document.getElementById("nama_odp").value = namaODP;
        }

        // Add event listeners to input fields
        document.getElementById("kode_area").addEventListener("input", updateNamaODP);
        document.getElementById("kode_jenis").addEventListener("input", updateNamaODP);
        document.getElementById("nomor_odp").addEventListener("input", updateNamaODP);

        function validateKoordinat() {
            const koordinatInput = document.getElementById("koordinat").value;
            const koordinatPattern = /^-?([1-8]?[0-9](\.\d+)?|90(\.0+)?),\s?-?((1[0-7][0-9]|[1-9]?[0-9])(\.\d+)?|180(\.0+)?)$/;

            if (!koordinatPattern.test(koordinatInput)) {
                // If coordinates are invalid, show warning modal
                const warningModal = new bootstrap.Modal(document.getElementById('warningModal'));
                warningModal.show();
                return false;  // Prevent form submission
            }
            return true;  // Proceed with submission if coordinates are valid
        }

        if (window.location.search.includes('id_exists=true')) {
            const idExistsModal = new bootstrap.Modal(document.getElementById('idExistsModal'));
            idExistsModal.show();
        }

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
</body>
</html>
