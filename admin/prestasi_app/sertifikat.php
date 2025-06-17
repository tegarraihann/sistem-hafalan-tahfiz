<?php

include "../../config/koneksi.php";
include "CertificateHelper.php";

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    die('ID tidak valid.');
}

// Query ambil data prestasi dan nama santri
$stmt = $koneksi->prepare("
    SELECT p.total_juz, p.path_sertifikat, s.nama 
    FROM tb_prestasi p
    JOIN tb_santri s ON p.id_santri = s.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Cek kalau data tidak ditemukan
if (!$data) {
    die('Data tidak ditemukan.');
}

// Inisialisasi Certificate Helper
$certificateHelper = new CertificateHelper($koneksi);

// Cek apakah sertifikat sudah ada
$certificate_check = $certificateHelper->checkCertificateExists($id);

if ($certificate_check['exists']) {
    // File sertifikat sudah ada, langsung tampilkan
    $namaSantri = strtoupper($data['nama']);
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="sertifikat_' . preg_replace('/[^a-zA-Z0-9]/', '_', $namaSantri) . '.pdf"');
    header('Content-Length: ' . filesize($certificate_check['full_path']));
    readfile($certificate_check['full_path']);
    exit;
} else {
    // File tidak ada, generate ulang
    $certificate_result = $certificateHelper->generateCertificate(
        $id,
        $data['nama'],
        $data['total_juz'],
        true // save to file
    );
    
    if ($certificate_result['success']) {
        // Tampilkan file yang baru dibuat
        $namaSantri = strtoupper($data['nama']);
        
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="sertifikat_' . preg_replace('/[^a-zA-Z0-9]/', '_', $namaSantri) . '.pdf"');
        header('Content-Length: ' . filesize($certificate_result['full_path']));
        readfile($certificate_result['full_path']);
        exit;
    } else {
        // Jika gagal generate dan simpan, coba generate langsung tanpa simpan
        $certificate_result = $certificateHelper->generateCertificate(
            $id,
            $data['nama'],
            $data['total_juz'],
            false // jangan save, langsung output
        );
        
        if (!$certificate_result['success']) {
            die('Gagal membuat sertifikat: ' . $certificate_result['message']);
        }
        exit;
    }
}

?>