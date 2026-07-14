<?php
session_start();

// Pastikan sesi valid
if (!isset($_SESSION['username']) || !isset($_SESSION['status'])) {
    header("Location: ../login/login.php"); // Redirect jika sesi tidak valid
    exit();
}

// Ambil data dari sesi
$username = $_SESSION['username'];
$status = $_SESSION['status'];

// Sertakan file koneksi
require '../konek/koneksi.php';

// Query untuk mengambil data dari tabel login berdasarkan username
$sql = "SELECT id, nama, username, email, no_hp, gender, tanggal_lahir, gambar, status 
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
        'id' => 'N/A',
        'nama' => 'Tidak ditemukan',
        'username' => $username,
        'email' => '',
        'no_hp' => '',
        'gender' => '',
        'tanggal_lahir' => '',
        'gambar' => '',
        'status' => $status
    ];
}
$stmt->close();

// Tentukan tautan kembali berdasarkan status
$homeLink = '';
if ($status === 'admin') {
    $homeLink = '../admin/hal-admin.php';
} elseif ($status === 's.admin') {
    $homeLink = '../superadmin/hal-sadmin.php';
} else {
    $homeLink = '../user/hal-user.php';
}

// Tentukan path gambar profil
$gambar_path = (!empty($data['gambar']) && file_exists('../uploads/' . $data['gambar'])) ? '../uploads/' . $data['gambar'] : '../aset/pp.jpg';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna - Telkom Indonesia</title>
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- CSS Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --primary-color: #C21807;
      --primary-gradient: linear-gradient(135deg, #c21807 0%, #ff3b30 100%);
      --dark-glass: rgba(15, 15, 25, 0.75);
      --light-glass: rgba(255, 255, 255, 0.08);
      --accent-color: #FF2E93;
    }

    * {
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.85)), url('../aset/bg3.jpeg') no-repeat center center fixed;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 15px;
      color: #ffffff;
      overflow-x: hidden;
    }

    /* Modern Glassmorphic Container */
    .profile-card {
      background: var(--dark-glass);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 24px;
      width: 100%;
      max-width: 950px;
      overflow: hidden;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
      animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
    }

    /* Header Banner with blur effect */
    .card-banner {
      height: 160px;
      background: linear-gradient(45deg, rgba(194, 24, 7, 0.4), rgba(255, 46, 147, 0.3)), url('../aset/bg4.jpg') center center;
      background-size: cover;
      position: relative;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-banner::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(to bottom, transparent, rgba(15, 15, 25, 0.9));
    }

    /* Back Button on top of banner */
    .btn-back-floating {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 10;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: #fff;
      padding: 8px 16px;
      border-radius: 50px;
      font-size: 0.9rem;
      font-weight: 500;
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-back-floating:hover {
      background: #ffffff;
      color: #000000;
      transform: translateX(-5px);
      box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }

    /* Profile Avatar section */
    .profile-avatar-wrapper {
      position: relative;
      margin-top: -80px;
      text-align: center;
      z-index: 2;
    }

    .profile-avatar {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 5px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.6);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .profile-avatar:hover {
      transform: scale(1.05) rotate(3deg);
      border-color: var(--primary-color);
      box-shadow: 0 0 25px rgba(194, 24, 7, 0.6);
    }

    .role-badge {
      display: inline-block;
      margin-top: 10px;
      padding: 5px 15px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      border-radius: 50px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .badge-sadmin {
      background: linear-gradient(135deg, #6f42c1 0%, #a020f0 100%);
      border: 1px solid rgba(111, 66, 193, 0.5);
    }

    .badge-admin {
      background: var(--primary-gradient);
      border: 1px solid rgba(194, 24, 7, 0.5);
    }

    .badge-user {
      background: linear-gradient(135deg, #198754 0%, #20c997 100%);
      border: 1px solid rgba(25, 135, 84, 0.5);
    }

    .profile-name {
      font-size: 1.8rem;
      font-weight: 700;
      margin-top: 12px;
      margin-bottom: 2px;
      letter-spacing: 0.5px;
      text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    .profile-username {
      color: rgba(255, 255, 255, 0.6);
      font-size: 0.95rem;
      font-weight: 300;
      margin-bottom: 25px;
    }

    /* Details Grid styling */
    .details-container {
      padding: 0 40px 40px;
    }

    .info-card {
      background: var(--light-glass);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      padding: 20px;
      height: 100%;
      transition: all 0.3s ease;
    }

    .info-card:hover {
      background: rgba(255, 255, 255, 0.12);
      transform: translateY(-3px);
      border-color: rgba(255, 255, 255, 0.15);
    }

    .info-label {
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255, 255, 255, 0.45);
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .info-label i {
      color: var(--primary-color);
      font-size: 0.9rem;
    }

    .info-value {
      font-size: 1.05rem;
      font-weight: 500;
      color: #ffffff;
      word-wrap: break-word;
    }

    /* Action Buttons Area */
    .action-row {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
    }

    .btn-custom {
      padding: 12px 30px;
      font-size: 0.95rem;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .btn-edit {
      background: var(--primary-gradient);
      color: white;
      border: none;
      box-shadow: 0 4px 15px rgba(194, 24, 7, 0.4);
    }

    .btn-edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(194, 24, 7, 0.6);
      background: linear-gradient(135deg, #ff3b30 0%, #ff453a 100%);
      color: white;
    }

    .btn-home {
      background: rgba(255, 255, 255, 0.1);
      color: white;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-home:hover {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      transform: translateY(-2px);
    }

    /* Dynamic Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .details-container {
        padding: 0 20px 30px;
      }
      .profile-card {
        margin: 15px 0;
      }
      .info-card {
        padding: 15px;
      }
    }
  </style>
</head>
<body>

  <div class="profile-card">
    <!-- Header Banner -->
    <div class="card-banner">
      <a href="<?php echo htmlspecialchars($homeLink); ?>" class="btn-back-floating">
        <i class="bi bi-arrow-left"></i> Kembali
      </a>
    </div>

    <!-- Avatar & Basic Info -->
    <div class="profile-avatar-wrapper">
      <img src="<?php echo htmlspecialchars($gambar_path); ?>" alt="Foto Profil" class="profile-avatar">
      <div>
        <?php if ($status === 's.admin'): ?>
          <span class="role-badge badge-sadmin"><i class="bi bi-shield-lock-fill me-1"></i> Super Admin</span>
        <?php elseif ($status === 'admin'): ?>
          <span class="role-badge badge-admin"><i class="bi bi-shield-fill me-1"></i> Admin</span>
        <?php else: ?>
          <span class="role-badge badge-user"><i class="bi bi-person-fill me-1"></i> Pengguna</span>
        <?php endif; ?>
      </div>
      <h2 class="profile-name"><?php echo htmlspecialchars($data['nama']); ?></h2>
      <p class="profile-username">@<?php echo htmlspecialchars($data['username']); ?></p>
    </div>

    <!-- Grid Details -->
    <div class="details-container">
      <div class="row g-4">
        <!-- User ID -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-hash"></i> ID Pengguna</div>
            <div class="info-value"><?php echo htmlspecialchars($data['id']); ?></div>
          </div>
        </div>

        <!-- Email -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-envelope-fill"></i> Email</div>
            <div class="info-value"><?php echo !empty($data['email']) ? htmlspecialchars($data['email']) : '-'; ?></div>
          </div>
        </div>

        <!-- No HP -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-telephone-fill"></i> No. Telepon</div>
            <div class="info-value"><?php echo !empty($data['no_hp']) ? htmlspecialchars($data['no_hp']) : '-'; ?></div>
          </div>
        </div>

        <!-- Gender -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-gender-ambiguous"></i> Jenis Kelamin</div>
            <div class="info-value">
              <?php 
                if (strtolower($data['gender']) == 'l') {
                  echo '<i class="bi bi-gender-male text-primary"></i> Laki-laki';
                } elseif (strtolower($data['gender']) == 'p') {
                  echo '<i class="bi bi-gender-female text-danger"></i> Perempuan';
                } else {
                  echo htmlspecialchars($data['gender'] ?: '-');
                }
              ?>
            </div>
          </div>
        </div>

        <!-- Tanggal Lahir -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-calendar-event-fill"></i> Tanggal Lahir</div>
            <div class="info-value">
              <?php 
                if (!empty($data['tanggal_lahir'])) {
                  $date = date_create($data['tanggal_lahir']);
                  echo date_format($date, "d F Y");
                } else {
                  echo '-';
                }
              ?>
            </div>
          </div>
        </div>

        <!-- Status Integrasi -->
        <div class="col-md-6 col-lg-4">
          <div class="info-card">
            <div class="info-label"><i class="bi bi-check-circle-fill text-success"></i> Status Akun</div>
            <div class="info-value">Terverifikasi</div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="action-row">
        <a href="../edit/edit-profile.php" class="btn btn-custom btn-edit">
          <i class="bi bi-pencil-square"></i> Edit Profil
        </a>
        <a href="<?php echo htmlspecialchars($homeLink); ?>" class="btn btn-custom btn-home">
          <i class="bi bi-house-door-fill"></i> Beranda
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
