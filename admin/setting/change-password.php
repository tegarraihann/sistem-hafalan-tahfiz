<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include "../../config/koneksi.php";
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$id = $_POST['id'];
$oldpassword = $_POST['oldpassword'];
$newpassword = $_POST['newpassword'];
$passwordrepeat = $_POST['passwordrepeat'];

// Ambil data user
$query = $koneksi->query("SELECT * FROM tb_pengguna WHERE id = '$id'");
$userData = $query->fetch_assoc();

// Hashing metode lama (md5(sha1(md5())))
$hash_old = md5(sha1(md5($oldpassword)));
$hash_new = md5(sha1(md5($newpassword)));

// Verifikasi password lama
if ($hash_old != $userData['password']) {
    $_SESSION['pesana'] = '
    <div class="alert alert-danger show flex items-center mb-2" role="alert">
        <i data-lucide="alert-octagon" class="w-6 h-6 mr-2"></i>
        Password lama tidak sesuai, mohon pastikan password yang diinput benar.
    </div>';
    echo "<script>document.location.href='../ubah-password';</script>";
    exit;
}

// Cek apakah password baru sama dengan password lama
if ($oldpassword === $newpassword) {
    $_SESSION['pesana'] = '
    <div class="alert alert-danger show flex items-center mb-2" role="alert">
        <i data-lucide="alert-triangle" class="w-6 h-6 mr-2"></i>
        Password baru tidak boleh sama dengan password lama.
    </div>';
    echo "<script>document.location.href='../ubah-password';</script>";
    exit;
}

// Cek kesamaan password baru dan konfirmasi
if ($newpassword === $passwordrepeat) {
    // Update password
    $update = $koneksi->query("UPDATE tb_pengguna SET password='$hash_new' WHERE id = '$id'");
    $_SESSION['pesana'] = '
    <div class="alert alert-primary show mb-2" role="alert">
        Password Berhasil DiUbah
    </div>';
    echo "<script>document.location.href='../ubah-password';</script>";
    exit;
} else {
    $_SESSION['pesana'] = '
    <div class="alert alert-warning show flex items-center mb-2" role="alert">
        <i data-lucide="alert-triangle" class="w-6 h-6 mr-2"></i>
        New Password dan Repeat Password tidak sama, mohon pastikan password yang diinput benar.
    </div>';
    echo "<script>document.location.href='../ubah-password';</script>";
    exit;
}
?>
