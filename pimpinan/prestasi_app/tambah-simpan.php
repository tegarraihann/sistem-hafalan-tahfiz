<?php
session_start();
include "../../config/koneksi.php";
date_default_timezone_set("Asia/Jakarta");
$currentDate = date('Y-m-d H:i:s');

// Ambil data dari form
$id_santri   = $_POST['id_santri'];
$total_juz   = $_POST['total_juz'];
$tanggal     = $_POST['tanggal'];
$email_ortu  = $_POST['email_ortu'];

// Ambil nama santri
$santri = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama FROM tb_santri WHERE id = '$id_santri'"));
$nama_santri = $santri['nama'] ?? 'Santri';

// Siapkan email
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
$notif_status = 'Gagal Terkirim';

try {
    // Server email pengirim
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';         // Contoh: smtp.gmail.com
    $mail->SMTPAuth   = true;
    $mail->Username   = 'pesantrenit8@gmail.com';    // GANTI: email pengirim
    $mail->Password   = 'mnmy csve pwvu cnna';      // GANTI: password aplikasi/email
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Pengirim dan Penerima
    $mail->setFrom('pesantrenit8@gmail.com', 'Pondok Pesantren Iaanatuth Thalibiin');
    $mail->addAddress($email_ortu);     // Email orang tua

    // Konten email
    $mail->isHTML(true);
    $mail->Subject = "Prestasi Baru: $nama_santri";
    $mail->Body = "
    <h3>Assalamu'alaikum Warahmatullahi Wabarakatuh</h3>
    <p>🌟 Alhamdulillah, anak Bapak/Ibu <strong>$nama_santri</strong> telah mencatatkan prestasi tahfizh baru:</p>
    <ul>
        <li>📖 Total Hafalan: <strong>$total_juz Juz</strong></li>
        <li>📆 Tanggal: <strong>$tanggal</strong></li>
    </ul>
    <p>Terima kasih atas dukungan dan doanya. 🤲🏻</p>
    <p>Wassalamu'alaikum Warahmatullahi Wabarakatuh</p>
    <p><em>Pesan ini dikirim oleh Sistem Hafalan Tahfizh (SIHAT) <strong>Pesantren I'aanatuth Thalibiin</strong></em></p>
    ";

    $mail->send();
    $notif_status = 'Terkirim';

} catch (Exception $e) {
    $notif_status = 'Gagal Terkirim';
}

// Simpan ke database
$koneksi->query(
  "INSERT INTO tb_prestasi (id_santri, total_juz, tanggal, created_at, updated_at, email_ortu, status_notif)
   VALUES ('$id_santri','$total_juz','$tanggal','$currentDate','$currentDate','$email_ortu','$notif_status')"
);

// Notifikasi
$_SESSION['pesan'] = "Data berhasil ditambah dan notifikasi $notif_status.";
$_SESSION['status'] = 'success';
echo "<script>document.location.href='../prestasi';</script>";
die();
?>
