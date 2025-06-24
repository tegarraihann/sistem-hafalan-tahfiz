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
<!-- END: Content -->

<!-- Load Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    // Pastikan Chart.js sudah dimuat
    document.addEventListener('DOMContentLoaded', function () {
        // Cek apakah data tersedia
        const kelasLabels = <?= json_encode($kelas_labels) ?>;
        const hafalanUlang = <?= json_encode($hafalan_ulang) ?>;
        const hafalanTidakUlang = <?= json_encode($hafalan_tidak_ulang) ?>;

        // Jika tidak ada data, tampilkan pesan
        if (kelasLabels.length === 0) {
            const canvas = document.getElementById('barChartKelas');
            const ctx = canvas.getContext('2d');
            ctx.font = '16px Arial';
            ctx.textAlign = 'center';
            ctx.fillStyle = '#64748b';
            ctx.fillText('Tidak ada data untuk ditampilkan', canvas.width / 2, canvas.height / 2);
            return;
        }

        const ctxKelas = document.getElementById('barChartKelas').getContext('2d');
        const barChartKelas = new Chart(ctxKelas, {
            type: 'bar',
            data: {
                labels: kelasLabels,
                datasets: [
                    {
                        label: 'Mengulang',
                        data: hafalanUlang,
                        backgroundColor: '#DC2626', // merah
                        borderRadius: 5,
                        borderWidth: 1
                    },
                    {
                        label: 'Tidak Mengulang',
                        data: hafalanTidakUlang,
                        backgroundColor: '#16A34A', // hijau
                        borderRadius: 5,
                        borderWidth: 1
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
                            precision: 0
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    });
</script>

<?php include 'layouts/footer.php'; ?>