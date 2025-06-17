# DOKUMENTASI FITUR WHATSAPP FONNTE
## SISTEM HAFALAN TAHFIZH (SIHAT)

---

## 📋 DAFTAR ISI

1. [Pendahuluan](#pendahuluan)
2. [Persyaratan Sistem](#persyaratan-sistem)
3. [Instalasi dan Konfigurasi](#instalasi-dan-konfigurasi)
4. [Struktur File](#struktur-file)
5. [Penjelasan Kode](#penjelasan-kode)
6. [Cara Penggunaan](#cara-penggunaan)
7. [Testing dan Debugging](#testing-dan-debugging)
8. [Troubleshooting](#troubleshooting)
9. [FAQ](#faq)
10. [Monitoring dan Analytics](#monitoring-dan-analytics)
11. [Keamanan](#keamanan)
12. [Optimasi Performa](#optimasi-performa)
13. [Maintenance](#maintenance)
14. [Pengembangan Lanjutan](#pengembangan-lanjutan)
15. [Support dan Bantuan](#support-dan-bantuan)
16. [Penutup](#penutup)

---

## 📖 PENDAHULUAN

Fitur WhatsApp Fonnte adalah sistem notifikasi otomatis yang terintegrasi dengan Sistem Hafalan Tahfizh (SIHAT). Fitur ini memungkinkan pengiriman notifikasi WhatsApp secara otomatis kepada orang tua santri ketika anak mereka mencapai prestasi hafalan baru.

### Fitur Utama:
- ✅ Pengiriman notifikasi WhatsApp otomatis
- ✅ Generate sertifikat PDF otomatis
- ✅ Validasi nomor WhatsApp
- ✅ Logging untuk monitoring
- ✅ Status pengiriman real-time
- ✅ Template pesan yang dapat dikustomisasi

### Teknologi yang Digunakan:
- **PHP 7.4+**
- **MySQL/MariaDB**
- **Fonnte API**
- **mPDF Library**
- **cURL**

---

## 🔧 PERSYARATAN SISTEM

### Server Requirements:
- **PHP**: 7.4 atau lebih tinggi
- **MySQL**: 5.7 atau MariaDB 10.3+
- **Extension PHP**:
  - curl
  - json
  - mbstring
  - gd (untuk PDF generation)
- **Internet Connection**: Untuk akses API Fonnte

### Akun Fonnte:
- Token API Fonnte aktif
- Device WhatsApp yang terhubung
- Saldo/quota Fonnte yang mencukupi

---

## ⚙️ INSTALASI DAN KONFIGURASI

### Langkah 1: Persiapan File

Pastikan struktur folder Anda sebagai berikut:

```
prestasi_app/
├── tambah.php
├── tambah-simpan.php
├── fonnte_config.php
├── CertificateHelper.php
├── sertifikat.php
├── edit.php
├── hapus.php
└── vendor/ (jika menggunakan Composer)
```

### Langkah 2: Konfigurasi Database

Pastikan tabel `tb_prestasi` memiliki kolom berikut:

```sql
ALTER TABLE tb_prestasi ADD COLUMN IF NOT EXISTS whatsapp_ortu VARCHAR(20);
ALTER TABLE tb_prestasi ADD COLUMN IF NOT EXISTS status_notif ENUM('Terkirim', 'Gagal Terkirim') DEFAULT 'Gagal Terkirim';
ALTER TABLE tb_prestasi ADD COLUMN IF NOT EXISTS path_sertifikat VARCHAR(255);
```

### Langkah 3: Setup Token Fonnte

1. Login ke dashboard Fonnte (https://fonnte.com)
2. Buat device WhatsApp baru atau gunakan yang sudah ada
3. Copy token API dari device tersebut
4. Buka file `fonnte_config.php`
5. Ganti `YOUR_FONNTE_TOKEN_HERE` dengan token asli Anda:

```php
define('FONNTE_TOKEN', 'abc123xyz789'); // Token asli Anda
```

### Langkah 4: Set Permission Folder

```bash
chmod 755 prestasi_app/
chmod 666 prestasi_app/fonnte_log.txt
chmod 755 assets/certificates/
```

---

## 📁 STRUKTUR FILE

### 1. `fonnte_config.php`
File konfigurasi utama untuk API Fonnte yang berisi:
- Token API
- Class FontteAPI untuk komunikasi dengan server Fonnte
- Template pesan WhatsApp
- Fungsi normalisasi nomor telepon

```php
<?php
// Konfigurasi sederhana untuk Fontte API
define('FONNTE_TOKEN', 'YOUR_FONNTE_TOKEN_HERE');

class FontteAPI {
    private $token;
    
    public function __construct($token) {
        $this->token = $token;
    }
    
    public function sendMessage($target, $message) {
        // Implementasi pengiriman pesan
    }
    
    public static function generatePrestasiMessage($nama, $juz, $tanggal) {
        // Template pesan prestasi
    }
}
?>
```

### 2. `tambah-simpan.php`
File processing untuk menambahkan prestasi baru yang melakukan:
- Validasi input form
- Simpan data ke database
- Kirim notifikasi WhatsApp
- Generate sertifikat PDF
- Redirect dengan pesan status

### 3. `tambah.php`
Form input untuk menambahkan prestasi santri dengan validasi JavaScript

### 4. `CertificateHelper.php`
Class helper untuk generate sertifikat PDF otomatis

---

## 💻 PENJELASAN KODE

### Class FontteAPI

```php
class FontteAPI {
    private $token;
    
    public function __construct($token) {
        $this->token = $token;
    }
    
    public function sendMessage($target, $message) {
        // Normalisasi nomor
        if (substr($target, 0, 1) == '0') {
            $target = '62' . substr($target, 1);
        }
        
        $data = [
            'target' => $target,
            'message' => $message
        ];
        
        // Setup cURL untuk API call
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $this->token
            ),
        ));
        
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        return [
            'success' => ($httpcode == 200 && empty($error)),
            'response' => $response,
            'error' => $error,
            'target' => $target
        ];
    }
    
    public static function generatePrestasiMessage($nama_santri, $total_juz, $tanggal) {
        return "*Assalamu'alaikum Warahmatullahi Wabarakatuh*\n\n".
               "🌟 Alhamdulillah, anak Bapak/Ibu *$nama_santri* telah mencatatkan prestasi tahfizh baru:\n".
               "📖 *Total Hafalan:* $total_juz Juz\n".
               "📆 *Tanggal:* $tanggal\n\n".
               "Terima kasih atas dukungan dan doanya. 🤲🏻\n\n".
               "*Pesantren I'aanatuth Thalibiin - Sistem Hafalan Tahfizh (SIHAT)*";
    }
}
```

**Fungsi Utama:**
- `sendMessage()`: Mengirim pesan ke nomor target
- `generatePrestasiMessage()`: Membuat template pesan prestasi
- `normalizePhoneNumber()`: Mengubah format nomor ke internasional

### Flow Pengiriman WhatsApp

1. **Input Form** → User mengisi form prestasi
2. **Validasi** → Sistem validasi input (nomor, data santri, dll)
3. **Database Insert** → Simpan data prestasi ke database
4. **Generate Certificate** → Buat sertifikat PDF otomatis
5. **Send WhatsApp** → Kirim notifikasi via Fonnte API
6. **Update Status** → Update status pengiriman di database
7. **User Feedback** → Tampilkan hasil ke user

### Template Pesan WhatsApp

```
*Assalamu'alaikum Warahmatullahi Wabarakatuh*

🌟 Alhamdulillah, anak Bapak/Ibu *[NAMA_SANTRI]* telah mencatatkan prestasi tahfizh baru:
📖 *Total Hafalan:* [TOTAL_JUZ] Juz
📆 *Tanggal:* [TANGGAL]

Terima kasih atas dukungan dan doanya. 🤲🏻

*Pesantren I'aanatuth Thalibiin - Sistem Hafalan Tahfizh (SIHAT)*
```

### Proses Validasi Input

```php
// Validasi nomor WhatsApp
if (!preg_match('/^(62|0)[0-9]{8,13}$/', $whatsapp)) {
    $_SESSION['pesan'] = "❌ Format nomor WhatsApp tidak valid";
    $_SESSION['status'] = 'error';
    echo "<script>document.location.href='../prestasi';</script>";
    exit;
}

// Validasi data santri
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
```

---

## 🎯 CARA PENGGUNAAN

### Untuk Admin/Operator:

#### 1. Akses Halaman Prestasi
- Login ke sistem SIHAT
- Pilih menu "Sertifikat" atau "Prestasi"

#### 2. Tambah Prestasi Baru
- Klik tombol "Tambah Sertifikat Santri"
- Isi form dengan data:
  - **Pilih nama santri**: Dropdown dengan data santri aktif
  - **Input total juz**: Angka 1-30 sesuai hafalan
  - **Pilih tanggal**: Tanggal pencapaian prestasi
  - **Masukkan nomor WhatsApp**: Nomor orang tua santri

#### 3. Format Nomor WhatsApp
- **Format Indonesia**: `08xxxxxxxxxx`
- **Format Internasional**: `628xxxxxxxxxx`
- **Sistem akan otomatis menormalisasi** ke format internasional

#### 4. Submit Form
- Klik tombol "Simpan"
- Tunggu proses (generate sertifikat + kirim WhatsApp)
- Sistem akan menampilkan status hasil

### Contoh Pengisian Form:

```
Nama Santri: Ahmad Ridho (Dropdown selection)
Total Juz: 5
Tanggal: 2025-06-17
WhatsApp Orang Tua: 08123456789
```

### Monitoring Status:

**Status di Tabel:**
- 🟢 **Terkirim**: WhatsApp berhasil dikirim
- 🔴 **Gagal Terkirim**: WhatsApp gagal dikirim

**Log File:**
- Cek file `fonnte_log.txt` untuk detail pengiriman
- Format log: `[timestamp] => [nomor] | [response] | [error]`

### Screenshot Status (Contoh):

```
┌─────────────────────────────────────────────────────────────┐
│ Status Notifikasi WhatsApp                                  │
├─────────────────────────────────────────────────────────────┤
│ ✅ Data prestasi berhasil ditambahkan.                     │
│ ✅ Sertifikat berhasil dibuat.                             │
│ 📱 Notifikasi WhatsApp berhasil dikirim ke 628123456789    │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔍 TESTING DAN DEBUGGING

### 1. Test Koneksi API

Buat file `test_fonnte.php` untuk testing koneksi:

```php
<?php
include 'fonnte_config.php';

// Test basic connection
$fonnte = new FontteAPI(FONNTE_TOKEN);
$result = $fonnte->sendMessage('628123456789', 'Test pesan dari SIHAT');

echo "Status: " . ($result['success'] ? 'Berhasil' : 'Gagal') . "\n";
echo "Response: " . $result['response'] . "\n";
echo "Error: " . $result['error'] . "\n";
echo "Target: " . $result['target'] . "\n";
?>
```

### 2. Debug Mode

Tambahkan di awal file `tambah-simpan.php` untuk debugging:

```php
// DEBUG MODE - hapus setelah testing
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log semua POST data
file_put_contents('debug.log', 
    date('Y-m-d H:i:s') . " POST: " . print_r($_POST, true) . "\n", 
    FILE_APPEND
);
```

### 3. Cek Log File

Monitor log real-time dengan command:

```bash
# Linux/Mac
tail -f prestasi_app/fonnte_log.txt

# Windows (PowerShell)
Get-Content prestasi_app/fonnte_log.txt -Wait
```

### 4. Test Database Connection

```php
<?php
include '../../config/koneksi.php';

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}
echo "Database connected successfully\n";

// Test query
$result = $koneksi->query("SELECT COUNT(*) as total FROM tb_prestasi");
$row = $result->fetch_assoc();
echo "Total prestasi: " . $row['total'] . "\n";
?>
```

### 5. Validasi Token Fonnte

```php
<?php
// test_token.php
include 'fonnte_config.php';

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.fonnte.com/validate',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'Authorization: ' . FONNTE_TOKEN
    ),
));

$response = curl_exec($curl);
$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
?>
```

---

## ⚠️ TROUBLESHOOTING

### Error: "File tidak ditemukan"

**Penyebab**: File `fonnte_config.php` atau `CertificateHelper.php` tidak ada

**Solusi**:
1. Pastikan file ada di folder `prestasi_app/`
2. Cek nama file (case sensitive di Linux)
3. Cek permission file (readable)

```bash
# Cek file existence
ls -la prestasi_app/fonnte_config.php
ls -la prestasi_app/CertificateHelper.php

# Set permission jika perlu
chmod 644 prestasi_app/fonnte_config.php
```

### Error: "Token tidak valid"

**Penyebab**: Token Fonnte salah atau expired

**Solusi**:
1. Login ke dashboard Fontte (https://fontte.com)
2. Cek status device WhatsApp
3. Generate token baru jika perlu
4. Update file `fontte_config.php`

```php
// Cek di fontte_config.php
define('FONNTE_TOKEN', 'token_baru_dari_dashboard');
```

### Error: "Nomor tidak valid"

**Penyebab**: Format nomor WhatsApp salah

**Solusi**:
1. Gunakan format: `08xxxxxxxxxx` atau `628xxxxxxxxxx`
2. Pastikan nomor aktif dan terdaftar WhatsApp
3. Cek regex validasi di kode

**Format Valid**:
```
✅ 08123456789
✅ 628123456789
✅ 081234567890
✅ 6281234567890

❌ 8123456789 (tanpa 0 atau 62)
❌ +628123456789 (dengan +)
❌ 08123-456-789 (dengan strip)
```

### WhatsApp terkirim tapi tidak diterima

**Penyebab**: 
- Nomor tidak aktif
- WhatsApp diblokir
- Quota Fontte habis
- Device WhatsApp offline

**Solusi**:
1. Cek saldo/quota Fonnte di dashboard
2. Verifikasi nomor aktif dan terinstall WhatsApp
3. Pastikan device Fontte online
4. Cek log response dari API

### Error: "ArgumentCountError bind_param"

**Penyebab**: Jumlah parameter tidak sesuai dengan placeholder

**Solusi**:
```php
// Salah: 7 placeholder tapi 6 parameter type
$stmt->bind_param("iissss", $id_santri, $total_juz, $tanggal, $currentDate, $currentDate, $whatsapp_normalized, $notif_status);

// Benar: 7 placeholder dengan 7 parameter type
$stmt->bind_param("iisssss", $id_santri, $total_juz, $tanggal, $currentDate, $currentDate, $whatsapp_normalized, $notif_status);
```

### Sertifikat tidak ter-generate

**Penyebab**: 
- Library mPDF tidak terinstall
- Permission folder salah
- Memory limit PHP terlalu kecil

**Solusi**:
1. Install mPDF via Composer:
```bash
composer require mpdf/mpdf
```

2. Set permission folder:
```bash
chmod 755 assets/certificates/
chmod 666 assets/certificates/
```

3. Increase memory limit:
```php
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);
```

### Error: "cURL timeout"

**Penyebab**: Koneksi internet lambat atau server Fonnte down

**Solusi**:
```php
// Increase timeout di fonnte_config.php
curl_setopt($curl, CURLOPT_TIMEOUT, 60); // 60 detik
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); // 30 detik
```

---

## ❓ FAQ

### Q: Bisakah menggunakan API WhatsApp lain selain Fonnte?

**A**: Ya, bisa. Anda perlu memodifikasi class `FontteAPI` untuk menyesuaikan dengan API yang digunakan. Contoh untuk WabLas:

```php
class WabLasAPI {
    private $token;
    private $device_id;
    
    public function __construct($token, $device_id) {
        $this->token = $token;
        $this->device_id = $device_id;
    }
    
    public function sendMessage($target, $message) {
        $data = [
            'phone' => $target,
            'message' => $message,
            'device' => $this->device_id
        ];
        
        // Endpoint WabLas
        $url = 'https://jkt.wablas.com/api/send-message';
        
        // Setup cURL sesuai dokumentasi WabLas
        // ...
    }
}
```

### Q: Bagaimana cara mengubah template pesan?

**A**: Edit fungsi `generatePrestasiMessage()` di file `fonnte_config.php`:

```php
public static function generatePrestasiMessage($nama_santri, $total_juz, $tanggal) {
    return "*🎉 PRESTASI TAHFIZH BARU 🎉*\n\n".
           "Alhamdulillah, ananda *$nama_santri* telah menyelesaikan hafalan:\n".
           "📚 *$total_juz Juz* Al-Qur'an\n".
           "📅 Tanggal: $tanggal\n\n".
           "Barakallahu fiikum 🤲\n".
           "_Pesantren I'aanatuth Thalibiin_";
}
```

### Q: Bisakah mengirim gambar/file melalui WhatsApp?

**A**: Fonnte mendukung pengiriman media. Modifikasi fungsi `sendMessage()`:

```php
public function sendMessageWithImage($target, $message, $image_url) {
    $data = [
        'target' => $target,
        'message' => $message,
        'url' => $image_url, // URL gambar yang bisa diakses public
        'filename' => 'sertifikat.jpg' // optional
    ];
    
    // Rest of the code same...
}
```

### Q: Bagaimana cara backup log WhatsApp?

**A**: Buat script backup otomatis:

```php
<?php
// backup_log.php
$source = 'prestasi_app/fonnte_log.txt';
$backup_dir = 'backup/whatsapp_logs/';

// Buat folder backup jika belum ada
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$backup_file = $backup_dir . 'fonnte_log_' . date('Y-m-d_H-i-s') . '.txt';

if (file_exists($source)) {
    copy($source, $backup_file);
    echo "Log berhasil dibackup ke: $backup_file\n";
    
    // Clear original log setelah backup
    file_put_contents($source, '');
} else {
    echo "File log tidak ditemukan\n";
}
?>
```

Jalankan dengan cron job:
```bash
# Backup setiap hari jam 23:59
59 23 * * * /usr/bin/php /path/to/backup_log.php
```

### Q: Bisakah mengirim ke multiple nomor sekaligus?

**A**: Ya, implementasi broadcast message:

```php
public function broadcastMessage($nomor_list, $message) {
    $results = [];
    
    foreach ($nomor_list as $nomor) {
        $result = $this->sendMessage($nomor, $message);
        $results[] = [
            'nomor' => $nomor,
            'status' => $result['success'] ? 'berhasil' : 'gagal',
            'response' => $result['response']
        ];
        
        // Delay untuk menghindari rate limiting
        sleep(1);
    }
    
    return $results;
}

// Penggunaan:
$nomor_ortu = ['628123456789', '628987654321', '628555666777'];
$pesan = "Pengumuman penting dari pesantren...";
$hasil = $fonnte->broadcastMessage($nomor_ortu, $pesan);
```

### Q: Bagaimana menangani rate limiting?

**A**: Implementasi queue system sederhana:

```php
// Tabel queue
CREATE TABLE whatsapp_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sent_at TIMESTAMP NULL
);

// Function untuk add ke queue
function addToQueue($nomor, $pesan) {
    global $koneksi;
    $stmt = $koneksi->prepare("INSERT INTO whatsapp_queue (nomor, pesan) VALUES (?, ?)");
    $stmt->bind_param("ss", $nomor, $pesan);
    return $stmt->execute();
}

// Process queue (jalankan via cron)
function processQueue() {
    global $koneksi;
    $fonnte = new FontteAPI(FONNTE_TOKEN);
    
    $result = $koneksi->query("SELECT * FROM whatsapp_queue WHERE status = 'pending' LIMIT 10");
    
    while ($row = $result->fetch_assoc()) {
        $send_result = $fontte->sendMessage($row['nomor'], $row['pesan']);
        
        $new_status = $send_result['success'] ? 'sent' : 'failed';
        $stmt = $koneksi->prepare("UPDATE whatsapp_queue SET status = ?, sent_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $new_status, $row['id']);
        $stmt->execute();
        
        sleep(2); // Delay 2 detik antar pengiriman
    }
}
```

### Q: Bagaimana cara monitoring quota Fonnte?

**A**: Buat endpoint untuk cek quota:

```php
function checkFontteQuota() {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/quota',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . FONNTE_TOKEN
        ),
    ));
    
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if ($httpcode == 200) {
        $data = json_decode($response, true);
        return [
            'success' => true,
            'quota_remaining' => $data['quota'] ?? 0,
            'quota_used' => $data['used'] ?? 0
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to check quota'];
}

// Usage
$quota = checkFontteQuota();
if ($quota['success']) {
    echo "Quota tersisa: " . $quota['quota_remaining'];
}
```

---

