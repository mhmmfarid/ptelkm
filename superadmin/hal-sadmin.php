<?php
session_start();

// Pastikan sesi valid
if (!isset($_SESSION['username']) || !isset($_SESSION['status'])) {
    header("Location: ../login/login.php"); // Redirect jika sesi tidak valid
    exit();
}

// Ambil data dari sesi
$username = $_SESSION['username'];
$status = strtoupper($_SESSION['status']);

// Sertakan file koneksi
require '../konek/koneksi.php';

// Query untuk mengambil data dari tabel login
$sql = "SELECT nama, email, no_hp, gender, tanggal_lahir 
        FROM login 
        WHERE username = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Ambil data
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    // Data default jika tidak ditemukan
    $data = [
        'nama' => 'Tidak ditemukan',
        'email' => '',
        'no_hp' => '',
        'gender' => '',
        'tanggal_lahir' => ''
    ];
}
$stmt->close();
// Ambil gambar profil dari database
include '../konek/koneksi.php';
$query = "SELECT gambar FROM login WHERE username = '$username'";
$result = mysqli_query($koneksi, $query);
if ($result) {
    $user = mysqli_fetch_assoc($result);
    $gambar = $user['gambar']; // Menyimpan nama gambar
    $gambar_path = $gambar ? 'uploads/' . $gambar : 'uploads/default-avatar.png'; // Jika tidak ada gambar, gunakan gambar default
} else {
    $gambar_path = 'uploads/default-avatar.png'; // Default jika query gagal
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Telkom Indonesia</title>

<link href="https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Dots:wght@400..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


  
  <link rel="stylesheet" href="style.css">
</head>
<style>
      @import url('https://fonts.googleapis.com/css2?family=Edu+AU+VIC+WA+NT+Dots:wght@400..700&family=Jacquarda+Bastarda+9&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap  ');
      @import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&family=Permanent+Marker&display=swap');

    *,html{
      font-family: "Poppins", sans-serif;
      font-weight: 400;
      font-style: normal;   
    } 
    #home{
      width: 100%;
      height:100vh;
      display: flex;
      background-image: url('../aset/bg.jpg');
      justify-content: center;
      align-items: center;
      margin-top: -1%;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;

    } 
    #list-p{
      width: 100%;
      height: 150px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 55px;
    }
    #list-p>.list-a{
      width: 88rem;
      height:100px;
      background-color: #fd2d2d;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50px;
      color: white;
    }
    #list-p>.list-a>h1{
      font-size: 50px;
      font-family: "Poppins", sans-serif;
      font-weight: 700;
      font-style: bold; 
    }
    #alat{
      width: 100%;
      height:100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      margin-top: 10px;
      background-color: #dc3545;
    }

    .odp{
      height: 400px;
      width: 400px;
      background-color: white;
      box-shadow: 10px 8px 8px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .odc {
      background-color: white;
      box-shadow: 10px 8px 8px rgba(0, 0, 0, 0.1);
      height: 400px;
      width: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
    }
    
    .sto{
      background-color: white;
      box-shadow: 10px 8px 8px rgba(0, 0, 0, 0.1);
      height: 400px;
      width: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 10px;
    }

    .wraaper1{
      gap: 10rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .btn-1{
      background-color: black;
    }
    nav{
      background-color: transparent;
      color: black;
    }
    .container-fluid{
      padding-bottom: 5px;
      position: fixed;
      margin-top: 3rem;
      background-color: #fffafa;
      width: 100%;
      box-shadow: 12px 10px 10px rgba(0, 0, 0, 0.1);
      z-index: 999;
      border-radius: 0 0 20px 20px;
  }
.a, .h, .f {
    color: black;
    font-size: 18px;
    margin: 0rem 10px;
    margin-top: 10px;
    text-decoration: none;
    position: relative;
}

.a::after, .h::after, .f::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    display: block;
    margin-top: 5px;
    right: 0;
    background: red; /* Warna underline */
    transition: width 0.3s ease; /* Transisi */
    -webkit-transition: width 0.3s ease;
    color: red; 
}
.a:hover::after, .h:hover::after, .f:hover::after {
    width: 100%; /* Underline muncul saat di-hover */
    left: 0;
    background: red;
}

.logo>img{
    margin-left: 50px;
    height: 60px;
    width: 200px;
}
h2{
    text-align: center;
}
.footer {
    background-color: #f8f9fa;
    padding: 40px 0;
    color: white;
  }
  .footer a {
    color: #6c757d;
    text-decoration: none;
  }
  .footer a:hover {
    color: #0056b3;
  }
  .footer .social-icons a {
    font-size: 1.2rem;
    margin-right: 15px;
  }
  .textwrap{
    font-family: roboto;
    color: black;
  }
.textwrap>h1{
    font-size: 65px;
    margin-bottom: -4px;
    font-family: "Exo 2", sans-serif;
    font-optical-sizing: auto;
    font-weight: 700;
    font-style: normal;

}
.textwrap>p{
    font-size: 20px;
    margin-left: 5px;
    font-family: "Exo 2", sans-serif;
    font-optical-sizing: auto;
    font-weight: 400;
    font-style: normal;
}
li{
    list-style-type: none;
}
.container2{
  background: url('sto.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  width: 100%;
  height: 80vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
.img-fluid{
  width: 100px;
  height: 100px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  margin-left: 20px;
}

#alat {
  margin: 10vh 0; /* Memberi jarak atas dan bawah */
}

.card {
  border: none;
  transition: all 500ms cubic-bezier(0.19, 1, 0.22, 1);
  overflow: hidden;
  border-radius: 20px;
  min-height: 450px;
  box-shadow: 0 0 12px 0 rgba(0, 0, 0, 0.2);
  margin-top: 30px
}

@media (max-width: 768px) {
  .card {
    min-height: 350px;
  }
}

@media (max-width: 420px) {
  .card {
    min-height: 300px;
  }
}

.card-has-bg {
  background-size: 120%;
  background-repeat: no-repeat;
  background-position: center center;
}

.card-has-bg:before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: inherit;
  filter: grayscale(100%);
}

.card:hover {
  transform: scale(0.98);
  box-shadow: 0 0 5px -2px rgba(0, 0, 0, 0.3);
}

.card-footer {
  background: none;
  border-top: none;
}


.card-title {
  font-weight: 800;
  color: white;
}

.card-meta {
  color: white;
  text-transform: uppercase;
  font-weight: 500;
  letter-spacing: 2px;
}

.card-body {
  transition: all 500ms cubic-bezier(0.19, 1, 0.22, 1);
  color:white;
  
}

.card:hover .card-body {
  margin-top: 30px;
  
}

.card-img-overlay {
  transition: all 800ms cubic-bezier(0.19, 1, 0.22, 1);
  background: url(../aset/sto.jpg);
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-blend-mode: multiply;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 20px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  align-items: center;
  justify-content: center;
}

.card:hover .card-img-overlay {
  background: url(../aset/sto.jpg);
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-blend-mode: multiply;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 20px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  align-items: center;
  justify-content: center;
  transition: all 400ms cubic-bezier(0.19, 1, 0.22, 1);
}

#alat .container{
  width: 1000vh;
  height: 530px;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  margin-top: 10px;
  border-radius: 20px;
}
footer img{
  width: 280px;
  height: 80px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: -20px;
  }

