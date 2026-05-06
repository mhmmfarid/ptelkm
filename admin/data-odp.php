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
  <title>Sticky Sidebar & ODP Table</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    /* Sidebar styling */
    .sidebar {
      background-color: #C21807;
      width: 80px;
      height: 100vh;
      overflow: hidden;
      transition: width 0.3s ease-out, margin-left 0.3s ease-out;
      position: fixed;
    }

    /* Class to expand sidebar */
    .sidebar.expanded {
      width: 250px;
    }

    /* Sidebar Header */
    .sidebar-header .sidebar-title {
      font-weight: 600;
      color: #fff;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
    }

    /* Show sidebar title and nav-link text when expanded */
    .sidebar.expanded .sidebar-title,
    .sidebar.expanded .nav-link {
      opacity: 1;
    }

    /* Divider Styling */
    .divider {
      border-color: white;
      margin: 8px 0;
      transition: opacity 0.3s ease-in-out;
    }

    .dividers {
      border-color: black;
      margin: 8px 0;
      transition: opacity 0.3s ease-in-out;
    }
    /* Nav Links */
    .nav-link {
      font-size: 15px;
      font-weight: 500;
      color: #ddd;
      padding-right: 10px;
      position: relative;
      opacity: 0;
      transition: color 0.2s ease, background-color 0.2s ease, opacity 0.3s ease-in-out;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #fff;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 5px;
    }

    /* Right Border for Active Item */
    .nav-link.active::before,
    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 3px;
      height: 100%;
      background-color: #fff;
      transform: scaleY(0);
      transition: transform 0.3s ease-in-out;
    }
    .nav-link.active::before {
      transform: scaleY(1);
    }

    /* Icon Styling */
    .icon-size {
      font-size: 18px;
    }

    /* Show text only when expanded */
    .nav-link span {
      display: none;
    }
    .sidebar.expanded .nav-link span {
      display: inline;
    }

    /* Content Section Styling */
.content-section {
  transition: margin-left 0.3s ease-out;
  padding: 20px;
  margin-left: 80px; /* Default margin untuk sidebar yang tertutup */
}

.sidebar.expanded + .content-section {
  margin-left: 250px; /* Margin ketika sidebar terbuka */
}


    .table-responsive {
      max-height: 610px;
      overflow-y: auto;
      width: 100%;
      transition: margin-left 0.3s ease-out;
    }

    .table th, .table td {
      text-align: center;
      vertical-align: middle;
    }

    .map {
         
        height: 200px;
        width: 330px;
       
      }

      .position-sticky {
        z-index: 2; /* Menambah z-index pada header tabel dan kolom untuk prioritas visibilitas */
      }

    /* Small Column Styling */
    .small-column {
      width: 130px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .content-section {
        margin-left: 0;
      }

      .sidebar {
        width: 60px;
      }

      .sidebar.expanded {
        width: 200px;
      }
    }
    .tombol {
      padding: 10px 10px;
    }
  
    .tombol a{
      padding: 5px 10px;
      background-color: #C21807;
      border-radius: 10px;
      text-decoration: none;
      border: 2px solid #C21807;
    }
  
    .tombol a:hover{
      background-color: #C21807;
      color: white;
      border: 2px solid #C21807;
    }
    .table-dark{
      z-index: 999;
    }
   
  </style>
</head>
<body>

  <!-- Sidebar -->
  <nav class="sidebar bg-dark-purple text-white d-flex flex-column p-3" id="sidebar">
    <div class="sidebar-header text-center mb-4">
      <img src="../aset/logotok.png" alt="Logo" class="img-fluid mt-3" style="max-width: 80px; height: 80px;">
      <h5 class="sidebar-title">Telkom Indonesia</h5>
    </div>
    <ul class="nav flex-column">
      <li class="nav-item mb-2 position-relative">
        <a href="#analyticsSubmenu" class="nav-link text-white d-flex align-items-center dropdown-toggle active" data-bs-toggle="collapse" aria-expanded="false" aria-controls="analyticsSubmenu">
          <i class="fa-solid fa-screwdriver-wrench icon-size"></i>
          <span class="ms-3">Tools of Production</span>
          <i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul class="collapse list-unstyled ms-4" id="analyticsSubmenu">
          <li><a href="../admin/data-sto.php" class="nav-link text-white">STO</a></li>
          <li><a href="../admin/data-odc.php" class="nav-link text-white">ODC</a></li>
          <li><a href="../admin/data-odp.php" class="nav-link text-white">ODP</a></li>
        </ul>
      </li>
      <li class="nav-item mb-2">
      <a href="../admin/hal-qr.php" class="nav-link text-white d-flex align-items-center">
        <i class="fa-solid fa-qrcode icon-size"></i>
        <span class="ms-3">Data Label</span>
      </a>
    </li>
      <hr class="divider">
      <li class="nav-item mb-2">
        <a href="../admin/hal-admin.php" class="nav-link text-white d-flex align-items-center">
          <i class="fa-solid fa-house icon-size"></i>
          <span class="ms-3">Home</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Main Content Section -->
  <div class="content-section">
  <div class="d-flex mb-3 mt-2">
  <h3 class="me-3">Optical Distribution Point (ODP)</h3>
  <a href="../create/create-odp.php">
    <button class="btn btn-danger me-2"><i class="fa-solid fa-plus"></i> Create ODP</button>
  </a>

