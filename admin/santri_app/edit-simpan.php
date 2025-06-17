<?php
include "../../config/koneksi.php";
session_start(); 
$currentDate = date('Y-m-d H:i:s');

$id = $_POST['id'];
$nis_siswa = $_POST['nis_siswa'];
$kelas = $_POST['kelas'];
$nama_siswa = $_POST['nama_siswa'];
$nama_ortu = $_POST['nama_ortu'];
$tempatlahir = $_POST['tempatlahir'];
$tanggallahir = $_POST['tanggallahir'];
$email_ortu = $_POST['email_ortu'];
$alamat = $_POST['alamat'];

$update = "UPDATE tb_santri SET nis='$nis_siswa', nama ='$nama_siswa', nama_ortu = '$nama_ortu', tempatlahir = '$tempatlahir', tanggallahir = '$tanggallahir', email_ortu = '$email_ortu', alamat = '$alamat', updated_at = '$currentDate' WHERE id = '".$id."' ";
$sql = mysqli_query($koneksi, $update);

$_SESSION['pesan'] = 'Data Berhasil Di Edit';
$_SESSION['status'] = 'success';
echo "<script> document.location.href='../santri?kelas=$kelas';</script>";
die();  
?>