footer .q{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: -30px;
}
.rounded-circle{
      border-radius: 50% !important;
      width: 40px;
      height: 40px;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      justify-content: center;
      align-items: center;
    }
.dropdown .nav-link {
  margin-top: -4px;
}
.nav-link {
    text-decoration: none;
    color: black;
    margin-right: 20px;
}

.nav-link.active::after {
    width: 100%; /* Underline untuk link yang aktif */
    left: 0;
}
small, h4{
  display: flex;
  justify-content: center;
  align-items: center;
}
#list{
      width: 100%;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    #list>.list-T{
      width: 100%;
      height: 40%;
      background-color: #fd2d2d;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      gap: 12rem;
    }
    #list .kotak {
    width: 300px;
    height: 300px;
    display: inline-block;
    margin: 10px;
    text-align: center;
    color: white;
    font-size: 24px;
    line-height: 150px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Bayangan untuk kotak */  
  }
  #list .kotak h2 {
    margin: 0;
    padding-top: 20px; /* Jarak atas untuk judul */
}

#list .kotak p {
    margin: 0;
    font-size: 18px;
}
/* Center the entire social-links section */
.social-links {
            justify-content: center;
            align-items: center;
            height: 20vh;
            width: 220px; 
            text-align: center;
        }

        /* Remove default list styling and center items */
        .social-links ul {
            list-style-type: none;
            padding: 0;
        }

        /* Style each link */
        .social-links a {
            text-decoration: none;
            color: #333; /* Dark grey color */
            font-size: 1rem; /* Increase text size */
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
            color: white;
            width: 100%;
            margin-bottom: 5px;
        }

        /* Hover effect */
        .social-links a:hover {
            color: #d22; /* Dark red color on hover */
        }

        /* Increase icon size */
        .social-links i {
            margin-right: 8px;
            font-size: 2rem; /* Larger icon size */
        }
        .custom-list {
    display: flex; /* Use flexbox to arrange items in a row */
    padding: 0; /* Remove default padding */
    list-style-type: none; /* Remove bullet points */
    margin-left:-10px;
}

