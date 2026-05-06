<?php
session_start(); // Memulai session
if (!isset($_SESSION['username'])) {
    // Redirect ke halaman login jika session username tidak ada
    header('Location: ../login/login.php');
    exit();
}

include '../konek/koneksi.php'; // Hubungkan ke database

// Ambil nilai filter dari URL
$kode_area = isset($_GET['kode_area']) ? mysqli_real_escape_string($koneksi, $_GET['kode_area']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Query dengan filter
$query = "
    SELECT 
        sto.id_sto, 
        sto.spesifikasi, 
        sto.kode_area, 
        odp.nama_odp, 
        odp.koordinat
    FROM sto
    LEFT JOIN odp ON sto.kode_area = odp.kode_area
    WHERE 1=1
";

if ($kode_area !== '') {
    $query .= " AND sto.kode_area = '$kode_area'";
}
if ($search !== '') {
    $query .= " AND sto.id_sto LIKE '%$search%'";
}

$query .= " ORDER BY sto.id_sto, odp.nama_odp ASC";

$result = mysqli_query($koneksi, $query);

// Proses hasil query
$data_grouped = [];
while ($row = mysqli_fetch_assoc($result)) {
    $id_sto = $row['id_sto'];
    if (!isset($data_grouped[$id_sto])) {
        $data_grouped[$id_sto] = [
            'spesifikasi' => $row['spesifikasi'],
            'kode_area' => $row['kode_area'],
            'odp' => []
        ];
    }

    if (!empty($row['nama_odp'])) {
        $data_grouped[$id_sto]['odp'][] = [
            'nama_odp' => $row['nama_odp'],
            'koordinat' => $row['koordinat']
        ];
    }
}
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
        margin-right: -120px;
        height: 140px;
        width: 250px;
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
    <h3 class="me-3">Sentral Telepon Otomat (STO)</h3>
    <a href="../create/create-sto.php">
      <button class="btn btn-danger me-2"><i class="fa-solid fa-plus"></i> Create STO</button>
    </a>
    <a href="../admin/data-sto.php">
      <button class="btn btn-danger me-2">refresh</button>
    </a>

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
    <div class="input-group w-auto ms-auto">
      <input type="text" class="form-control" placeholder="Search by ID STO..." id="search-input">
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
          <th scope="col">ID STO</th>
          <th scope="col">Spesifikasi</th>
          <th scope="col">Kode Area</th>
          <th scope="col">ODP Terdaftar</th>
          <th scope="col-6">Maps</th>
          <th scope="col">Edit</th>
          <th scope="col">Delete</th>
        </tr>
      </thead>
      <tbody id="sto-results">
      <?php foreach ($data_grouped as $id_sto => $data) { ?>
        <tr>
          <td><?php echo $id_sto; ?></td>
          <td><?php echo $data['spesifikasi']; ?></td>
          <td><?php echo $data['kode_area']; ?></td>
          <td>
            <?php if (!empty($data['odp'])) { ?>
              <ul>
                <?php foreach ($data['odp'] as $odp) { ?>
                  <li><?php echo htmlspecialchars($odp['nama_odp']); ?></li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              No ODP registered
            <?php } ?>
          </td>
          <td>
            <?php if (!empty($data['odp'])) { ?>
              <div id="map-<?php echo $id_sto; ?>" class="map" style="height: 150px; width: 300px;"></div>
              <script>
                (function() {
                  var map = L.map('map-<?php echo $id_sto; ?>').setView([<?php echo $data['odp'][0]['koordinat']; ?>], 16);
                  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '© OpenStreetMap' }).addTo(map);

                  <?php foreach ($data['odp'] as $odp) {
                    if (!empty($odp['koordinat'])) {
                      $coords = explode(',', $odp['koordinat']);
                      $latitude = trim($coords[0]);
                      $longitude = trim($coords[1]); ?>
                      L.marker([<?php echo $latitude; ?>, <?php echo $longitude; ?>]).addTo(map).bindPopup('ODP: <?php echo htmlspecialchars($odp['nama_odp']); ?>');
                    <?php }
                  } ?>
                })();
              </script>
            <?php } ?>
          </td>
          <td><a href="../edit/edit-sto.php?id_sto=<?php echo $id_sto; ?>" class="btn btn-primary btn-sm">Edit</a></td>
          <td><a href="../delete/delete-sto.php?id_sto=<?php echo $id_sto; ?>" onclick="return confirm('Are you sure?');" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
      <?php } ?>
    </tbody>
    </table>
  </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
