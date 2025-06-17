<?php
session_start();
include "../../config/koneksi.php";
$currentDate = date('Y-m-d H:i:s');

$nama_guru = $_POST['nama_guru'];
$tanggallahir = $_POST['tanggallahir'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

// Periksa apakah Email sudah ada di database
$cek_query = "SELECT COUNT(*) as jumlah FROM tb_guru WHERE email = ?";
$stmt = $koneksi->prepare($cek_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['jumlah'] > 0) {
    echo "<script>alert('Email sudah ada di database. Masukkan Email yang berbeda.');</script>";
    echo "<script> document.location.href='../guru';</script>";
} else {
    // Simpan data guru
    $tambah = $koneksi->query(
        "INSERT INTO tb_guru (nama, tanggallahir, email, no_hp, created_at) 
         VALUES ('$nama_guru','$tanggallahir','$email','$no_hp','$currentDate')"
    );

    // Generate password dari tanggal lahir (format: ddmmyyyy)
    $password_plain = date('dmY', strtotime($tanggallahir));
    $password_sha1 = md5(sha1(md5($password_plain)));

    // Simpan ke tabel pengguna
    $addUser = $koneksi->query(
        "INSERT INTO tb_pengguna (username, nama, password, level, foto, tanggal) 
         VALUES ('$email','$nama_guru','$password_sha1','Guru','','$currentDate')"
    );

    $_SESSION['pesan'] = 'Data Berhasil Di Tambah';
    $_SESSION['status'] = 'success';  
    echo "<script> document.location.href='../guru';</script>";
    die();
}

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>