.custom-list li {
    margin: 0 5px; /* Add horizontal margin between items */
    color: white; /* Set text color to white */
    font-weight: bold; /* Make text bold */
    padding: 10px; /* Add padding for better spacing */
    transition: background-color 0.3s, color 0.3s; /* Smooth transition for hover effect */
    width: 100px; /* Fixed width */
    border-radius: 10px; /* Rounded corners */
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 180px;
}

.custom-list li:hover {
    background-color: rgba(255, 255, 255, 0.1); /* Light background on hover */
    color: red; /* Light background on hover */
    cursor: pointer; /* Change cursor to pointer on hover */
}
.al h5,p{
  transition: color 0.3s; /* Smooth transition for hover effect */
}
.al:hover{
  color: red;
}
.navbar .nav-link {
  color: #333; /* Warna default */
  padding: 8px 15px; /* Spasi di sekitar link */
  transition: color 0.3s ease; /* Animasi saat beralih */
}

/* Gaya khusus untuk link yang memiliki kelas 'active' */
.navbar .nav-link.active {
  color: red; /* Warna teks ketika aktif */
  font-weight: bold; /* Menebalkan teks saat aktif */
  border-radius: 4px; /* Rounding sedikit pada background */
}
.appointment-card {
      width: 300px;
      padding: 20px;
      border-radius: 15px;
      background-color: #f8f9fa;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: left;
    }
    .appointment-count {
      font-size: 2rem;
      font-weight: bold;
      color: red;
    }
    .appointment-label {
      font-size: 1.1rem;
      font-weight: 500;
      color: #333;
    }
    .appointment-icon {
      background-color: #dcdfe2;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 70px;
      height: 70px;
    }
    .appointment-info {
      font-size: 0.8rem;
      color: #6c757d;
    }
    .appointment-icon img {
            width: 60px; /* Adjust the size as needed */
            height: 60px; /* Adjust the size as needed */
            border-radius: 50%; /* Makes the image circular */
            object-fit: cover; /* Ensures the image covers the entire circle */
        }
         /* Dropdown Menu Styles */
.dropdown-menu {
    width: 330px;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border: none;
    background-color: #dddee0;
    color: #333;
    font-size: 14px;
    text-align: left;
    z-index: 9999;
}
/* Container untuk header profil */
.profile-header-container {
    display: flex;
    align-items: center; /* Vertikal rata tengah */
    gap: 15px; /* Jarak antara gambar dan informasi */
    padding: 10px 0;
    margin-bottom:-10px;
}

/* Gambar profil */
.profile-image {
    width: 100px; /* Ukuran gambar */
    height: 100px;
    object-fit: cover;
    border-radius: 50%; /* Membuat gambar menjadi lingkaran */
    border: 2px solid #ddd; /* Tambahkan border jika diinginkan */
}

/* Informasi profil */
.profile-info {
    flex: 1; /* Mengisi sisa ruang */
}

.profile-info h6 {
    font-size: 1rem;
    margin-bottom: 5px;
}

.profile-info .admin-badge {
    display: inline-block;
    background-color: #007bff; /* Warna lencana */
    color: #fff;
    padding: 3px 8px;
    border-radius: 5px;
    font-size: 0.8rem;
    margin-top: 5px;
}
/* Divider */
.divider {
    height: 1px;
    background-color: black;
    margin: 10px 0;
}

