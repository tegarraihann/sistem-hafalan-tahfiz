<?php
date_default_timezone_set('Asia/Makassar');

// Hindari session_start() ganda
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/koneksi.php';

// Cek login
if (!isset($_SESSION["username"])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location= '../index';</script>";
    exit;
}

// Ambil data dari session
$pimpinan = $_SESSION['username'];
$level = $_SESSION['level'];
$nama = $_SESSION['nama'];
$id = $_SESSION["id"];
$foto = isset($_SESSION["foto"]) ? $_SESSION["foto"] : '';

// Fungsi tanggal Indonesia
function tgl_indo($tanggal) {
    $bulan = array (
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
             'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Batasi akses berdasarkan folder dan level
$currentFolder = basename(dirname($_SERVER['PHP_SELF']));
$aksesFolder = [
    'pimpinan' => 'Pimpinan',
    'guru'     => 'Guru',
    'admin'    => 'Admin'
];

// Cek apakah user berhak mengakses folder ini
if (isset($aksesFolder[$currentFolder]) && $aksesFolder[$currentFolder] != $level) {
    echo "<script>alert('Anda tidak memiliki akses ke halaman ini!'); window.location= '../index';</script>";
    exit;
}

// Siapkan title default jika belum diset
if (!isset($title)) {
    $title = "Dashboard";
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<!-- BEGIN: Head -->
<head>
    <meta charset="utf-8">
    <link href="../assets/img/favicon.png" rel="shortcut icon">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SIHAT - Sistem Hafalan Tahfizh Al-Qur'an">
    <meta name="keywords" content="SIHAT, tahfizh, hafalan, dashboard, santri, admin">
    <meta name="author" content="LEFT4CODE">
    <title><?= htmlspecialchars($title) ?> - SIHAT Al-Qur'an</title>

    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="../assets/dist/css/app.css" />
    <!-- END: CSS Assets-->

    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/dist/dataTables/jquery.dataTables.min.css" />
    
    <style>
        /* Tambahan CSS untuk memastikan layout tidak broken */
        body {
            min-height: 100vh;
        }
        
        .content {
            min-height: calc(100vh - 200px);
        }
        
        /* Fix untuk chart container */
        #barChartKelas {
            max-height: 400px !important;
            height: 400px !important;
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            max-height: 400px;
            overflow: hidden;
        }
    </style>
</head>
<!-- END: Head -->

<body class="py-5 md:py-0 bg-black/[0.15] dark:bg-transparent">