<!-- Tombol Filter yang Menampilkan Form Filter -->

<!-- Form Filter untuk Kode Area -->
<div class="mb-3">
  <select class="form-select" id="kodeAreaFilter" name="kode_area">
    <option value="">FILTER</option>
    <?php
    include '../konek/koneksi.php';
    
    // Query untuk mengambil kode_area dari tabel sto
    $query = "SELECT DISTINCT kode_area FROM sto"; 
    $result = mysqli_query($koneksi, $query);

    // Tampilkan kode_area sebagai opsi dalam dropdown
    while ($row = mysqli_fetch_array($result)) {
      echo "<option value='" . $row['kode_area'] . "'>" . $row['kode_area'] . "</option>";
    }
    ?>
  </select>
</div>


  



  <!-- Search Input -->
  <!-- Search Input -->
<div class="input-group w-auto ms-auto">
  <input type="text" class="form-control" placeholder="Search by ID ODP..." id="search-input">
  <button class="btn btn-outline-secondary" type="button" id="search-btn">
    <i class="fa fa-search"></i>
  </button>
</div>


</div>

    <hr class="dividers">
    <div class="table-responsive">
      <table class="table table-bordered table-striped mt-3" id="dataTable">
        <thead class="table-dark position-sticky top-0">
          <tr class="tr-head" >
            <th scope="col">ID ODP</th>
            <th scope="col">Kode Jenis</th>
            <th scope="col">Kode Area</th>
            <th scope="col">Nomor ODP</th>
            <th scope="col">ID ODC</th>
            <th scope="col">Tanggal Pengerjaan</th>
            <th scope="col" class="small-column">Koordinat</th>
            <th scope="col">Nama ODP</th>
            <th scope="col">Status ODP</th>
            <th scope="col">Kode Label</th>
            <th scope="col">Waktu</th>
            <th scope="col">Maps</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
          
        </thead>
        <tbody id="odp-results">
        <?php
include '../konek/koneksi.php';

// Ambil nilai pencarian dan filter kode_area dari query string
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$kode_area = isset($_GET['kode_area']) ? mysqli_real_escape_string($koneksi, $_GET['kode_area']) : '';

// Query untuk filter berdasarkan search atau kode_area
$query = "SELECT * FROM odp WHERE 1";

// Tambahkan filter pencarian jika ada
if (!empty($search)) {
    $query .= " AND id_odp LIKE '%$search%'";
}

// Tambahkan filter kode_area jika ada
if (!empty($kode_area)) {
    $query .= " AND kode_area = '$kode_area'";
}

// Eksekusi query dan tampilkan hasilnya
$tampil = mysqli_query($koneksi, $query);

