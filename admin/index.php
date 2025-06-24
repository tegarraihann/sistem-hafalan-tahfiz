<?php
$title = 'Dashboard';
include 'layouts/header.php';
include 'layouts/navbar.php';

// Ambil data santri per kelas (jumlah yang tidak ulang dan yang ulang)
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

$query = mysqli_query($koneksi, $sql);

while ($row = mysqli_fetch_assoc($query)) {
    $kelas_labels[] = $row['nama_kelas'];
    $hafalan_ulang[] = (int) $row['ulang'];
    $hafalan_tidak_ulang[] = (int) $row['tidak_ulang'];
}
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: General Report -->
                <div class="col-span-12 mt-6 -mb-6 intro-y">
                    <div class="alert alert-dismissible show box bg-primary text-white flex items-center mb-6"
                        role="alert">
                        <span class="text-sm font-medium">
                            <h4>Selamat Datang di Sistem Hafalan Tahfizh (SIHAT) Al-Quran</h4>
                        </span>
                    </div>
                </div>
                <div class="col-span-12 mt-2">
                    <div class="intro-y flex items-center h-10">
                        <h2 class="text-lg font-medium truncate mr-5">General Report</h2>
                    </div>
                    <div class="grid grid-cols-12 gap-6 mt-5">
                        <!-- Total Santri -->
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="users" class="report-box__icon text-primary"></i>
                                    </div>
                                    <?php
                                    $sql = "SELECT count(id) as a FROM tb_santri";
                                    $query = mysqli_query($koneksi, $sql);
                                    $data = mysqli_fetch_assoc($query);
                                    ?>
                                    <div class="text-3xl font-medium leading-8 mt-6"><?= $data['a']; ?></div>
                                    <div class="text-base text-slate-500 mt-1">Total Santri</div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Kelas -->
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="credit-card" class="report-box__icon text-pending"></i>
                                    </div>
                                    <?php
                                    $sql = "SELECT count(id) as a FROM tb_kelas";
                                    $query = mysqli_query($koneksi, $sql);
                                    $data = mysqli_fetch_assoc($query);
                                    ?>
                                    <div class="text-3xl font-medium leading-8 mt-6"><?= $data['a'] ?></div>
                                    <div class="text-base text-slate-500 mt-1">Total Kelas</div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Prestasi -->
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="slack" class="report-box__icon text-danger"></i>
                                    </div>
                                    <?php
                                    $sql = "SELECT count(id) as a FROM tb_prestasi";
                                    $query = mysqli_query($koneksi, $sql);
                                    $data = mysqli_fetch_assoc($query);
                                    ?>
                                    <div class="text-3xl font-medium leading-8 mt-6"><?= $data['a'] ?></div>
                                    <div class="text-base text-slate-500 mt-1">Total Sertifikat Santri</div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Hafalan -->
                        <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-5">
                                    <div class="flex">
                                        <i data-lucide="archive" class="report-box__icon text-primary"></i>
                                    </div>
                                    <?php
                                    $sql = "SELECT count(id) as a FROM tb_hafalan_baru";
                                    $query = mysqli_query($koneksi, $sql);
                                    $data = mysqli_fetch_assoc($query);
                                    ?>
                                    <div class="text-3xl font-medium leading-8 mt-6"><?= $data['a'] ?></div>
                                    <div class="text-base text-slate-500 mt-1">Total Hafalan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: General Report -->

                <!-- Diagram Status Hafalan per Kelas -->
                <div class="col-span-12 mt-8">
                    <div class="box p-5 bg-white rounded-lg shadow-sm border">
                        <h2 class="text-lg font-medium mb-5">Status Hafalan Santri per Kelas</h2>
                        <div class="chart-container" style="height: 400px;">
                            <canvas id="barChartKelas"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END: Content -->

<!-- Load Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- Chart Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const ctxKelas = document.getElementById('barChartKelas');
            if (ctxKelas) {
                const barChartKelas = new Chart(ctxKelas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($kelas_labels) ?>,
                        datasets: [
                            {
                                label: 'Mengulang',
                                data: <?= json_encode($hafalan_ulang) ?>,
                                backgroundColor: '#F43F5E',
                                borderRadius: 5,
                                barThickness: 40
                            },
                            {
                                label: 'Tidak Mengulang',
                                data: <?= json_encode($hafalan_tidak_ulang) ?>,
                                backgroundColor: '#14B8A6',
                                borderRadius: 5,
                                barThickness: 40
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
                                ticks: {
                                    stepSize: 1,
                                    callback: function (value) {
                                        return Number.isInteger(value) ? value : '';
                                    }
                                },
                                max: Math.max(...<?= json_encode($hafalan_ulang) ?>, ...<?= json_encode($hafalan_tidak_ulang) ?>) + 2
                            },
                            x: {
                                ticks: {
                                    maxTicksLimit: 10
                                }
                            }
                        },
                        layout: {
                            padding: {
                                top: 20,
                                right: 20,
                                bottom: 20,
                                left: 20
                            }
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error creating chart:', error);
            // Tampilkan pesan error di halaman jika chart gagal
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                chartContainer.innerHTML = '<div class="text-center text-red-500 p-4">Error loading chart: ' + error.message + '</div>';
            }
        }
    });
</script>
<?php include 'layouts/footer.php'; ?>