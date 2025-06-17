<?php
/**
 * Certificate Maintenance Tool
 * Simpan di: prestasi_app/certificate_maintenance.php
 */

include "../../config/koneksi.php";
include "CertificateHelper.php";

$action = $_GET['action'] ?? 'menu';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Maintenance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn { display: inline-block; padding: 10px 20px; margin: 5px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-info { background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; }
        .alert-warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .stat-card { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #dee2e6; }
        .stat-number { font-size: 2em; font-weight: bold; color: #007bff; }
        .log { background: #f8f9fa; padding: 15px; border-radius: 8px; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Certificate Maintenance Tool</h1>
        
        <?php if ($action === 'menu'): ?>
            <div class="alert alert-info">
                <strong>Informasi:</strong> Tool ini digunakan untuk maintenance sertifikat sistem tahfizh.
            </div>
            
            <?php
            // Tampilkan statistik
            $certificateHelper = new CertificateHelper($koneksi);
            
            // Hitung statistik
            $total_prestasi = $koneksi->query("SELECT COUNT(*) as count FROM tb_prestasi")->fetch_assoc()['count'];
            $with_path = $koneksi->query("SELECT COUNT(*) as count FROM tb_prestasi WHERE path_sertifikat IS NOT NULL AND path_sertifikat != ''")->fetch_assoc()['count'];
            $without_path = $total_prestasi - $with_path;
            
            // Cek file yang ada
            $existing_files = 0;
            $certificates_dir = __DIR__ . '/../../assets/certificates/';
            
            if (is_dir($certificates_dir)) {
                $files = glob($certificates_dir . '*.pdf');
                $existing_files = count($files);
            }
            ?>
            
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_prestasi ?></div>
                    <div>Total Prestasi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $with_path ?></div>
                    <div>Dengan Path Sertifikat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $without_path ?></div>
                    <div>Tanpa Path Sertifikat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $existing_files ?></div>
                    <div>File PDF Tersedia</div>
                </div>
            </div>
            
            <h3>🔧 Operasi Maintenance</h3>
            <div style="margin: 20px 0;">
                <a href="?action=regenerate_missing" class="btn btn-primary">🔄 Regenerate Sertifikat yang Hilang</a>
                <a href="?action=check_integrity" class="btn btn-success">✅ Cek Integritas</a>
                <a href="../prestasi" class="btn btn-secondary">← Kembali ke Prestasi</a>
            </div>
            
        <?php elseif ($action === 'regenerate_missing'): ?>
            <h3>🔄 Regenerate Sertifikat yang Hilang</h3>
            <?php
            $certificateHelper = new CertificateHelper($koneksi);
            
            echo "<div class='log'>";
            echo "Memulai proses regenerate sertifikat yang hilang...<br><br>";
            
            $result = $certificateHelper->regenerateAllMissingCertificates();
            
            echo "Proses selesai!<br>";
            echo "- Total diproses: {$result['processed']}<br>";
            echo "- Berhasil: {$result['success']}<br>";
            echo "- Gagal: {$result['failed']}<br>";
            echo "</div>";
            
            echo "<div class='alert alert-success'>";
            echo "<strong>Selesai!</strong> Regenerate sertifikat yang hilang telah selesai.";
            echo "</div>";
            ?>
            <a href="?" class="btn btn-secondary">← Kembali ke Menu</a>
            
        <?php elseif ($action === 'check_integrity'): ?>
            <h3>✅ Cek Integritas Sertifikat</h3>
            <?php
            echo "<div class='log'>";
            echo "Melakukan pengecekan integritas sertifikat...<br><br>";
            
            $total_checked = 0;
            $valid_files = 0;
            $missing_files = 0;
            
            $result = $koneksi->query("
                SELECT p.id, p.path_sertifikat, s.nama, p.total_juz 
                FROM tb_prestasi p 
                JOIN tb_santri s ON p.id_santri = s.id 
                ORDER BY p.id
            ");
            
            while ($row = $result->fetch_assoc()) {
                $total_checked++;
                
                if (empty($row['path_sertifikat'])) {
                    echo "ID {$row['id']} - {$row['nama']}: Tidak ada path sertifikat ⚠️<br>";
                    $missing_files++;
                } else {
                    $full_path = __DIR__ . '/../../' . $row['path_sertifikat'];
                    if (file_exists($full_path)) {
                        $file_size = filesize($full_path);
                        if ($file_size > 0) {
                            echo "ID {$row['id']} - {$row['nama']}: File valid (" . number_format($file_size) . " bytes) ✅<br>";
                            $valid_files++;
                        }
                    } else {
                        echo "ID {$row['id']} - {$row['nama']}: File tidak ditemukan ❌<br>";
                        $missing_files++;
                    }
                }
            }
            
            echo "<br>========== RINGKASAN ==========<br>";
            echo "Total dicek: $total_checked<br>";
            echo "File valid: $valid_files<br>";
            echo "File hilang/tidak ada path: $missing_files<br>";
            echo "</div>";
            
            if ($missing_files > 0) {
                echo "<div class='alert alert-warning'>";
                echo "<strong>Perhatian!</strong> Ditemukan $missing_files sertifikat yang hilang. Jalankan regenerate untuk memperbaiki.";
                echo "</div>";
            } else {
                echo "<div class='alert alert-success'>";
                echo "<strong>Sempurna!</strong> Semua sertifikat dalam kondisi baik.";
                echo "</div>";
            }
            ?>
            <a href="?" class="btn btn-secondary">← Kembali ke Menu</a>
            
        <?php endif; ?>
    </div>
</body>
</html>