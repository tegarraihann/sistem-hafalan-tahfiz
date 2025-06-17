<?php
/**
 * CertificateHelper - Utility class untuk mengelola sertifikat
 * Simpan file ini di: prestasi_app/CertificateHelper.php
 */

require_once __DIR__ . '/vendor/autoload.php';
use \Mpdf\Mpdf;

class CertificateHelper {
    
    private $koneksi;
    private $certificates_dir;
    private $background_path;
    
    public function __construct($database_connection) {
        $this->koneksi = $database_connection;
        $this->certificates_dir = __DIR__ . '/../../assets/certificates/';
        $this->background_path = __DIR__ . '/../../assets/img/sertifikat_background.png';
        
        // Buat folder jika belum ada
        if (!is_dir($this->certificates_dir)) {
            mkdir($this->certificates_dir, 0755, true);
        }
    }
    
    /**
     * Generate sertifikat untuk prestasi tertentu
     */
    public function generateCertificate($prestasi_id, $nama_santri, $total_juz, $save_to_file = true) {
        try {
            // Inisialisasi mPDF
            $mpdf = new Mpdf([
                'format' => 'A4-L',
                'orientation' => 'L',
                'default_font' => 'roboto',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0
            ]);

            // Set background jika ada
            if (file_exists($this->background_path)) {
                $mpdf->SetWatermarkImage($this->background_path, 1.0, '', [0, 0]);
                $mpdf->showWatermarkImage = true;
            }

            // Format nama santri
            $namaSantri = strtoupper($nama_santri);

            // HTML content untuk sertifikat
            $html = $this->buildCertificateHTML($namaSantri, $total_juz);

            // Generate PDF
            $mpdf->WriteHTML($html);
            
            if ($save_to_file) {
                // Buat nama file unik
                $filename = $this->generateFilename($prestasi_id, $nama_santri);
                $filepath = $this->certificates_dir . $filename;
                
                // Simpan file
                $mpdf->Output($filepath, 'F');
                
                // Update database
                $relative_path = 'assets/certificates/' . $filename;
                $this->updateCertificatePath($prestasi_id, $relative_path);
                
                return [
                    'success' => true,
                    'message' => 'Sertifikat berhasil dibuat dan disimpan',
                    'file_path' => $relative_path,
                    'full_path' => $filepath
                ];
            } else {
                // Output langsung ke browser
                $filename = 'sertifikat_' . preg_replace('/[^a-zA-Z0-9]/', '_', $namaSantri) . '.pdf';
                $mpdf->Output($filename, 'I');
                return [
                    'success' => true,
                    'message' => 'Sertifikat berhasil ditampilkan',
                    'file_path' => null
                ];
            }
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat sertifikat: ' . $e->getMessage(),
                'file_path' => null
            ];
        }
    }
    
    /**
     * Cek apakah sertifikat sudah ada untuk prestasi tertentu
     */
    public function checkCertificateExists($prestasi_id) {
        $stmt = $this->koneksi->prepare("SELECT path_sertifikat FROM tb_prestasi WHERE id = ?");
        $stmt->bind_param("i", $prestasi_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        if ($data && !empty($data['path_sertifikat'])) {
            $full_path = __DIR__ . '/../../' . $data['path_sertifikat'];
            if (file_exists($full_path)) {
                return [
                    'exists' => true,
                    'file_path' => $data['path_sertifikat'],
                    'full_path' => $full_path
                ];
            }
        }
        
        return [
            'exists' => false,
            'file_path' => null,
            'full_path' => null
        ];
    }
    
    /**
     * Generate nama file unik untuk sertifikat
     */
    private function generateFilename($prestasi_id, $nama_santri) {
        $clean_name = preg_replace('/[^a-zA-Z0-9]/', '_', $nama_santri);
        return 'sertifikat_' . $prestasi_id . '_' . $clean_name . '_' . date('Ymd_His') . '.pdf';
    }
    
    /**
     * Update path sertifikat di database
     */
    private function updateCertificatePath($prestasi_id, $path) {
        $stmt = $this->koneksi->prepare("UPDATE tb_prestasi SET path_sertifikat = ? WHERE id = ?");
        $stmt->bind_param("si", $path, $prestasi_id);
        return $stmt->execute();
    }
    
    /**
     * Build HTML content untuk sertifikat
     */
    private function buildCertificateHTML($nama_santri, $total_juz) {
        return '
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Roboto, sans-serif;
                }
                .nama-santri {
                    position: absolute;
                    top: 350px;
                    left: 0;
                    width: 100%;
                    text-align: center;
                    font-size: 56px;
                    font-weight: bold;
                    color: #000;
                    z-index: 10;
                }
                .total-juz {
                    position: absolute;
                    top: 490px;
                    left: 0;
                    width: 100%;
                    text-align: center;
                    font-size: 28px;
                    color: #000;
                    z-index: 10;
                }
            </style>

            <div class="nama-santri">
                ' . htmlspecialchars($nama_santri) . '
            </div>
            <div class="total-juz">
                ' . htmlspecialchars($total_juz) . ' Juz
            </div>
        ';
    }
    
    /**
     * Regenerate semua sertifikat yang tidak ada filenya
     */
    public function regenerateAllMissingCertificates() {
        $result = ['processed' => 0, 'success' => 0, 'failed' => 0];
        
        $query = "
            SELECT p.id, p.total_juz, p.path_sertifikat, s.nama 
            FROM tb_prestasi p
            JOIN tb_santri s ON p.id_santri = s.id
            ORDER BY p.id DESC
        ";
        
        $stmt = $this->koneksi->query($query);
        while ($row = $stmt->fetch_assoc()) {
            $result['processed']++;
            
            $need_regenerate = false;
            
            if (empty($row['path_sertifikat'])) {
                $need_regenerate = true;
            } else {
                $full_path = __DIR__ . '/../../' . $row['path_sertifikat'];
                if (!file_exists($full_path)) {
                    $need_regenerate = true;
                }
            }
            
            if ($need_regenerate) {
                $generate_result = $this->generateCertificate(
                    $row['id'], 
                    $row['nama'], 
                    $row['total_juz'], 
                    true
                );
                
                if ($generate_result['success']) {
                    $result['success']++;
                } else {
                    $result['failed']++;
                }
            }
        }
        
        return $result;
    }
}
?>