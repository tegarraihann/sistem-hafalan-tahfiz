<?php
include "../../config/koneksi.php";
header('Content-Type: application/json');

if (isset($_POST['idsantri'])) {
    $idsantri = mysqli_real_escape_string($koneksi, $_POST['idsantri']);

    $query = "SELECT nama, kelas FROM tb_santri WHERE nis = '$idsantri'";
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil && mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_assoc($hasil);
        echo json_encode($data);
    } else {
        echo json_encode(['nama' => '', 'kelas' => '']);
    }
} else {
    echo json_encode(['error' => 'Parameter idsantri tidak ditemukan']);
}

mysqli_close($koneksi);
?>
