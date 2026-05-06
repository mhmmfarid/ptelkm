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

if (isset($_GET['kode_label'])) {
    $kode_label = $_GET['kode_label'];
    $query = mysqli_query($koneksi, "SELECT * FROM qr WHERE kode_label = '$kode_label'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo "Data Tidak Ditemukan";
        exit();
    }
} else {
    header('location:../admin/hal-data.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit QR</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
    h6, .form-label {
        color: #FD762F;
        font-weight: bold;
    }
    .btn-primary {
        background-color: #FFAC6D;
        border: none;
        padding: 10px 30px;
        border-radius: 50px;
    }
</style>
<body>
    <form action="../update/update-qr.php" method="post">
        <input type="hidden" name="kode_label" value="<?php echo $data['kode_label'] ?>">
        <div class="container d-flex justify-content-center align-items-center vh-100">
            <div class="card p-4 shadow-lg signup-form">
                <div class="progress-bar">
                    <h1>EDIT QR</h1>    
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="kode_label_display" class="form-label">Kode Label</label>
                        <input type="text" class="form-control" id="kode_label_display" name="kode_label_display" value="<?php echo $data['kode_label'] ?>" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="waktu" class="form-label">Waktu</label>
                    <input type="datetime-local" class="form-control" id="waktu" name="waktu" value="<?php echo date('Y-m-d\TH:i', strtotime($data['waktu'])); ?>" required onchange="updateStatus()">
                </div>

                <div class="mb-3">
                    <label for="kode_area" class="form-label">Kode Area</label>
                    <select class="form-select" id="kode_area" name="kode_area" required>
                        <option value="">Pilih Kode Area</option>
                        <?php
                        $sto_query = "SELECT * FROM sto";
                        $sto_result = mysqli_query($koneksi, $sto_query);
                        while ($sto = mysqli_fetch_assoc($sto_result)) {
                            $selected = ($sto['kode_area'] == $data['kode_area']) ? 'selected' : '';
                            echo "<option value='" . $sto['kode_area'] . "' $selected>" . $sto['kode_area'] . " - " . $sto['id_sto'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <input type="text" class="form-control" id="status" name="status" value="<?php echo $data['status']; ?>" readonly>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" value="update" class="btn btn-primary px-4">Save & Continue</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function updateStatus() {
            var waktu = document.getElementById('waktu').value;
            var statusField = document.getElementById('status');

            if (!waktu) {
                statusField.value = ''; 
                return;
            }

            var currentDateTime = new Date().toISOString().slice(0, 16);

            if (waktu > currentDateTime) {
                statusField.value = "on"; 
            } else {
                statusField.value = "pending"; 
            }
        }
    </script>
</body>
</html>
