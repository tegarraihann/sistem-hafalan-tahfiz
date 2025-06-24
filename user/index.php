<?php
$title = 'Dashboard';
include 'layouts/header.php';
include 'layouts/navbar.php';

// Ambil data guru berdasarkan session
$username = $_SESSION['username'] ?? null; // atau sesuai dengan key session Anda
$nama_session = $_SESSION['nama'] ?? null;

$konf = null;
if ($username) {
    // Query ke tabel pengguna untuk mendapatkan data guru
    $stmt = $koneksi->prepare("SELECT * FROM tb_pengguna WHERE username = ? AND level = 'Guru'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $konf = $result->fetch_assoc();
    }
    $stmt->close();
}

// Jika tidak ditemukan berdasarkan username, coba berdasarkan nama
if (!$konf && $nama_session) {
    $stmt = $koneksi->prepare("SELECT * FROM tb_pengguna WHERE nama = ? AND level = 'Guru'");
    $stmt->bind_param("s", $nama_session);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $konf = $result->fetch_assoc();
    }
    $stmt->close();
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
                                        <?= $konf ? htmlspecialchars($konf["nama"]) : 'Data tidak ditemukan'; ?>
                                    </div>
                                    <div class="text-slate-500">
                                        NIP :
                                        <?= $konf && !empty($konf["nip"]) ? htmlspecialchars($konf["nip"]) : 'Tidak tersedia'; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r pt-5 lg:pt-0">
                                <div class="font-medium text-center lg:text-left lg:mt-3">Informasi Details</div>
                                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                                    <div class="truncate sm:whitespace-normal flex items-center">
                                        Tanggal Lahir :
                                        <?php
                                        if ($konf && !empty($konf["tanggal_lahir"])) {
                                            echo date('d F Y', strtotime($konf["tanggal_lahir"]));
                                        } else {
                                            echo 'Tidak tersedia';
                                        }
                                        ?>
                                    </div>
                                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                                        No HandPhone :
                                        <?= $konf && !empty($konf["no_hp"]) ? htmlspecialchars($konf["no_hp"]) : 'Tidak tersedia'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: Informasi Guru -->

                <!-- Diagram Status Hafalan per Kelas -->
                <div class="col-span-12 mt-8">
                    <div class="box p-5 bg-white rounded-lg shadow-sm border">
                        <h2 class="text-lg font-medium mb-5">Status Hafalan Santri per Kelas</h2>
                        <div class="chart-container" style="height: 400px;">
                            <canvas id="barChartKelas"></canvas>
                        </div>
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

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $labels[] = $row['nama'];
                            $ulang[] = $row['jumlah_mengulang'];
                            $tidak_ulang[] = $row['jumlah_lancar'];
                        }
                    }
                }
                ?>

            </div>
        </div>
    </div>
</div>
<!-- END: Content -->

<!-- Chart.js CDN -->
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
                        labels: <?= json_encode($labels) ?>,
                        datasets: [
                            {
                                label: 'Mengulang',
                                data: <?= json_encode($ulang) ?>,
                                backgroundColor: '#F43F5E',
                                borderRadius: 5,
                                barThickness: 40
                            },
                            {
                                label: 'Tidak Mengulang',
                                data: <?= json_encode($tidak_ulang) ?>,
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
                                max: Math.max(...<?= json_encode($ulang) ?>, ...<?= json_encode($tidak_ulang) ?>) + 2
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