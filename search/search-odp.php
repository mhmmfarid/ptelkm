<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
<tbody id="odp-results">
  <?php
  include '../konek/koneksi.php';

  // Ambil nilai pencarian dari query string
  $search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

  // Query untuk filter berdasarkan search atau tampilkan semua data jika tidak ada pencarian
  if (!empty($search)) {
      // Jika ada search, lakukan pencarian berdasarkan ID ODP menggunakan LIKE
      $query = "SELECT * FROM odp WHERE id_odp LIKE '%$search%'";
  } else {
      // Jika tidak ada pencarian, tampilkan semua data
      $query = "SELECT * FROM odp";
  }

  // Eksekusi query dan tampilkan hasilnya
  $tampil = mysqli_query($koneksi, $query);

  // Periksa apakah ada hasil query
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
            <td id="status-<?php echo $data['id_odp']; ?>"><?php echo $data['status_odp']; ?></td>
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
