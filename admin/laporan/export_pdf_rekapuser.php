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

    // Query data santri
    $query = "SELECT s.id AS id_santri, s.nis, s.nama, s.kelas
              FROM tb_santri s
              WHERE nis = '$cari'";

    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    // Mulai buffer HTML
    ob_start();
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<h2>Laporan Rekap Data Hafalan Santri</h2>

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

            // Ambil total juz dari tb_prestasi
            $prestasi_sql = "SELECT SUM(total_juz) AS total_juz FROM tb_prestasi WHERE id_santri = $id";
            $prestasi_result = mysqli_query($koneksi, $prestasi_sql);
            $prestasi_data = mysqli_fetch_assoc($prestasi_result);
            $prestasi = $prestasi_data['total_juz'] ? $prestasi_data['total_juz'] . ' Juz' : '-';
            echo "<td>" . $prestasi . "</td>";

            // Hitung total surat dari tb_hafalan_baru
            $surat_sql = "SELECT COUNT(DISTINCT surat) AS total_surat FROM tb_hafalan_baru WHERE ID_Santri = $id";
            $surat_result = mysqli_query($koneksi, $surat_sql);
            $surat_data = mysqli_fetch_assoc($surat_result);
            $total_surat = $surat_data['total_surat'] ? $surat_data['total_surat'] . ' Surat' : '-';
            echo "<td>" . $total_surat . "</td>";

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
    $mpdf->Output('rekap_hafalan_santri.pdf', 'I');

    // Tutup koneksi
    mysqli_close($koneksi);
    exit;
} else {
    header("Location: index.php");
    exit;
}
?>