if (mysqli_num_rows($tampil) > 0) {
    while ($data = mysqli_fetch_array($tampil)) {
        $coordinates = $data['koordinat'];

        // Ambil waktu dari database dan bandingkan dengan waktu saat ini
        $current_time = new DateTime($data['waktu'], new DateTimeZone('Asia/Jakarta'));
        $waktu = new DateTime($data['waktu'], new DateTimeZone('Asia/Jakarta'));

        // Jika waktu sudah lewat, perbarui status menjadi Pending
        if ($waktu < $current_time && $data['status_odp'] != 'Pending') {
            $update_query = "UPDATE odp SET status_odp = 'Pending' WHERE id_odp = '{$data['id_odp']}'";
            mysqli_query($koneksi, $update_query);
            $data['status_odp'] = 'Pending'; // Update status pada variabel
        } 
        // Jika waktu lebih besar dari sekarang, ubah status menjadi On
        elseif ($waktu > $current_time && $data['status_odp'] != 'On') {
            $update_query = "UPDATE odp SET status_odp = 'On' WHERE id_odp = '{$data['id_odp']}'";
            mysqli_query($koneksi, $update_query);
            $data['status_odp'] = 'On'; // Update status pada variabel
        }
        ?>
        <tr class="display-flex align-items-center justify-content-center" data-status="<?php echo $data['kode_area']; ?>">
            <td><?php echo $data['id_odp']; ?></td>
            <td><?php echo $data['kode_jenis']; ?></td>
            <td><?php echo $data['kode_area']; ?></td>
            <td><?php echo $data['nomor_odp']; ?></td>
            <td><?php echo $data['id_odc']; ?></td>
            <td><?php echo $data['tanggal_pengerjaan']; ?></td>
            <td><?php echo $data['koordinat']; ?></td>
            <td><?php echo $data['nama_odp']; ?></td>
            <td id="status-<?php echo $data['id_odp']; ?>">
              <?php echo $data['status_odp']; ?></td>
            <td><?php echo $data['kode_label']; ?></td>
            <td><?php echo $data['waktu']; ?></td>
            <td>
                <div id="map-<?php echo $data['id_odp']; ?>" class="map"></div>
                <script>
                    var coords = "<?php echo $coordinates; ?>".split(",");
                    var lat = parseFloat(coords[0].trim());
                    var lng = parseFloat(coords[1].trim());
                    var map = L.map('map-<?php echo $data['id_odp']; ?>').setView([lat, lng], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 18,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    var marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup('ODP Location: <br>ID ODP: <?php echo $data['id_odp']; ?><br>Kode Jenis: <?php echo $data['kode_jenis']; ?><br>Tanggal Pengerjaan: <?php echo $data['tanggal_pengerjaan']; ?>')
                        .openPopup();
                </script>
                <div class="tombol">
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $data['koordinat']; ?>" target="_blank" class="btn btn-primary btn-sm">Open Maps</a>
                </div>
            </td>
            <td><a href="../edit/edit-odp.php?id_odp=<?php echo $data['id_odp']; ?>"><button class="btn btn-primary btn-sm">Edit</button></a></td>
            <td><a href="../delete/delete-odp.php?id_odp=<?php echo $data['id_odp']; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><button class="btn btn-danger btn-sm">Delete</button></a></td>
        </tr>
        <?php
    }
} else {
    echo "<tr><td colspan='13' class='text-center'>No results found.</td></tr>";
}
?>

</tbody>




      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
 // Menangani perubahan pada dropdown filter kode_area
document.getElementById('kodeAreaFilter').addEventListener('change', function() {
    var kodeAreaValue = this.value;
    var searchValue = document.getElementById('search-input').value.trim();
    
    // Update URL untuk menyertakan filter kode_area dan search jika ada
    var url = window.location.pathname + '?kode_area=' + kodeAreaValue;
    if (searchValue) {
        url += '&search=' + encodeURIComponent(searchValue);
    }
    window.location.href = url;
});

// Menangani pencarian
document.getElementById('search-btn').addEventListener('click', function () {
    const searchValue = document.getElementById('search-input').value.trim();
    var kodeAreaValue = document.getElementById('kodeAreaFilter').value;
    
    if (searchValue || kodeAreaValue) {
        var url = window.location.pathname + '?search=' + encodeURIComponent(searchValue) + '&kode_area=' + encodeURIComponent(kodeAreaValue);
        window.location.href = url;
    } else {
        alert('Please enter a search term or select a filter.');
    }
});


    const sidebar = document.getElementById('sidebar');
    const hoverZone = document.createElement('div');
    hoverZone.classList.add('sidebar-hover-zone');
    sidebar.appendChild(hoverZone);

    // Add "expanded" class when hovering over the sidebar or the hover zone
    hoverZone.addEventListener('mouseenter', () => {
      sidebar.classList.add('expanded');
    });

    sidebar.addEventListener('mouseenter', () => {
      sidebar.classList.add('expanded');
    });

    // Remove "expanded" class when the cursor leaves both the sidebar and hover zone
    sidebar.addEventListener('mouseleave', () => {
      sidebar.classList.remove('expanded');
    });
    hoverZone.addEventListener('mouseleave', () => {
      sidebar.classList.remove('expanded');
    });

    // Adjust content margin when sidebar is toggled
    sidebar.addEventListener('transitionend', function() {
      if (sidebar.classList.contains('expanded')) {
        document.querySelector('.content-section').style.marginLeft = '250px';
      } else {
        document.querySelector('.content-section').style.marginLeft = '80px';
      }
    });
   


  </script>

</body>
</html>
