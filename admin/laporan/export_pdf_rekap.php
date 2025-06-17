<?php
require_once __DIR__ . '/vendor/autoload.php'; // Sesuaikan dengan lokasi autoloader mpdf

use \Mpdf\Mpdf;

if (isset($_POST['export_pdf'])) {
    // Koneksi ke database
    include "../../config/koneksi.php";
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING)); 
    session_start();
    if ($koneksi->connect_error) {
        die("Connection failed: " . $koneksi->connect_error);
    }

    $cari = $_POST['cari'];
    if (!empty($cari)) {
        // Query SQL jika filter berdasarkan kelas
        $query = "SELECT s.id AS id_santri, s.nis, s.nama, s.kelas
                  FROM tb_santri s
                  WHERE kelas = '$cari'";
    } else {
        // Query SQL jika tidak ada filter
        $query = "SELECT s.id AS id_santri, s.nis, s.nama, s.kelas
                  FROM tb_santri s";
    }

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    // Menghasilkan data HTML untuk tabel
    ob_start();
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<h2><?= isset($cari) && !empty($cari) ? "Laporan Rekap Data Hafalan Santri Kelas $cari" : "Laporan Rekap Data Hafalan Santri"; ?></h2>

<table>
    <tr>
        <th>No</th>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
        <th>Prestasi</th>
        <th>Total Surat</th>
    </tr>

<?php
    $nomor_urut = 1;
    foreach ($result as $data) :
        $id = $data['id_santri'];

        echo "<tr>";
        echo "<td>" . $nomor_urut . "</td>";
        echo "<td>" . $data['nis'] . "</td>";
        echo "<td>" . $data['nama'] . "</td>";
        echo "<td>" . $data['kelas'] . "</td>";

        // Ambil data prestasi (total juz) dari tabel tb_prestasi
        $sqlPrestasi = "SELECT SUM(total_juz) AS total_juz FROM tb_prestasi WHERE id_santri = $id";
        $resPrestasi = mysqli_query($koneksi, $sqlPrestasi);
        $dataJuz = mysqli_fetch_assoc($resPrestasi);
        $totalJuz = $dataJuz['total_juz'] ?? 0;
        echo "<td>" . $totalJuz . " Juz</td>";

        // Hitung total surat dari tb_hafalan_baru
        $sqlSurat = "SELECT COUNT(DISTINCT surat) AS total_surat FROM tb_hafalan_baru WHERE ID_Santri = $id";
        $resSurat = mysqli_query($koneksi, $sqlSurat);
        $dataSurat = mysqli_fetch_assoc($resSurat);
        $totalSurat = $dataSurat['total_surat'] ?? 0;
        echo "<td>" . $totalSurat . " Surat</td>";
        echo "</tr>";

        $nomor_urut++;
    endforeach;
?>
</table>

<?php
    $html = ob_get_clean();

    // Buat objek MPDF
    $mpdf = new Mpdf();

    // Tambahkan HTML ke MPDF
    $mpdf->WriteHTML($html);

    // Outputkan PDF ke browser
    $mpdf->Output('rekap_hafalan_santri.pdf', 'I'); // 'I' = tampilkan di browser

    // Tutup koneksi
    mysqli_close($koneksi);
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
