<?php
session_start();
include "../../config/koneksi.php";

$currentDate = date('Y-m-d H:i:s');

// Ambil data dari form
$nis = $_POST['nis'];
$tanggal = $_POST['tanggal'];
$juz = $_POST['juz'];
$surat = $_POST['surat'];
$ayat = $_POST['ayat'];
$status = $_POST['status'];

// Ambil ID santri dari tabel tb_santri berdasarkan NIS
$query = $koneksi->prepare("SELECT id FROM tb_santri WHERE nis = ?");
$query->bind_param("s", $nis);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();
$id_santri = $data['id'] ?? null;
$query->close();

// Cek jika santri ditemukan
if (!$id_santri) {
  header("Location: ../hafalan-baru.php?status=gagal");
  exit;
}

// Simpan data ke tabel hafalan_baru
$stmt = $koneksi->prepare("INSERT INTO tb_hafalan_baru (id_santri, tanggal, juz, surat, ayat, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $id_santri, $tanggal, $juz, $surat, $ayat, $status, $currentDate, $currentDate);

if ($stmt->execute()) {
  header("Location: ../hafalan-baru.php?nis=$nis&status=sukses");
} else {
  header("Location: ../hafalan-baru.php?nis=$nis&status=gagal");
}

$stmt->close();
exit;
?>
