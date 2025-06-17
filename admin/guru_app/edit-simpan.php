<?php
include "../../config/koneksi.php";
session_start(); 
$currentDate = date('Y-m-d H:i:s');

$id = $_POST['id'];
$nama_guru = $_POST['nama_guru'];
$tanggallahir = $_POST['tanggallahir'];
$email = $_POST['email'];
$no_hp = $_POST['no_hp'];

// Format tanggal lahir jadi ddmmyyyy untuk password
$password_plain = date('dmY', strtotime($tanggallahir));
$password_hash = md5(sha1(md5($password_plain))); // hash sesuai pola sebelumnya

// Update data guru
$update = "UPDATE tb_guru SET nama='$nama_guru', tanggallahir='$tanggallahir', email='$email', no_hp='$no_hp', updated_at='$currentDate' WHERE id='$id'";
$sql = mysqli_query($koneksi, $update);

// Update akun pengguna
$koneksi->query(
    "UPDATE tb_pengguna SET username='$email', password='$password_hash' WHERE username='$email'"
);

$_SESSION['pesan'] = 'Data Berhasil Di Edit';
$_SESSION['status'] = 'success';
echo "<script> document.location.href='../guru';</script>";
die();
?>
