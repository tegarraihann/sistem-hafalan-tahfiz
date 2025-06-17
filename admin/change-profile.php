<?php
session_start();
include '../config/koneksi.php';

$id = $_POST['id'];
$username = $_POST['username'];
$full_name = $_POST['full_name'];
$foto_lama = $_POST['foto_lama'];

// Cek apakah ada file yang diupload
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $foto_name = $_FILES['foto']['name'];
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $ext = pathinfo($foto_name, PATHINFO_EXTENSION);
    $nama_baru = uniqid('foto_') . '.' . $ext;

    // Pastikan folder upload ada
    $upload_dir = "../assets/img/";
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Pindahkan file upload
    move_uploaded_file($foto_tmp, $upload_dir . $nama_baru);

    // Hapus foto lama jika ada
    if (!empty($foto_lama) && file_exists($upload_dir . $foto_lama)) {
        unlink($upload_dir . $foto_lama);
    }

    $foto_simpan = $nama_baru;
} else {
    // Tidak upload foto baru, gunakan yang lama
    $foto_simpan = $foto_lama;
}

// Update data ke database
$query = $koneksi->query("UPDATE tb_pengguna 
                          SET username = '$username', nama = '$full_name', foto = '$foto_simpan' 
                          WHERE id = '$id'");

if ($query) {
    $_SESSION['pesana'] = '<div class="alert alert-success">Profil berhasil diperbarui.</div>';
} else {
    $_SESSION['pesana'] = '<div class="alert alert-danger">Gagal memperbarui profil.</div>';
}

header("Location: ../setting-profile.php");
exit();
