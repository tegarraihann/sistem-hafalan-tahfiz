<?php
// Konfigurasi sederhana untuk Fonnte API
// Ganti dengan token Fonnte Anda yang sebenarnya
define('FONNTE_TOKEN', '53VBwfkf1STFmgff6fT1');

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
        
        // Log untuk debugging
        file_put_contents(__DIR__ . '/fonnte_log.txt', 
            date('Y-m-d H:i:s') . " => " . $target . " | " . $response . PHP_EOL, 
            FILE_APPEND | LOCK_EX
        );
        
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
?>