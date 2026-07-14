<?php
include '../konek/koneksi.php';
session_start(); // Memulai session untuk mengambil username dan status

// Pastikan pengguna telah login
if (!isset($_SESSION['username'])) {
    header('Location: ../login/login.php');
    exit();
}

$username = $_SESSION['username']; // Mengambil username dari session

// Query untuk mengambil data user berdasarkan username
$query = "SELECT * FROM login WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    $status = isset($user['status']) ? $user['status'] : 'unknown';
    $nama = isset($user['nama']) ? $user['nama'] : '';
    $email = isset($user['email']) ? $user['email'] : '';
    $no_hp = isset($user['no_hp']) ? $user['no_hp'] : '';
    $gender = isset($user['gender']) ? $user['gender'] : '';
    $gam = isset($user['gambar']) ? $user['gambar'] : '';
    $tanggal_lahir = isset($user['tanggal_lahir']) ? $user['tanggal_lahir'] : '';
} else {
    echo "<script>alert('Gagal mengambil data!');</script>";
    exit();
}




// Tentukan link berdasarkan status
$homeLink = '';
if ($status === 'admin') {
    $homeLink = '../admin/hal-admin.php';
} elseif ($status === 'user') {
    $homeLink = '../user/hal-user.php';
} elseif ($status === 's.admin') {
    $homeLink = '../superadmin/hal-sadmin.php';
} else {
    $homeLink = '#'; // Default jika status tidak valid
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

.account-settings {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

.profile-header {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    margin-top: 15px;
}

.profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%; /* Membuat gambar menjadi bulat */
    object-fit: cover; /* Memastikan gambar memenuhi lingkaran tanpa merusak proporsinya */
    margin-right: 10px;
    border: 2px solid #ccc;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}


.upload-btn, .delete-btn {
    padding: 8px 12px;
    cursor: pointer;
    margin-right: 5px;
    border-radius: 5px;
}

.upload-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
}

.delete-btn {
    background-color: #e0e0e0;
    color: #333;
    border: none;
}

form {
    width: 100%;
}

.form-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
}

.form-group {
    width: 48%;
}

.form-group.full-width {
    width: 100%;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.input-with-flag {
    display: flex;
    align-items: center;
}

.flag-icon {
    font-size: 18px;
    margin-right: 8px;
}

.gender-options {
    display: flex;
    gap: 20px; /* Menambahkan jarak antar opsi */
    margin-top: 5px;
    
}

.gender-option-box {
  border: 1px solid #ccc;
  padding: 8px 20px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  cursor: pointer;
}

.gender-option-box input[type="radio"] {
  margin-right: 8px;
}

.gender-option-box:hover {
  border-color: #C21807;
}

.gender-options .gender-option-box input[type="radio"]:checked + label {
  font-weight: bold;
  color: #007bff;
}


.save-btn {
    width: 100%;
    padding: 10px;
    background-color: #C21807;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 15px;
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
  #nama_lengkap {
    width: 208%;
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
      <li class="nav-item mb-2">
        <a href="../edit/edit-profile.php" class="nav-link text-white d-flex align-items-center active">
          <i class="fa-solid fa-user icon-size"></i>
          <span class="ms-3">Profile</span>
        </a>
      </li>
      <hr class="divider">  
      <li class="nav-item mb-2">
                        <a href="<?php echo $homeLink; ?>" class="nav-link text-white d-flex align-items-center">
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
      <h3 class="me-3">Profile</h3>
    </a>
    </div>

    <div class="account-settings">
      
      
    <form action="../simpan/simpan-profile.php" method="post" enctype="multipart/form-data">
    <div class="profile-header">
        <!-- Menampilkan gambar profil yang ada di database -->
        <?php if ($status !== 'user'): ?>
            <img 
                src="<?php echo (!empty($gam) && file_exists('../uploads/' . $gam)) ? '../uploads/' . htmlspecialchars($gam, ENT_QUOTES, 'UTF-8') : '../aset/pp.jpg'; ?>" 
                alt="Profile" 
                class="profile-pic" 
                id="profilePic" 
                onclick="triggerFilePicker()"
                style="cursor: pointer; border: 1px solid #ccc; width: 120px; height: 120px; object-fit: cover;">
            <input type="file" id="uploadAvatar" style="display:none;" accept="image/*" name="gambar" onchange="previewImage(event)">
            <small class="text-muted d-block text-center mt-1 w-100">Klik gambar untuk mengubah foto</small>
        <?php else: ?>
            <img 
                src="<?php echo (!empty($gam) && file_exists('../uploads/' . $gam)) ? '../uploads/' . htmlspecialchars($gam, ENT_QUOTES, 'UTF-8') : '../aset/pp.jpg'; ?>" 
                alt="Profile" 
                class="profile-pic" 
                id="profilePic" 
                style="border: 1px solid #ccc; width: 120px; height: 120px; object-fit: cover; cursor: default;">
            <small class="text-danger d-block text-center mt-1 w-100">Foto profil diatur oleh Admin</small>
        <?php endif; ?>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Username" readonly>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <input type="text" id="status" name="status" value="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Status" readonly>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama" value="<?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Nama Lengkap" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" placeholder="examples@gmail.com" required>
        </div>
        <div class="form-group">
            <label for="mobile">No. Telepon</label>
            <input type="text" id="mobile" name="no_hp" value="<?php echo htmlspecialchars($no_hp, ENT_QUOTES, 'UTF-8'); ?>" placeholder="0851453147" required>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Gender</label>
            <div class="gender-options d-flex gap-2">
                <div class="gender-option-box">
                    <input type="radio" id="male" name="gender" value="male" <?php echo ($gender == 'male') ? 'checked' : ''; ?> required>
                    <label for="male">Male</label>
                </div>
                <div class="gender-option-box">
                    <input type="radio" id="female" name="gender" value="female" <?php echo ($gender == 'female') ? 'checked' : ''; ?> required>
                    <label for="female">Female</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="tanggal_lahir">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($tanggal_lahir, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
    </div>

    <button type="submit" class="save-btn">Save Changes</button>
</form>



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

    document.addEventListener('DOMContentLoaded', () => {
    // Data pengguna (contoh dari login)
   
});

// Fungsi untuk membuka file picker
function triggerFilePicker() {
    document.getElementById('uploadAvatar').click();
}

// Fungsi untuk menampilkan gambar yang dipilih pengguna
function previewImage(event) {
    const profilePic = document.getElementById('profilePic');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            profilePic.src = e.target.result; // Set gambar ke hasil yang dipilih
        };
        reader.readAsDataURL(file);
    }
}

  </script>

</body>
</html>