/* Dropdown Menu Items */
.dropdown-ites {
    color: black;
    font-size: 14px;
    padding: 10px 5px;
    text-decoration: none;
    text-align: left;
  }
  
  .achievement-item{
  transition: background-color 0.3s ease;
  flex-direction: column;
  margin: 3px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
  color: #333;
}
.achievement-item:hover{
  background-color: #e0f2f1;
}
</style>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand logo" href="#"><img src="../aset/logo.png" alt=""></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link h active" aria-current="page" href="#home" id="link-home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link f" aria-current="page" href="#alat" id="link-alat">Tools</a>
          </li>
          <li class="nav-item">
            <a class="nav-link a" aria-current="page" href="#list" id="link-list">Device</a>
          </li>
        </ul>

        <div class="d-flex align-items-center">
        <div class="dropdown mt-2 d-flex align-items-center">
        <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button" id="profileDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
    <!-- Gambar Profil pada Dropdown -->
    <img src="../aset/bg2.jpg" alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
</button>

            <!-- Divider -->
        <div class="divider"></div>
            <!-- Dropdown Menu -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdownButton" id="dropdownMenu">
                <!-- Profile Header -->
                <div class="profile-header-container">
    <!-- Gambar profil -->
    <div class="profile-image-container">
    <img src="../aset/bg2.jpg" alt="Profile Picture" class="profile-image">

    </div>

    <!-- Informasi profil -->
    <div class="profile-info">
      <h6 class="mt-2 mb-0"><?php echo htmlspecialchars($username); ?></h6>
      <span class="admin-badge"><?php echo htmlspecialchars($status); ?></span>
      <p class="text-muted mt-1 mb-0" style="font-size: 0.9em;"><strong>Nama:</strong> <?php echo htmlspecialchars($data['nama']); ?></p>
      <p class="text-muted mt-1 mb-0" style="font-size: 0.9em;"><strong>Email:</strong> <?php echo htmlspecialchars($data['email']); ?></p>
      <p class="text-muted mt-1 mb-0" style="font-size: 0.9em;"><strong>No HP:</strong> <?php echo htmlspecialchars($data['no_hp']); ?></p>
      <p class="text-muted mt-1 mb-0" style="font-size: 0.9em;"><strong>Gender:</strong> <?php echo htmlspecialchars($data['gender']); ?></p>
      <p class="text-muted mt-1 mb-0" style="font-size: 0.9em;"><strong>Tanggal Lahir:</strong> <?php echo htmlspecialchars($data['tanggal_lahir']); ?></p>
    </div>
</div>

                <!-- Divider -->
        <div class="divider"></div>
        <!-- Achievements Section -->
        <div class="achievement-item">
          <li><a class="dropdown-ites" href="../superadmin/data-akun.php"><i class="fa-solid fa-user"></i> Data Account</a></li>
        </div>  
        <!-- Achievements Section -->
        <div class="achievement-item">
          <li><a class="dropdown-ites" href="../edit/edit-profile.php"><i class="fa-solid fa-address-card"></i> Profile</a></li>
        </div>  
        <!-- Divider -->
<div class="divider"></div>
                      <!-- Achievements Section -->
        <div class="achievement-item">
          <li><a class="dropdown-ites" href="../login/login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</a></li>
        </div>  

            </ul>
        </div>
    </div>

    <script>
    const dropdownMenu = document.getElementById('dropdownMenu');
    dropdownMenu.addEventListener('mouseleave', function () {
        const dropdown = bootstrap.Dropdown.getInstance(document.getElementById('profileDropdownButton'));
        dropdown.hide();
    });
</script>
</div>
      </div>
      </div>
    </div>
</nav>


