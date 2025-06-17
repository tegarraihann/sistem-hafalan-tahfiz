<?php
session_start();
if (!isset($_SESSION['level']) || $_SESSION['level'] != 'Pimpinan') {
    echo "<script>alert('anda tidak memiliki akses untuk halaman ini!'); window.location='../';</script>";
    exit;
}

$title = 'Dashboard';
include 'layouts/header.php';
include 'layouts/navbar.php';

$username = $_SESSION['username'];
$level = $_SESSION['level'];
$nama_pengguna = $_SESSION['nama'];

$query = $koneksi->query("SELECT * FROM tb_pengguna WHERE username = '$username'");
$konf = mysqli_fetch_assoc($query);

// Ambil data hafalan per kelas
$kelas_labels = [];
$hafalan_ulang = [];
$hafalan_tidak_ulang = [];

$sql = "SELECT tb_kelas.nama_kelas,
               SUM(CASE WHEN tb_hafalan_baru.status = 'mengulang' THEN 1 ELSE 0 END) AS ulang,
               SUM(CASE WHEN tb_hafalan_baru.status = 'tidak mengulang' THEN 1 ELSE 0 END) AS tidak_ulang
        FROM tb_hafalan_baru
        JOIN tb_santri ON tb_hafalan_baru.id_santri = tb_santri.id
        JOIN tb_kelas ON tb_santri.kelas = tb_kelas.nama_kelas
        GROUP BY tb_kelas.nama_kelas";

$query_chart = mysqli_query($koneksi, $sql);
while ($row = mysqli_fetch_assoc($query_chart)) {
    $kelas_labels[] = $row['nama_kelas'];
    $hafalan_ulang[] = (int)$row['ulang'];
    $hafalan_tidak_ulang[] = (int)$row['tidak_ulang'];
}
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">

                <!-- Selamat Datang -->
                <div class="col-span-12 mt-6 -mb-6 intro-y">
                    <div class="alert alert-dismissible show box bg-primary text-white flex items-center mb-6" role="alert">
                        <span class="text-sm font-medium">
                            <h4>Selamat Datang di Sistem Hafalan Tahfizh (SIHAT) Al-Qur'an</h4>
                        </span>
                    </div>
                </div>

                <!-- Informasi Pengguna -->
                <div class="col-span-12 mt-2">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">
                            Informasi Pengguna
                        </h2>
                    </div>

                    <div class="intro-y box px-5 pt-5 mt-5">
                        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
                            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                                <div class="ml-5">
                                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                                        <?= $konf['nama'] ?>
                                    </div>
                                    <div class="text-slate-500">Level: <?= $konf['level'] ?></div>
                                    <div class="text-slate-500 mt-1">Username: <?= $konf['username'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diagram Status Hafalan per Kelas -->
                <div class="col-span-12 mt-10">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-5">Status Hafalan Santri per Kelas</h2>
                        <canvas id="barChartKelas" height="120"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script>
const ctxKelas = document.getElementById('barChartKelas').getContext('2d');
const barChartKelas = new Chart(ctxKelas, {
    type: 'bar',
    data: {
        labels: <?= json_encode($kelas_labels) ?>,
        datasets: [
            {
                label: 'Mengulang',
                data: <?= json_encode($hafalan_ulang) ?>,
                backgroundColor: '#F43F5E',
                borderRadius: 5
            },
            {
                label: 'Tidak Mengulang',
                data: <?= json_encode($hafalan_tidak_ulang) ?>,
                backgroundColor: '#14B8A6',
                borderRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            title: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php include 'layouts/footer.php'; ?>
