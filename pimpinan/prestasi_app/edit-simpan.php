<?php
include "../../config/koneksi.php";
// mysqli_report (MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// error_reporting(E_ALL ^ (E_NOTICE | E_WARNING)); 
session_start(); 
$currentDate = date('Y-m-d H:i:s');

    $id = $_POST['id'];
    $id_santri = $_POST['id_santri'];
    $total_juz = $_POST['total_juz'];
    $tanggal = $_POST['tanggal'];

    $update = "UPDATE tb_prestasi SET id_santri='$id_santri', total_juz ='$total_juz', tanggal = '$tanggal', updated_at = '$currentDate' WHERE id = '".$id."' ";

    $sql = mysqli_query($koneksi, $update);

        $_SESSION['pesan'] = 'Data Berhasil Di Edit';
        $_SESSION['status'] = 'success';
        echo "<script> document.location.href='../prestasi';</script>";
        die();  
    ?>