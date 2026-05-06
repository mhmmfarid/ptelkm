<?php
include "../konek/koneksi.php"; // Koneksi ke database

// Mendapatkan kode_label dari query string
$kode_label = mysqli_real_escape_string($koneksi, $_GET['kode_label']);

// Query untuk mendapatkan waktu dari tabel qr
$query = "SELECT waktu FROM qr WHERE kode_label = '$kode_label'";
$result = mysqli_query($koneksi, $query);

// Cek apakah kode_label ditemukan di database
if (mysqli_num_rows($result) > 0) {
    // Ambil data waktu dari hasil query
    $row = mysqli_fetch_assoc($result);
    $waktu = $row['waktu'];

    // Mengonversi waktu jika perlu
    // Misal, jika waktu ada dalam format 'Y-m-d H:i:s', Anda dapat konversi menjadi format datetime-local
    $waktuFormatted = date("Y-m-d\TH:i", strtotime($waktu));

    // Mengembalikan waktu dalam format datetime-local (YYYY-MM-DDTHH:MM)
    echo json_encode(['success' => true, 'waktu' => $waktuFormatted]);
} else {
    // Jika kode_label tidak ditemukan
    echo json_encode(['success' => false]);
}
?>
