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
        position: relative;  
        height: 140px;
        width: 200px;
        z-index: 1; /* Pastikan z-index lebih tinggi dari elemen lainnya */
        margin-top: 10px;
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
        <a href="#analyticsSubmenu" class="nav-link text-white d-flex align-items-center dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false" aria-controls="analyticsSubmenu">
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
      <a href="../admin/hal-qr.php" class="active nav-link text-white d-flex align-items-center">
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
    <h3 class="me-3">Data QR</h3>
    <a href="../create/create-qr.php">
      <button class="btn btn-danger me-2"><i class="fa-solid fa-plus"></i> Create QR</button>
    </a>

    <!-- Button Filter -->
    <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#filterModal"><i class="fa-solid fa-filter icon-size"></i> Filter</button>


    <!-- Search Input -->
    <div class="input-group w-auto ms-auto">
      <input type="text" class="form-control" placeholder="Search by ID STO..." id="search-input">
      <button class="btn btn-outline-secondary" type="button" id="search-btn">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>

    <div style="max-height: 700px; overflow-y: auto;">
    <h2>Waktu saat ini: <span id="current_time"></span></h2>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Kode Label</th>
                    <th scope="col">Waktu</th>
                    <th scope="col">Kode Area</th>
                    <th scope="col">QR</th>
                    <th scope="col">Status</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
    <?php
    include '../konek/koneksi.php';

    // Query untuk mengambil data QR dari database
    $query = "SELECT * FROM qr";
    $result = mysqli_query($koneksi, $query);

    // Loop untuk menampilkan data QR
    while ($data = mysqli_fetch_array($result)) {
        // Waktu saat ini sesuai zona waktu Bogor/Jakarta
        $current_time = new DateTime(null, new DateTimeZone('Asia/Jakarta'));
        
        // Waktu dari database
        $waktu = new DateTime($data['waktu'], new DateTimeZone('Asia/Jakarta')); // Pastikan zona waktu juga digunakan untuk waktu dari database

       
        // Jika waktu sudah lewat, update status menjadi "Pending"
        if ($waktu < $current_time && $data['status'] !== 'Pending') {
            // Update status di database menggunakan prepared statement
            $stmt = $koneksi->prepare("UPDATE qr SET status = 'Pending' WHERE kode_label = ?");
            $stmt->bind_param("s", $data['kode_label']);
            if ($stmt->execute()) {
                $data['status'] = 'Pending'; // Update status di tampilan
                echo "Status diubah menjadi Pending.<br>"; // Debug
            } else {
                echo "Error: " . $stmt->error . "<br>"; // Debug
            }
            $stmt->close(); // Tutup prepared statement
        }
    ?>
        <tr>
            <td><?php echo $data['kode_label']; ?></td>
            <td>
                <?php 
                // Mengatur zona waktu Jakarta untuk menampilkan waktu dengan format 24 jam
                $dateTime = new DateTime($data['waktu'], new DateTimeZone('Asia/Jakarta'));
                echo $dateTime->format('Y-m-d H:i'); 
                ?>
            </td>
            <td><?php echo $data['kode_area']; ?></td>

            <td>
                <?php
                // Generate the QR code URL
                $kode_label = $data['kode_label'] . ", " . $data['waktu'] . ", " . $data['kode_area'] . ", " . $data['status'];
                $qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($kode_label) . "&size=150x150";
                ?>
                <img src="<?php echo $qr_url; ?>" alt="QR Code">
            </td>
            <td><?php echo $data['status']; ?></td>
            <td>
                <a href="../edit/edit-qr.php?kode_label=<?php echo $data['kode_label']; ?>">
                    <button class="btn btn-primary btn-sm">Edit</button>
                </a>
            </td>
            <td>
                <a href="../delete/delete-qr.php?kode_label=<?php echo $data['kode_label']; ?>" onclick="return confirm('Anda yakin ingin menghapus data ini?');">
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </a>
            </td>
        </tr>
    <?php
    }
    ?>
</tbody>

        </table>
    </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
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
    function updateTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Format waktu seperti 'YYYY-MM-DD HH:MM:SS'
            const formattedTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            
            // Menampilkan waktu di elemen dengan id "current_time"
            document.getElementById('current_time').textContent = formattedTime;
        }

        // Memperbarui waktu setiap detik
        setInterval(updateTime, 1000);

        // Menampilkan waktu pertama kali saat halaman dimuat
        window.onload = updateTime;
  </script>

</body>
</html>