<!-- Script untuk menambahkan kelas 'active' pada link yang diklik -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
  // Pilih semua link navbar yang mengarah ke section tertentu
  const sections = document.querySelectorAll('section');
  const navLinks = document.querySelectorAll('.nav-link');

  // Fungsi untuk menghapus kelas 'active' dari semua link
  function removeActiveClasses() {
    navLinks.forEach(link => link.classList.remove('active'));
  }

  // Fungsi untuk menambahkan kelas 'active' ke link yang sesuai
  function setActiveLink(link) {
    removeActiveClasses();
    link.classList.add('active');
  }

  // Fungsi untuk mendeteksi scroll dan menyesuaikan link aktif
  function updateActiveLinkOnScroll() {
    let currentSection = '';
    
    sections.forEach(section => {
      const sectionTop = section.offsetTop - 50; // Mengkompensasi tinggi navbar
      if (window.scrollY >= sectionTop) {
        currentSection = section.getAttribute('id');
      }
    });

    // Temukan link navbar yang cocok dengan ID section yang sedang aktif
    navLinks.forEach(link => {
      if (link.getAttribute('href').substring(1) === currentSection) {
        setActiveLink(link);
      }
    });
  }

  // Event listener saat di-scroll
  window.addEventListener('scroll', updateActiveLinkOnScroll);
  
  // Panggil fungsi ini saat halaman dimuat untuk memberi active pada bagian default (home)
  updateActiveLinkOnScroll();
});

</script>

  
  <!-- Content -->
   <section id="home">
    <div class="container" >
        <class="container">
            <div class="textwrap">
            <h1>Telkom indonesia</h1>
           <p>Welcome Website Telkom indonesia</p>
          </div>
         </class=>
    </div>
   </section>
   
   <section id="list-p">
    <div class="list-a bg-danger">
      <h1>Welcome To Telkom Indonesia</h1>
    </div>
   </section>

   <section id="alat">
  <div class="container">
  <div class="row d-flex justify-content-center align-items-center">
      <div class="col-sm-12 col-md-6 col-lg-4 mb-4 mt-4">
        <div class="card text-dark card-has-bg click-col" style="background-image:url('sto2.jpg');">
          <a href="maps-sto.php" class="card-img-overlay d-flex flex-column">
            <div class="card-body">
              <small class="card-meta mb-2">Alat Produksi STO</small>
              <h4 class="card-title mt-0">Sentral Telepon Otomat</h4>
              <small>Sentral Telepon Otomat (STO) merupakan kantor Telkom yang lebih berhubungan dengan sisi teknikal. Biasanya STO ditempati oleh anak perusahaan Telkom yaitu PT. Telkom Akses beserta beberapa mitra Telkom.</small>
            </div>
            <div class="card-footer">
              <div class="media">
                <div class="media-body">
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>

      <div class="col-sm-12 col-md-6 col-lg-4 mb-4 mt-4">
        <div class="card text-dark card-has-bg click-col" style="background-image:url('odc.jpg');">
          <a href="maps-odc.php" class="card-img-overlay d-flex flex-column">
            <div class="card-body">
              <small class="card-meta mb-2">Alat Produksi ODC</small>
              <h4 class="card-title mt-0">Optical Distribution Cabinet</h4>
              <small>Optical Distribution Cabinet (ODC) Telkom adalah tempat terminasi kabel feeder dan kabel distribusi dalam jaringan internet. ODC merupakan bagian dari struktur jaringan fiber to the home (FTTH) dan berfungsi sebagai penghubung antara kabel feeder dan kabel distribusi.</small>
            </div>
            <div class="card-footer">
              <div class="media">
                <div class="media-body">
                  <h6 class="my-0 text-dark d-block"></h6>
                </div>
              </div>
            </div>
          </a>
        </div>
      </div>
      
      <div class="col-sm-12 col-md-6 col-lg-4 mb-4 mt-4">
        <div class="card text-dark card-has-bg click-col" style="background-image:url('odp.jpg');">
          <a href="../map/maps-odp.php" class="card-img-overlay d-flex flex-column">
            <div class="card-body">
              <small class="card-meta mb-2">Alat Produksi ODP</small>
              <h4 class="card-title mt-0">Optical Distribution Point</h4>
              <small>ODP Telkom adalah singkatan dari Optical Distribution Point, yaitu perangkat yang berfungsi sebagai tempat terminasi kabel dan membagi satu inti optik ke beberapa pelanggan. ODP merupakan fitur pendukung layanan serat optik yang tahan korosi dan cuaca.</small>
            </div>
            <div class="card-footer">
              <div class="media">
                <div class="media-body">
                  <h6 class="my-0 text-dark d-block"></h6>
                </div>
              </div>
            </div>
          </a>
        </div>

      <!-- Add more card columns as needed -->
      
    </div>
  </div>
