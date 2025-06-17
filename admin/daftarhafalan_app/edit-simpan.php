<?php
include "../../config/koneksi.php";
session_start();

$currentDate = date('Y-m-d H:i:s');

// Ambil data dari POST
$id      = $_POST['id'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$juz     = $_POST['juz'] ?? '';
$surat   = $_POST['surat'] ?? '';
$ayat    = $_POST['ayat'] ?? '';

// Validasi dasar (bisa dikembangkan lebih lanjut)
if (empty($id) || empty($tanggal) || empty($juz) || empty($surat) || empty($ayat)) {
    $_SESSION['pesan'] = 'Semua data harus diisi.';
    $_SESSION['status'] = 'warning';
    echo "<script>document.location.href='../hafalan-baru';</script>";
    exit;
}

// Siapkan statement untuk update
$stmt = $koneksi->prepare("UPDATE tb_hafalan_baru SET tanggal = ?, juz = ?, surat = ?, ayat = ?, updated_at = ? WHERE id = ?");
$stmt->bind_param("ssssss", $tanggal, $juz, $surat, $ayat, $currentDate, $id);
$stmt->execute();
$stmt->close();

// Beri notifikasi sukses
$_SESSION['pesan'] = 'Data berhasil diubah.';
$_SESSION['status'] = 'success';
echo "<script>document.location.href='../hafalan-baru';</script>";
exit;
?>
