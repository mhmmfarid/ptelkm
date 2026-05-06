<?php
// Include the database connection
include '../konek/koneksi.php';

// Initialize search variable
$searchTerm = "";

// Check if the search term is set in the GET request
// Memeriksa apakah ada input pencarian
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search']; // Ambil nilai dari form pencarian

    // Query untuk mencari id_odp berdasarkan input pencarian
    $query = "SELECT odp.id_odp, odp.kode_jenis, odp.kode_area, odp.nomor_odp, odp.id_odc, odp.tanggal_pengerjaan, odp.koordinat, odp.status_odp, qr.kode_label
              FROM odp
              LEFT JOIN qr ON qr.kode_label = odp.kode_label
              WHERE odp.id_odp = ?"; // Gantilah LIKE menjadi = agar hanya data yang sesuai dengan id_odp yang ditampilkan
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $searchTerm); // Bind parameter pencarian
    $stmt->execute();
    $result = $stmt->get_result(); // Ambil hasil query
} else {
    // Jika tidak ada pencarian, ambil semua data
    $query = "SELECT odp.id_odp, odp.kode_jenis, odp.kode_area, odp.nomor_odp, odp.id_odc, odp.tanggal_pengerjaan, odp.koordinat, odp.status_odp, qr.kode_label
              FROM odp
              LEFT JOIN qr ON qr.kode_label = odp.kode_label";
    $result = $koneksi->query($query); // Eksekusi query untuk menampilkan semua data
}



// Get initial coordinates if any records exist
if ($result && $result->num_rows > 0) {
    $firstRow = $result->fetch_assoc(); // Get the first row of results
    $initialCoordinates = $firstRow['koordinat']; // Set initial coordinates to the first record's coordinates
    $result->data_seek(0); // Reset pointer back to the first row for future use
} else {
    $initialCoordinates = "-6.4023,106.7982"; // Default coordinates (Depok)
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps ODP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</head>
<style>
/* Custom styles for the modal, form, and buttons */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #4B007A;
    margin: 0;
    padding: 0;
    height: 100vh;
}

.side-panel {
    background: linear-gradient(135deg, #FF3A54 0%, #FF5A6F 100%);
    height: 100vh;
    box-shadow: 4px 0px 15px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

#map {
    background-color: #141A24;
    position: relative;
    height: 100vh;
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
}

input.form-control {
    border-radius: 25px;
    padding: 10px 20px;
    background-color: #fff;
    border: none;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

button.btn {
    border-radius: 25px;
    padding: 10px 20px;
    transition: all 0.3s ease;
    font-size: 14px;
}

button.btn-outline-light {
    border-color: #ffffff;
    color: #ffffff;
    box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
}

button.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0px 8px 20px rgba(255, 255, 255, 0.4);
}

.text-light {
    color: #FFF;
}

.modal-content {
    border-radius: 10px;
}

.modal-body {
    font-size: 16px;
}

.modal-header {
    background-color: #f7f7f7;
}
.transport-options {
    max-height: 600px; /* Set a fixed height */
    overflow-y: auto;  /* Enable vertical scrolling */
    padding-right: 15px; /* Optional padding for the scrollbar */
    border: 2px solid #fff;
    border-radius: 5px;
    padding: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
<body>
<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-lg-4 col-md-6 col-sm-12 side-panel">
            <div class="p-4">
                <div class="ats">
                    <a href="../user/hal-user.php"><button class="btn btn-light btn-sm" style="border-radius: 50%; padding: 5px 10px; border: none; cursor: pointer;">
                        &#60;
                    </button></a>
                    <h4 class="text-light mb-3">Optical Distribution Point (ODP)</h4>
                </div>

                <!-- Search Form -->
                <form method="GET" action="maps-odp.php">
    <input type="text" class="form-control mb-3" name="search" placeholder="Search id" value="<?php echo htmlspecialchars($searchTerm); ?>">
</form>

                <!-- Data Display -->
                <div class="transport-options">
<?php
// Memeriksa apakah query berhasil dan ada hasilnya
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $koordinat = $row['koordinat'];
        $status_odp = $row['status_odp']; // Mendapatkan status_odp untuk tombol kondisi
        ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex flex-column">
                <span class="text-light"><strong>ID ODP:</strong> <?php echo htmlspecialchars($row['id_odp']); ?></span>
                <span class="text-light"><strong>Kode Jenis:</strong> <?php echo htmlspecialchars($row['kode_jenis']); ?></span>
                <span class="text-light"><strong>Kode Area:</strong> <?php echo htmlspecialchars($row['kode_area']); ?></span>
                <span class="text-light"><strong>Nomor ODP:</strong> <?php echo htmlspecialchars($row['nomor_odp']); ?></span>
                <span class="text-light"><strong>ID ODC:</strong> <?php echo htmlspecialchars($row['id_odc']); ?></span>
                <span class="text-light"><strong>Tanggal Pengerjaan:</strong> <?php echo htmlspecialchars($row['tanggal_pengerjaan']); ?></span>
                <span class="text-light"><strong>Koordinat:</strong> <?php echo htmlspecialchars($row['koordinat']); ?></span>
                <span class="text-light"><strong>Status ODP:</strong> <?php echo htmlspecialchars($row['status_odp']); ?></span>
                <span class="text-light"><strong>Kode Label (QR):</strong> <?php echo htmlspecialchars($row['kode_label']); ?></span>
            </div>
        </div>
        <button class="btn btn-primary" onclick="showMap('<?php echo htmlspecialchars($koordinat); ?>', '<?php echo htmlspecialchars($row['id_odp']); ?>', '<?php echo htmlspecialchars($row['kode_jenis']); ?>', '<?php echo htmlspecialchars($row['tanggal_pengerjaan']); ?>')">Telusuri</button>

        <!-- Show the "Input Code Label" button only if status_odp is "pending" -->
        <?php if ($status_odp === 'Pending') { ?>
            <button class="btn btn-outline-light mt-2" onclick="openInputForm('<?php echo htmlspecialchars($row['id_odp']); ?>')">Input Code Label</button>
        <?php } ?>

        <?php
    }
} else {
    // Jika tidak ada data yang ditemukan
    echo "<p class='text-light'>No results found for ID ODP: " . htmlspecialchars($searchTerm) . "</p>";
}
?>
</div>

            </div>
        </div>

        <div class="col-lg-8 col-md-6 col-sm-12 p-0">
            <div id="map" class="h-100"></div>
        </div>
    </div>