</section>

<?php
// Koneksi ke database 'telkom'
try {
    $pdo = new PDO("mysql:host=localhost;dbname=telkom", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Mengambil jumlah data berdasarkan kolom id_sto, id_odc, dan id_odp
    $jumlah_sto = $pdo->query("SELECT COUNT(id_sto) FROM sto")->fetchColumn();
    $jumlah_odc = $pdo->query("SELECT COUNT(id_odc) FROM odc")->fetchColumn();
    $jumlah_odp = $pdo->query("SELECT COUNT(id_odp) FROM odp")->fetchColumn();
} catch (PDOException $e) {
    // Jika gagal, tampilkan pesan "Gagal"
    echo "Gagal: " . $e->getMessage();
    exit; // Menghentikan eksekusi lebih lanjut
}
?>

<section id="list">
    <div class="list-T bg-danger">
    <div class="appointment-card d-flex justify-content-between align-items-center">
    <div>
      <div class="appointment-count"><?php echo $jumlah_sto; ?></div>
      <div class="appointment-label">STO</div>
      <div class="appointment-info">Sentral Telepon Otomat</div>
      <div class="appointment-info">Today, 11/06/2024</div>
    </div>
    <div class="appointment-icon">
      <img src="../aset/sto.jpg" alt="odp Icon">
    </div>
  </div>
  <div class="appointment-card d-flex justify-content-between align-items-center">
    <div>
      <div class="appointment-count"><?php echo $jumlah_odc; ?></div>
      <div class="appointment-label">ODC</div>
      <div class="appointment-info">Optical Distribution Cabinet</div>
      <div class="appointment-info">Today, 11/06/2024</div>
    </div>
    <div class="appointment-icon">
      <img src="../aset/odc.jpg" alt="odp Icon">
    </div>
  </div>
        <div class="appointment-card d-flex justify-content-between align-items-center">
    <div>
      <div class="appointment-count"><?php echo $jumlah_odp; ?></div>
      <div class="appointment-label">ODP</div>
      <div class="appointment-info">Optical Distribution Point</div>
      <div class="appointment-info">Today, 11/06/2024</div>
    </div>
    <div class="appointment-icon">
      <img src="../aset/odp2.png" alt="odp Icon">
    </div>
  </div>
</section>


    <!-- Footer -->
    <section id="footer">
    <footer class="footer bg-dark">
    <div class="container">
      <div class="row">
        <div class="q col-lg-3 col-md-6 mb-4">
          <img src="../aset/logow.png" alt="">
        </div>
        <div class="col-lg-3 col-md-6 mb-4 social-links mt-4">
        <ul>
            <li><a href="https://www.facebook.com/telkomindonesia" target="_blank"><i class="bi bi-facebook"></i>Telkom Indonesia</a></li>
            <li><a href="https://www.instagram.com/telkomindonesia" target="_blank"><i class="bi bi-instagram"></i>Telkom Indonesia</a></li>
            <li><a href="https://twitter.com/telkomindonesia" target="_blank"><i class="bi bi-twitter"></i>Telkom Indonesia</a></li>
        </ul>
        </div>
        <div class="col-lg-3 col-md-6  d-flex text-align-center justify-content-center mt-2">
    <ul class="list-unstyled custom-list">
        <a href="#"><li>Home</li></a>
        <a href="../admin/hal-profile.php"><li>Profile</li></a>
        <a href="../user/maps-odp.php"><li>Maps</li></a>
    </ul>
</div>
        <div class="col-lg-3 col-md-6 mb-2 mt-2">
          <ul class="list-unstyled al">
          <h5>Alamat</h5>
          <p>Kawasan The Telkom Hub, Gedung Telkom Landmark Tower II, lantai.39, Jl. Jenderal Gatot Subroto Kav. 52, Kuningan Barat, Mampang Prapatan, Jakarta Selatan, Jakarta, Indonesia 12710</p>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <p>© 2024 PT Telkom Indonesia. All Rights Reserved.</p>
        </div>
        <div class="col-md-6 text-md-end social-icons">
          
        </div>
      </div>
    </div>
  </footer>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>