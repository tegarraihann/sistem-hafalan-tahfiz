<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

include "../../config/koneksi.php";
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$id = $_POST['id'];
$full_name = mysqli_real_escape_string($koneksi, $_POST['full_name']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);

// Ambil data lama
$result = mysqli_query($koneksi, "SELECT nama, foto FROM tb_pengguna WHERE id = '$id'");
$data = mysqli_fetch_assoc($result);
$nama = $data['nama'];
$foto_lama = $data['foto'];

$foto_simpan = $foto_lama;

// Cek jika ada upload file
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
    $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
    $file_size = $_FILES['foto']['size'];

    if (in_array($file_ext, $allowed_ext) && $file_size <= 2 * 1024 * 1024) { // max 2MB
        $nama_bersih = preg_replace('/[^a-zA-Z0-9]/', '', $nama);
        $nama_baru = 'foto_' . $id . '_' . $nama_bersih . '.' . $file_ext;

        $upload_dir = "../../assets/img/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_dir . $nama_baru)) {
            // Hapus foto lama jika berbeda dan ada
            if (!empty($foto_lama) && $foto_lama !== $nama_baru && file_exists($upload_dir . $foto_lama)) {
                unlink($upload_dir . $foto_lama);
            }

            $foto_simpan = $nama_baru;
        } else {
            $_SESSION['pesana'] = '
            <div class="alert alert-danger show mb-2" role="alert">Gagal mengunggah foto.</div>';
            header("Location: ../ubah-profile");
            exit;
        }
    } else {
        $_SESSION['pesana'] = '
        <div class="alert alert-danger show mb-2" role="alert">Format file tidak diizinkan atau ukuran melebihi 2MB.</div>';
        header("Location: ../ubah-profile");
        exit;
    }
}

// Update data
$update = "UPDATE tb_pengguna SET username = '$username', nama = '$full_name', foto = '$foto_simpan' WHERE id = '$id'";
$sql = mysqli_query($koneksi, $update);

$_SESSION['pesana'] = '
<div class="alert alert-primary show mb-2" role="alert">Profil berhasil diperbarui.</div>';

header("Location: ../ubah-profile");
exit;
