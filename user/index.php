<?php
$title = 'Dashboard';
include 'layouts/header.php';
include 'layouts/navbar.php';

$nis = $_SESSION['nama'] ?? null;

$konf = null;
if ($nip) {
    $result = $koneksi->query("SELECT * FROM tb_guru WHERE nip = '$nip'");
    if ($result && $result->num_rows > 0) {
        $konf = $result->fetch_assoc();
    }
}
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-9">
            <div class="grid grid-cols-12 gap-6">
                <!-- BEGIN: Informasi Guru -->
                <div class="col-span-12 mt-2">
                    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                        <h2 class="text-lg font-medium truncate mr-5">Informasi Guru</h2>
                    </div>

                    <div class="intro-y box px-5 pt-5 mt-5">
                        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 pb-5 -mx-5">
                            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                                <div class="ml-5">
                                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                                        <?= $konf ? $konf["nama"] : 'Data tidak ditemukan'; ?>
                                    </div>
                                    <div class="text-slate-500">
                                        NIP : <?= $konf ? $konf["nip"] : 'Tidak tersedia'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r pt-5 lg:pt-0">
                                <div class="font-medium text-center lg:text-left lg:mt-3">Informasi Details</div>
                                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                                    <div class="truncate sm:whitespace-normal flex items-center">
                                        Tanggal Lahir :
                                        <?= $konf && isset($konf["tanggallahir"]) ? date('d F Y', strtotime($konf["tanggallahir"])) : 'Tidak tersedia'; ?>
                                    </div>
                                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                                        No HandPhone : <?= $konf ? $konf["no_hp"] : 'Tidak tersedia'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Informasi Guru -->

                <!-- BEGIN: Grafik Hafalan per Santri -->
                <div class="col-span-12 mt-10">
                    <div class="box p-5">
                        <h2 class="text-lg font-medium mb-5">Grafik Hafalan per Santri</h2>
                        <canvas id="barChartSantri" height="150"></canvas>
                    </div>
                </div>

                <?php
                // Ambil data hafalan dari DB
                $labels = [];
                $ulang = [];
                $tidak_ulang = [];

                if ($koneksi->query("SHOW TABLES LIKE 'tb_hafalan_baru'")->num_rows > 0) {
                    $result = $koneksi->query("
                        SELECT s.nama, 
                               SUM(IF(h.status = 'mengulang', 1, 0)) AS jumlah_mengulang,
                               SUM(IF(h.status = 'lancar', 1, 0)) AS jumlah_lancar
                        FROM tb_hafalan_baru h
                        JOIN tb_santri s ON h.id_santri = s.id
                        GROUP BY s.nama
                    ");

                    while ($row = $result->fetch_assoc()) {
                        $labels[] = $row['nama'];
                        $ulang[] = $row['jumlah_mengulang'];
                        $tidak_ulang[] = $row['jumlah_lancar'];
                    }
                }
                ?>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    const ctx = document.getElementById('barChartSantri').getContext('2d');
                    const barChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?= json_encode($labels) ?>,
                            datasets: [{
                                label: 'Mengulang',
                                backgroundColor: '#f87171',
                                data: <?= json_encode($ulang) ?>
                            }, {
                                label: 'Tidak Mengulang',
                                backgroundColor: '#34d399',
                                data: <?= json_encode($tidak_ulang) ?>
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { position: 'top' },
                                title: { display: true, text: 'Statistik Hafalan per Santri' }
                            }
                        }
                    });
                </script>
                <!-- END: Grafik -->

            </div>
        </div>
    </div>
</div>
<!-- END: Content -->

<?php include 'layouts/footer.php'; ?>
