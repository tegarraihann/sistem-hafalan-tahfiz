<?php
include "../../config/koneksi.php";
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING)); 
session_start();

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$id = $_GET['id'];
$nis = $_GET['email'];

$hapus_guru = "DELETE FROM tb_guru WHERE id = '$id'";
$hapus_pengguna = "DELETE FROM tb_pengguna WHERE nama = '$email'";

$koneksi->query($hapus_guru);
$koneksi->query($hapus_pengguna);

$_SESSION['pesan'] = 'Data Berhasil Di Hapus';
$_SESSION['status'] = 'success';

echo "<script> document.location.href='../guru';</script>";
die();