<?php
session_start(); // Memulai session untuk mengambil username
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}
$username = $_SESSION['username']; // Mengambil username dari session

// Import koneksi ke database
if (!isset($koneksi)) {
    include '../konek/koneksi.php';
}

// Ambil filter dari URL
$kode_area = isset($_GET['kode_area']) ? mysqli_real_escape_string($koneksi, $_GET['kode_area']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Query utama
$query = "SELECT * FROM odc WHERE 1=1";
if (!empty($kode_area)) {
    $query .= " AND kode_area = '$kode_area'";
}
if (!empty($search)) {
    $query .= " AND id_odc LIKE '%$search%'";
}
$query .= " ORDER BY id_odc ASC"; // Urutkan berdasarkan ID ODC
$tampil = mysqli_query($koneksi, $query);
if (!$tampil) {
    die("Query Error: " . mysqli_error($koneksi));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data ODP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
  margin-left: 40px; /* Default margin untuk sidebar yang tertutup */
}

.sidebar.expanded + .content-section {
  margin-left: 250px; /* Margin ketika sidebar terbuka */
}


    .table-responsive {
      max-height: 650px;
      overflow-y: auto;
      width: 100%;
      transition: margin-left 0.3s ease-out;
      width: 175vh;
    }

    .table th, .table td {
      text-align: center;
      vertical-align: middle;
    }

    .map {
        position: relative;  
        height: 140px;
        width: 200px;
        z-index: 1; /* Pastikan z-index lebih tinggi dari elemen lainnya */
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
        <a href="#analyticsSubmenu" class="active nav-link text-white d-flex align-items-center dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false" aria-controls="analyticsSubmenu">
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
  <div class="container my-4">
    <div class="d-flex mb-3 mt-2">
      <h3 class="me-3">Optical Distribution Cabinet (ODC)</h3>
      <a href="../create/create-odc.php">
        <button class="btn btn-danger me-2"><i class="fa-solid fa-plus"></i> Create ODC </button>
      </a>
      <a href="../admin/data-odc.php">
        <button class="btn btn-danger me-2"></i>refresh </button>
      </a>
      
      <div class="mb-3">
  <select class="form-select" id="kodeAreaFilter" name="kode_area">
    <option value="">FILTER</option>
    <?php
// Query untuk mengambil kode_area dari tabel odc
$query = "SELECT DISTINCT kode_area FROM odc"; 
$result = mysqli_query($koneksi, $query);

// Tampilkan kode_area sebagai opsi dalam dropdown
while ($row = mysqli_fetch_array($result)) {
  $selected = (isset($_GET['kode_area']) && $_GET['kode_area'] === $row['kode_area']) ? 'selected' : '';
  echo "<option value='" . $row['kode_area'] . "' $selected>" . $row['kode_area'] . "</option>";
}
?>

  </select>
</div>

      <!-- Search Input -->
      <div class="input-group w-auto ms-auto">
        <input type="text" class="form-control" placeholder="Search by ID ODC..." id="search-input">
        <button class="btn btn-outline-secondary" type="button" id="search-btn">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>

    <hr class="dividers">

        <div class="table-responsive">
      <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark position-sticky top-0">
          <tr>
            <th scope="col">ID ODC</th>
            <th scope="col">Nama ODC</th>
            <th scope="col">Kode Area</th>
            <th scope="col">Kode Jenis</th>
            <th scope="col">Isi Port</th>
            <th scope="col">no_odc</th>
            <th scope="col">Lokasi</th>
            <th scope="col">Koordinat</th>
            <th scope="col">Status ODC</th>
            <th scope="col">sisa port</th>
            <th scope="col">Tanggal Pemasangan</th>
            <th scope="col">maps</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
          </tr>
        </thead>
        <tbody id="odc-results">
      <?php
      while ($data = mysqli_fetch_array($tampil)) {
          // Extract coordinates from $data['koordinat']
          $coords = explode(',', $data['koordinat']);
          $latitude = isset($coords[0]) ? trim($coords[0]) : null;
          $longitude = isset($coords[1]) ? trim($coords[1]) : null;

          // Calculate remaining ports and status
          $odp_query = "SELECT COUNT(*) AS odp_count FROM odp WHERE id_odc = '" . $data['id_odc'] . "'";
          $odp_result = mysqli_query($koneksi, $odp_query);
          $odp_data = mysqli_fetch_array($odp_result);
          $odp_count = $odp_data['odp_count'];

          $remaining_ports = $data['isi_port'] - $odp_count;
          $status_odc = $remaining_ports <= 0 ? 'Off' : 'On';
      ?>
      <tr>
        <td><?php echo $data['id_odc']; ?></td>
        <td><?php echo $data['nama_odc']; ?></td>
        <td><?php echo $data['kode_area']; ?></td>
        <td><?php echo $data['kode_jenis']; ?></td>
        <td><?php echo $data['isi_port']; ?></td>
        <td><?php echo $data['no_odc']; ?></td>
        <td><?php echo $data['lokasi']; ?></td>
        <td><?php echo $data['koordinat']; ?></td>
        <td><?php echo $status_odc; ?></td>
        <td><?php echo $remaining_ports; ?></td>
        <td><?php echo $data['tanggal_pemasangan']; ?></td>
        <td>
          <?php if ($latitude && $longitude) { ?>
          <div id="map-<?php echo $data['id_odc']; ?>" style="height: 150px; width: 300px;"></div>
          <script>
            var map = L.map('map-<?php echo $data['id_odc']; ?>').setView([<?php echo $latitude; ?>, <?php echo $longitude; ?>], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map)
              .bindPopup('ODC Location: <?php echo $data['nama_odc']; ?>')
              .openPopup();
          </script>
          <?php } else { ?>
          No coordinates available
          <?php } ?>
        </td>
        <td><a href="../edit/edit-odc.php?id_odc=<?php echo $data['id_odc']; ?>"><button class="btn btn-primary btn-sm">Edit</button></a></td>
        <td><a href="../delete/delete-odc.php?id_odc=<?php echo $data['id_odc']; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><button class="btn btn-danger btn-sm">Delete</button></a></td>
      </tr>
      <?php } ?>
    </tbody>

      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
 // Handle dropdown change
 document.getElementById('kodeAreaFilter').addEventListener('change', function() {
    var kodeAreaValue = this.value;
    var searchValue = document.getElementById('search-input').value.trim();

    var url = window.location.pathname + '?kode_area=' + encodeURIComponent(kodeAreaValue);
    if (searchValue) {
      url += '&search=' + encodeURIComponent(searchValue);
    }
    window.location.href = url;
  });

  // Handle search button click
  document.getElementById('search-btn').addEventListener('click', function() {
    var kodeAreaValue = document.getElementById('kodeAreaFilter').value;
    var searchValue = document.getElementById('search-input').value.trim();

    var url = window.location.pathname + '?search=' + encodeURIComponent(searchValue);
    if (kodeAreaValue) {
      url += '&kode_area=' + encodeURIComponent(kodeAreaValue);
    }
    window.location.href = url;
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
    document.getElementById('search-btn').addEventListener('click', function() {
      const searchValue = document.getElementById('search-input').value.trim();

      if (searchValue) {
        fetch(`../search/search-odc.php?search=${searchValue}`)
          .then(response => response.text())
          .then(data => {
            document.getElementById('odc-results').innerHTML = data;
          })
          .catch(error => console.error('Error fetching search results:', error));
      } else {
        alert('Please enter an ID ODC to search.');
      }
    });

  </script>

</body>
</html>
