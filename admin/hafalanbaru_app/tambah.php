<?php
session_start();
include "../../config/koneksi.php";

$currentDate = date('Y-m-d H:i:s');

// Ambil data dari form
$id_santri = $_POST['id_santri'];
$id_kelas = $_POST['id_kelas'];
$tanggal = $_POST['tanggal'];
$juz = $_POST['juz'];
$surat = $_POST['surat'];
$ayat = $_POST['ayat'];
$status = $_POST['status'];

// Validasi data required
if (empty($id_santri) || empty($id_kelas) || empty($tanggal) || empty($juz) || empty($surat) || empty($ayat) || empty($status)) {
    header("Location: ../hafalan-baru.php?status=gagal&error=data_kosong");
    exit;
}

// Verifikasi bahwa santri dan kelas ada di database
$verify_santri = $koneksi->prepare("SELECT id, nama, nis FROM tb_santri WHERE id = ?");
$verify_santri->bind_param("i", $id_santri);
$verify_santri->execute();
$santri_result = $verify_santri->get_result();

if ($santri_result->num_rows === 0) {
    header("Location: ../hafalan-baru.php?status=gagal&error=santri_tidak_ditemukan");
    exit;
}

$santri_data = $santri_result->fetch_assoc();
$verify_santri->close();

$verify_kelas = $koneksi->prepare("SELECT id, nama_kelas FROM tb_kelas WHERE id = ?");
$verify_kelas->bind_param("i", $id_kelas);
$verify_kelas->execute();
$kelas_result = $verify_kelas->get_result();

if ($kelas_result->num_rows === 0) {
    header("Location: ../hafalan-baru.php?status=gagal&error=kelas_tidak_ditemukan");
    exit;
}

$kelas_data = $kelas_result->fetch_assoc();
$verify_kelas->close();

// Simpan data ke tabel tb_hafalan_baru
$stmt = $koneksi->prepare("INSERT INTO tb_hafalan_baru (id_santri, id_kelas, tanggal, juz, surat, ayat, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssssss", $id_santri, $id_kelas, $tanggal, $juz, $surat, $ayat, $status, $currentDate, $currentDate);

if ($stmt->execute()) {
    header("Location: ../hafalan-baru.php?status=sukses&santri=" . urlencode($santri_data['nama']));
} else {
    error_log("Database Error: " . $stmt->error);
    header("Location: ../hafalan-baru.php?status=gagal&error=database_error");
}

$stmt->close();
$koneksi->close();
exit;
?>