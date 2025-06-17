<?php

require_once __DIR__ . '/vendor/autoload.php'; // Autoload mPDF
include "../../config/koneksi.php"; // Koneksi database
use \Mpdf\Mpdf;

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('ID tidak valid.');
}

// Query ambil data prestasi dan nama santri
$stmt = $koneksi->prepare("
    SELECT p.total_juz, s.nama 
    FROM tb_prestasi p
    JOIN tb_santri s ON p.id_santri = s.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Cek kalau data tidak ditemukan
if (!$data) {
    die('Data tidak ditemukan.');
}

// Inisialisasi mPDF
$mpdf = new Mpdf([
    'format' => 'A4-L', // Landscape A4
    'orientation' => 'L',
    'default_font' => 'roboto' // Font Roboto
]);

// Set gambar background
$mpdf->SetWatermarkImage('../../assets/img/sertifikat_background.png', 0.8, '', [0,0]);
$mpdf->showWatermarkImage = true;

// Convert nama santri jadi huruf besar semua
$namaSantri = strtoupper($data['nama']);
$totalJuz = $data['total_juz'];

// Isi sertifikat (nama & total juz)
$html = '
<div style="position: relative; width: 100%; height: 100%;">
    <div style="position: absolute; top: 330px; width: 100%; text-align: center; font-family: Roboto, sans-serif; font-size: 56px; font-weight: bold; text-decoration: underline;">
        ' . htmlspecialchars($namaSantri) . '
    </div>
    <div style="position: absolute; top: 470px; width: 100%; text-align: center; font-family: Roboto, sans-serif; font-size: 28px;">
        ' . htmlspecialchars($totalJuz) . ' Juz
    </div>
</div>
';

// Tulis dan outputkan
$mpdf->WriteHTML($html);
$mpdf->Output('sertifikat_'.$namaSantri.'.pdf', 'I'); // langsung buka di browser

?>
