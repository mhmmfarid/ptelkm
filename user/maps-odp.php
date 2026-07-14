<?php
session_start();

// Pastikan sesi valid
if (!isset($_SESSION['username']) || !isset($_SESSION['status'])) {
    header("Location: ../login/login.php"); // Redirect jika sesi tidak valid
    exit();
}

$username = $_SESSION['username'];
$status = $_SESSION['status'];

// Tentukan tautan kembali berdasarkan status
$homeLink = '';
if ($status === 'admin') {
    $homeLink = '../admin/hal-admin.php';
} elseif ($status === 's.admin') {
    $homeLink = '../superadmin/hal-sadmin.php';
} else {
    $homeLink = '../user/hal-user.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Peta Kantor Telkom Terintegrasi - Telkom Indonesia</title>
  
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  
  <style>
    :root {
      --primary-color: #C21807;
      --accent-color: #FF3B30;
      --sidebar-bg: linear-gradient(135deg, #121217 0%, #200808 100%);
      --text-light: #f8f9fa;
      --glass-light: rgba(255, 255, 255, 0.08);
      --glass-border: rgba(255, 255, 255, 0.12);
    }

    * {
      font-family: 'Poppins', sans-serif;
    }

    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      width: 100%;
      overflow: hidden;
      background-color: #121217;
    }

    .main-wrapper {
      height: 100vh;
      display: flex;
    }

    /* Side Panel Style */
    .side-panel {
      background: var(--sidebar-bg);
      width: 380px;
      height: 100vh;
      box-shadow: 5px 0 25px rgba(0, 0, 0, 0.5);
      z-index: 1000;
      display: flex;
      flex-direction: column;
      border-right: 1px solid var(--glass-border);
      transition: all 0.3s ease;
      flex-shrink: 0;
    }

    .panel-header {
      padding: 25px 20px 15px;
      border-bottom: 1px solid var(--glass-border);
    }

    .back-btn-container {
      margin-bottom: 15px;
    }

    .btn-back {
      background: var(--glass-light);
      border: 1px solid var(--glass-border);
      color: var(--text-light);
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 0.85rem;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .btn-back:hover {
      background: var(--text-light);
      color: #000;
      transform: translateX(-3px);
    }

    .panel-title {
      color: var(--text-light);
      font-weight: 700;
      font-size: 1.25rem;
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .panel-title i {
      color: var(--accent-color);
    }

    .panel-subtitle {
      color: rgba(255, 255, 255, 0.5);
      font-size: 0.8rem;
      margin-top: 4px;
      margin-bottom: 0;
    }

    .search-container {
      padding: 15px 20px;
    }

    .search-input-group {
      position: relative;
    }

    .search-input-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.4);
    }

    .search-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--glass-border);
      border-radius: 50px;
      color: white;
      padding: 10px 15px 10px 42px;
      font-size: 0.9rem;
      width: 100%;
      transition: all 0.3s ease;
    }

    .search-control:focus {
      background: rgba(255, 255, 255, 0.1);
      border-color: var(--accent-color);
      box-shadow: 0 0 10px rgba(255, 59, 48, 0.3);
      outline: none;
    }

    /* List Container Scrollable */
    .office-list {
      flex-grow: 1;
      overflow-y: auto;
      padding: 5px 15px 20px;
    }

    .office-list::-webkit-scrollbar {
      width: 6px;
    }

    .office-list::-webkit-scrollbar-track {
      background: transparent;
    }

    .office-list::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 3px;
    }

    .office-list::-webkit-scrollbar-thumb:hover {
      background: var(--accent-color);
    }

    /* Office Card Item */
    .office-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 14px;
      padding: 16px;
      margin-bottom: 12px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .office-card:hover {
      background: rgba(255, 255, 255, 0.07);
      border-color: rgba(255, 59, 48, 0.3);
      transform: translateY(-2px);
    }

    .office-card.active-card {
      background: rgba(194, 24, 7, 0.12);
      border-color: var(--accent-color);
      box-shadow: inset 0 0 10px rgba(194, 24, 7, 0.2);
    }

    .office-card-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 8px;
    }

    .office-name {
      color: white;
      font-size: 0.95rem;
      font-weight: 600;
      margin: 0;
      line-height: 1.4;
    }

    .office-badge {
      font-size: 0.7rem;
      font-weight: 600;
      padding: 2px 8px;
      border-radius: 50px;
      white-space: nowrap;
      margin-left: 10px;
      background: rgba(255, 59, 48, 0.2);
      border: 1px solid var(--accent-color);
      color: #ff8e8a;
    }

    .office-detail {
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.6);
      margin-bottom: 6px;
      display: flex;
      align-items: flex-start;
      gap: 8px;
    }

    .office-detail i {
      color: var(--accent-color);
      margin-top: 2px;
      flex-shrink: 0;
    }

    .btn-action-wrapper {
      display: flex;
      justify-content: flex-end;
      margin-top: 12px;
    }

    .btn-telusuri {
      background: var(--primary-color);
      border: none;
      color: white;
      font-size: 0.8rem;
      font-weight: 600;
      padding: 5px 15px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .btn-telusuri:hover {
      background: var(--accent-color);
      box-shadow: 0 4px 10px rgba(255, 59, 48, 0.4);
    }

    /* Map Section */
    #map {
      flex-grow: 1;
      height: 100vh;
    }

    /* Custom Pulsing Leaflet Marker */
    .pulsing-marker-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .pulsing-marker {
      width: 16px;
      height: 16px;
      background-color: var(--accent-color);
      border-radius: 50%;
      border: 2px solid #ffffff;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
      position: relative;
    }

    .pulsing-marker::after {
      content: '';
      position: absolute;
      top: -8px;
      left: -8px;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      border: 2px solid var(--accent-color);
      animation: ripple 1.6s infinite ease-out;
      opacity: 0;
    }

    @keyframes ripple {
      0% {
        transform: scale(0.4);
        opacity: 0.8;
      }
      100% {
        transform: scale(1.3);
        opacity: 0;
      }
    }

    /* Custom Leaflet Popup Style */
    .leaflet-popup-content-wrapper {
      background: #161622 !important;
      color: white !important;
      border: 1px solid var(--glass-border);
      border-radius: 14px !important;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
      padding: 6px;
    }

    .leaflet-popup-tip {
      background: #161622 !important;
      border: 1px solid var(--glass-border);
    }

    .popup-content-title {
      font-weight: 700;
      color: white;
      font-size: 1rem;
      margin-bottom: 4px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      padding-bottom: 4px;
    }

    .popup-content-address {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.7);
      margin-bottom: 8px;
      line-height: 1.4;
    }

    .popup-link-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #007bff;
      color: white;
      text-decoration: none;
      font-size: 0.75rem;
      padding: 4px 10px;
      border-radius: 40px;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .popup-link-btn:hover {
      background: #0056b3;
      color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .main-wrapper {
        flex-direction: column-reverse;
      }
      .side-panel {
        width: 100%;
        height: 45vh;
      }
      #map {
        height: 55vh;
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="main-wrapper">
    <!-- Side Panel -->
    <div class="side-panel">
      <div class="panel-header">
        <div class="back-btn-container">
          <a href="<?php echo htmlspecialchars($homeLink); ?>" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
          </a>
        </div>
        <h4 class="panel-title">
          <i class="bi bi-geo-alt-fill"></i> Peta Integrasi Kantor
        </h4>
        <p class="panel-subtitle">Daftar Kantor Wilayah PT Telkom Indonesia yang terintegrasi</p>
      </div>

      <!-- Search Box -->
      <div class="search-container">
        <div class="search-input-group">
          <i class="bi bi-search"></i>
          <input type="text" id="searchInput" class="search-control" placeholder="Cari nama kantor atau wilayah...">
        </div>
      </div>

      <!-- Office List -->
      <div class="office-list" id="officeListContainer">
        <!-- Rendered dynamically by JS -->
      </div>
    </div>

    <!-- Map View -->
    <div id="map"></div>
  </div>

  <!-- Leaflet Javascript -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  
  <script>
    // List Kantor Telkom Terintegrasi di Indonesia
    const telkomOffices = [
      {
        id: "pusat",
        name: "Telkom Landmark Tower (The Telkom Hub)",
        region: "Kantor Pusat / Regional II",
        address: "The Telkom Hub, Gedung Telkom Landmark Tower II, Jl. Jenderal Gatot Subroto Kav. 52, Kuningan Barat, Mampang Prapatan, Jakarta Selatan, DKI Jakarta 12710",
        phone: "(021) 5215111",
        status: "Pusat Integrasi",
        coords: [-6.2300, 106.8184]
      },
      {
        id: "reg1",
        name: "Telkom Regional I Sumatera",
        region: "Regional I (Sumatera)",
        address: "Jl. HM. Yamin No.2, Perintis, Medan Timur, Kota Medan, Sumatera Utara 20231",
        phone: "(061) 4513111",
        status: "Terintegrasi",
        coords: [3.5907, 98.6756]
      },
      {
        id: "reg2",
        name: "Telkom Regional II Jabodetabek",
        region: "Regional II (Jabodetabek)",
        address: "Jl. Kebon Sirih No.10, Gambir, Jakarta Pusat, DKI Jakarta 10110",
        phone: "(021) 3862222",
        status: "Terintegrasi",
        coords: [-6.1822, 106.8310]
      },
      {
        id: "reg3",
        name: "Telkom Regional III Jawa Barat",
        region: "Regional III (Jawa Barat)",
        address: "Jl. Japati No.1, Sadang Serang, Coblong, Kota Bandung, Jawa Barat 40133",
        phone: "(022) 4527111",
        status: "Terintegrasi",
        coords: [-6.9025, 107.6187]
      },
      {
        id: "reg4",
        name: "Telkom Regional IV Jateng & DIY",
        region: "Regional IV (Jateng & DIY)",
        address: "Jl. Pahlawan No.10, Mugassari, Semarang Selatan, Kota Semarang, Jawa Tengah 50249",
        phone: "(024) 8411111",
        status: "Terintegrasi",
        coords: [-6.9935, 110.4208]
      },
      {
        id: "reg5",
        name: "Telkom Regional V Jatim, Bali, Nusra",
        region: "Regional V (Jatim, Bali & Nusra)",
        address: "Jl. Ketintang No.156, Ketintang, Gayungan, Kota Surabaya, Jawa Timur 60231",
        phone: "(031) 8281111",
        status: "Terintegrasi",
        coords: [-7.3129, 112.7296]
      },
      {
        id: "reg6",
        name: "Telkom Regional VI Kalimantan",
        region: "Regional VI (Kalimantan)",
        address: "Jl. Jenderal Sudirman No.1, Klandasan Ulu, Balikpapan Kota, Kota Balikpapan, Kalimantan Timur 76112",
        phone: "(0542) 731111",
        status: "Terintegrasi",
        coords: [-1.2694, 116.8312]
      },
      {
        id: "reg7",
        name: "Telkom Regional VII KTI",
        region: "Regional VII (Sulawesi, Maluku, Papua)",
        address: "Jl. AP. Pettarani No.2, Masale, Panakkukang, Kota Makassar, Sulawesi Selatan 90222",
        phone: "(0411) 855555",
        status: "Terintegrasi",
        coords: [-5.1586, 119.4350]
      },
      {
        id: "witel_yog",
        name: "Telkom Witel Yogyakarta",
        region: "Witel Yogyakarta",
        address: "Jl. Yos Sudarso No.9, Kotabaru, Gondokusuman, Kota Yogyakarta, DI Yogyakarta 55224",
        phone: "(0274) 512222",
        status: "Terintegrasi",
        coords: [-7.7829, 110.3756]
      },
      {
        id: "witel_bali",
        name: "Telkom Witel Bali",
        region: "Witel Bali",
        address: "Jl. Serma Cok Ngurah Gambir No.2, Dauh Puri, Denpasar Barat, Kota Denpasar, Bali 80232",
        phone: "(0361) 226161",
        status: "Terintegrasi",
        coords: [-8.6713, 115.2239]
      },
      {
        id: "witel_pap",
        name: "Telkom Witel Jayapura",
        region: "Witel Papua (KTI)",
        address: "Jl. Koti No.2, Gurabesi, Jayapura Utara, Kota Jayapura, Papua 99111",
        phone: "(0967) 533333",
        status: "Terintegrasi",
        coords: [-2.5381, 140.7014]
      },
      {
        id: "witel_mnd",
        name: "Telkom Witel Manado",
        region: "Witel Sulawesi Utara (KTI)",
        address: "Jl. Wolter Monginsidi No.85, Sario Tumpaan, Sario, Kota Manado, Sulawesi Utara 95114",
        phone: "(0431) 8802222",
        status: "Terintegrasi",
        coords: [1.4705, 124.8315]
      }
    ];

    // Inisialisasi Map (Centering di Indonesia)
    let map = L.map('map', {
      zoomControl: false // Kita taruh custom zoom control nanti
    }).setView([-2.5, 118.0], 5);

    // Zoom Control Custom Posisi Kanan Atas
    L.control.zoom({
      position: 'topright'
    }).addTo(map);

    // OpenStreetMap Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Custom Icon dengan Pulsing Effect (Radar)
    const customRadarIcon = L.divIcon({
      className: 'pulsing-marker-wrapper',
      html: '<div class="pulsing-marker"></div>',
      iconSize: [20, 20],
      iconAnchor: [10, 10]
    });

    const markersMap = {}; // Untuk melacak marker berdasarkan id kantor

    // Loop untuk menambahkan marker ke peta
    telkomOffices.forEach(office => {
      const marker = L.marker(office.coords, { icon: customRadarIcon })
        .addTo(map)
        .bindPopup(`
          <div class="popup-container">
            <div class="popup-content-title">${office.name}</div>
            <div style="font-size: 0.75rem; color: #ff8e8a; font-weight:600; margin-bottom:4px;">${office.region}</div>
            <div class="popup-content-address">${office.address}</div>
            <div style="font-size: 0.8rem; margin-bottom: 8px;">
              <i class="bi bi-telephone-fill" style="color:#ff3b30; margin-right:4px;"></i> ${office.phone}
            </div>
            <a href="https://www.google.com/maps/search/?api=1&query=${office.coords[0]},${office.coords[1]}" target="_blank" class="popup-link-btn">
              <i class="bi bi-geo-alt-fill"></i> Google Maps
            </a>
          </div>
        `);
      
      markersMap[office.id] = marker;
    });

    // Menampilkan daftar kantor di sidebar
    const listContainer = document.getElementById('officeListContainer');

    function renderList(dataList) {
      listContainer.innerHTML = '';
      
      if (dataList.length === 0) {
        listContainer.innerHTML = `<p class="text-white-50 text-center mt-4">Kantor tidak ditemukan.</p>`;
        return;
      }

      dataList.forEach(office => {
        const card = document.createElement('div');
        card.className = `office-card`;
        card.id = `card-${office.id}`;
        card.setAttribute('onclick', `focusOnOffice('${office.id}')`);
        
        card.innerHTML = `
          <div class="office-card-header">
            <h5 class="office-name">${office.name}</h5>
            <span class="office-badge">${office.status}</span>
          </div>
          <div class="office-detail">
            <i class="bi bi-tag-fill"></i>
            <span>${office.region}</span>
          </div>
          <div class="office-detail">
            <i class="bi bi-geo-alt-fill"></i>
            <span>${office.address}</span>
          </div>
          <div class="office-detail">
            <i class="bi bi-telephone-fill"></i>
            <span>${office.phone}</span>
          </div>
          <div class="btn-action-wrapper">
            <button class="btn-telusuri">
              <i class="bi bi-compass"></i> Telusuri
            </button>
          </div>
        `;
        listContainer.appendChild(card);
      });
    }

    // Render list pertama kali
    renderList(telkomOffices);

    // Fungsi untuk memperbesar peta dan membuka popup saat item diklik
    function focusOnOffice(officeId) {
      const office = telkomOffices.find(o => o.id === officeId);
      if (!office) return;

      // Hapus kelas active dari semua kartu
      document.querySelectorAll('.office-card').forEach(card => {
        card.classList.remove('active-card');
      });

      // Tambah kelas active ke kartu terpilih
      const selectedCard = document.getElementById(`card-${officeId}`);
      if (selectedCard) {
        selectedCard.classList.add('active-card');
        selectedCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      }

      // Fly to koordinat dengan animasi halus
      map.flyTo(office.coords, 14, {
        animate: true,
        duration: 1.5
      });

      // Buka popup marker
      setTimeout(() => {
        markersMap[officeId].openPopup();
      }, 1500);
    }

    // Event listener untuk pencarian
    document.getElementById('searchInput').addEventListener('input', function(e) {
      const query = e.target.value.toLowerCase();
      const filtered = telkomOffices.filter(office => {
        return office.name.toLowerCase().includes(query) || 
               office.region.toLowerCase().includes(query) || 
               office.address.toLowerCase().includes(query);
      });
      renderList(filtered);
    });
  </script>
</body>
</html>
