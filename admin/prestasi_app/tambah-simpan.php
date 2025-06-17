<?php
session_start();
include "../../config/koneksi.php";
include "CertificateHelper.php";
include "fonnte_config.php"; // Include konfigurasi Fonnte
date_default_timezone_set("Asia/Jakarta");
$currentDate = date('Y-m-d H:i:s');

// Validasi input
if (!isset($_POST['id_santri']) || !isset($_POST['total_juz']) || !isset($_POST['tanggal']) || !isset($_POST['whatsapp_ortu'])) {
    $_SESSION['pesan'] = "❌ Data tidak lengkap!";
    $_SESSION['status'] = 'error';
    echo "<script>document.location.href='../prestasi';</script>";
    exit;
}

// Ambil data dari form
$id_santri   = (int) $_POST['id_santri'];
$total_juz   = (int) $_POST['total_juz'];
$tanggal     = $_POST['tanggal'];
$whatsapp    = trim($_POST['whatsapp_ortu']);

// Validasi data
if ($id_santri <= 0 || $total_juz <= 0 || empty($tanggal) || empty($whatsapp)) {
    $_SESSION['pesan'] = "❌ Data tidak valid!";
    $_SESSION['status'] = 'error';
    echo "<script>document.location.href='../prestasi';</script>";
    exit;
}

// Validasi nomor WhatsApp
if (!preg_match('/^(62|0)[0-9]{8,13}$/', $whatsapp)) {
    $_SESSION['pesan'] = "❌ Format nomor WhatsApp tidak valid. Gunakan format 08xxx atau 62xxx (8-13 digit)";
    $_SESSION['status'] = 'error';
    echo "<script>document.location.href='../prestasi';</script>";
    exit;
}

// Ambil nama santri
$stmt = $koneksi->prepare("SELECT nama FROM tb_santri WHERE id = ?");
$stmt->bind_param("i", $id_santri);
$stmt->execute();
$result = $stmt->get_result();
$santri = $result->fetch_assoc();

if (!$santri) {
    $_SESSION['pesan'] = "❌ Data santri tidak ditemukan!";
    $_SESSION['status'] = 'error';
    echo "<script>document.location.href='../prestasi';</script>";
    exit;
}

$nama_santri = $santri['nama'];

// Inisialisasi API Fonnte
$fonnte = new FontteAPI(FONNTE_TOKEN);

// Generate pesan WhatsApp
$pesan = FontteAPI::generatePrestasiMessage($nama_santri, $total_juz, $tanggal);

// Kirim WhatsApp
$whatsapp_result = $fonnte->sendMessage($whatsapp, $pesan);
$notif_status = $whatsapp_result['success'] ? 'Terkirim' : 'Gagal Terkirim';

// Normalisasi nomor untuk disimpan
$whatsapp_normalized = $whatsapp_result['target'];

// Simpan ke database
$stmt = $koneksi->prepare(
    "INSERT INTO tb_prestasi (id_santri, total_juz, tanggal, created_at, updated_at, whatsapp_ortu, status_notif)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("iisssss", $id_santri, $total_juz, $tanggal, $currentDate, $currentDate, $whatsapp_normalized, $notif_status);
$insert_result = $stmt->execute();

if ($insert_result) {
    // Ambil ID prestasi yang baru saja dimasukkan
    $prestasi_id = $koneksi->insert_id;
    
    // Inisialisasi Certificate Helper
    $certificateHelper = new CertificateHelper($koneksi);
    
    // Generate sertifikat otomatis
    $certificate_result = $certificateHelper->generateCertificate(
        $prestasi_id, 
        $nama_santri, 
        $total_juz, 
        true // save to file
    );
    
    // Buat pesan berdasarkan hasil
    $messages = [];
    
    // Status sertifikat
    if ($certificate_result['success']) {
        $messages[] = "✅ Sertifikat berhasil dibuat";
    } else {
        $messages[] = "⚠️ Gagal membuat sertifikat: " . $certificate_result['message'];
    }
    
    // Status WhatsApp
    if ($whatsapp_result['success']) {
        $messages[] = "📱 Notifikasi WhatsApp berhasil dikirim ke " . $whatsapp_normalized;
    } else {
        $messages[] = "📱 Notifikasi WhatsApp gagal dikirim ke " . $whatsapp_normalized;
        if (!empty($whatsapp_result['error'])) {
            $messages[] = "Error: " . $whatsapp_result['error'];
        }
    }
    
    $_SESSION['pesan'] = "✅ Data prestasi berhasil ditambahkan. " . implode(". ", $messages) . ".";
    $_SESSION['status'] = ($certificate_result['success'] && $whatsapp_result['success']) ? 'success' : 'warning';
    
} else {
    $_SESSION['pesan'] = "❌ Gagal menambah data prestasi: " . $koneksi->error;
    $_SESSION['status'] = 'error';
}

echo "<script>document.location.href='../prestasi';</script>";
exit;
?>