</div>

<!-- Modal for Inputting Code Label -->
<div class="modal fade" id="modalInputCodeLabel" tabindex="-1" aria-labelledby="modalInputCodeLabelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInputCodeLabelLabel">Input Code Label</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <form id="formCodeLabel">
        <input type="hidden" name="id_odp" id="id_odp" value="">
        <div class="mb-3">
            <label for="kode_label" class="form-label">Kode Label</label>
            <select class="form-select" id="kode_label" name="kode_label" required>
                <!-- Opsi kode_label akan dimuat dari database -->
                <?php
                $query = "SELECT kode_label FROM qr";
                $result = $koneksi->query($query);
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['kode_label']) . "'>" . htmlspecialchars($row['kode_label']) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No available labels</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div id="responseMessage"></div> <!-- Tempat untuk menampilkan hasil respon dari server -->
</div>

        </div>
    </div>
</div>

<script>
document.getElementById('formCodeLabel').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form reload halaman

    var formData = new FormData(this); // Mengambil data dari form
    console.log("Form Data: ", formData);  // Debugging

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update-kode-label.php', true); // Kirim ke update-kode-label.php
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Menampilkan respon dari server
            document.getElementById('responseMessage').innerHTML = xhr.responseText;
        } else {
            // Menampilkan error jika ada masalah
            document.getElementById('responseMessage').innerHTML = "Terjadi kesalahan saat memperbarui data.";
        }
    };
    xhr.send(formData); // Kirim data ke server
});


// Initialize map
let map = L.map('map').setView([<?php echo htmlspecialchars($initialCoordinates); ?>], 13); // Set initial coordinates

// Adding tile layer (OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Function to show map with a zoom and marker at the clicked location
function showMap(koordinat, id_odp, kode_jenis, tanggal_pengerjaan) {
    // Split the coordinates into latitude and longitude
    let coords = koordinat.split(',');

    // Set the map view to the new coordinates with zoom level 16
    map.setView(new L.LatLng(coords[0], coords[1]), 16); // Increased zoom level for closer view

    // Add a marker at the coordinates and bind a popup with details
    L.marker([coords[0], coords[1]])
        .addTo(map)
        .bindPopup("<b>ID ODP:</b> " + id_odp + "<br><b>Kode Jenis:</b> " + kode_jenis + "<br><b>Tanggal Pengerjaan:</b> " + tanggal_pengerjaan)
        .openPopup();
}

function openInputForm(id_odp) {
    document.getElementById('id_odp').value = id_odp;
    new bootstrap.Modal(document.getElementById('modalInputCodeLabel')).show();
}
</script>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
