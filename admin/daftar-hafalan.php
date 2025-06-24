<?php
$title = 'Daftar Hafalan';
include 'layouts/header.php';
include 'layouts/navbar.php';

$nisFilter = $_GET['nis'] ?? '';
$kelasFilter = $_GET['kelas'] ?? '';
$statusFilter = $_GET['status'] ?? '';
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto"><?= $title ?></h2>
    <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
      <a href="hafalan-baru.php" class="btn btn-primary shadow-md">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Hafalan
      </a>
    </div>
  </div>

  <?php
  // Menampilkan notifikasi status
  if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses') {
      $santri_name = $_GET['santri'] ?? '';
      echo '<div class="alert alert-success-soft show flex items-center mb-5" role="alert">
              <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i>
              Data hafalan untuk santri <strong>' . htmlspecialchars($santri_name) . '</strong> berhasil ditambahkan!
            </div>';
    } elseif ($_GET['status'] == 'updated') {
      echo '<div class="alert alert-success-soft show flex items-center mb-5" role="alert">
              <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i>
              Data hafalan berhasil diperbarui!
            </div>';
    } elseif ($_GET['status'] == 'deleted') {
      echo '<div class="alert alert-success-soft show flex items-center mb-5" role="alert">
              <i data-lucide="check-circle" class="w-6 h-6 mr-2"></i>
              Data hafalan berhasil dihapus!
            </div>';
    } elseif ($_GET['status'] == 'gagal') {
      $error = $_GET['error'] ?? '';
      $error_message = 'Terjadi kesalahan!';
      
      switch ($error) {
        case 'data_kosong':
          $error_message = 'Harap lengkapi semua data yang diperlukan!';
          break;
        case 'santri_tidak_ditemukan':
          $error_message = 'Data santri tidak ditemukan!';
          break;
        case 'kelas_tidak_ditemukan':
          $error_message = 'Data kelas tidak ditemukan!';
          break;
        case 'database_error':
          $error_message = 'Terjadi kesalahan pada database!';
          break;
      }
      
      echo '<div class="alert alert-danger-soft show flex items-center mb-5" role="alert">
              <i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
              ' . $error_message . '
            </div>';
    }
  }
  ?>

  <div class="min-h-screen bg-white col-span-12">
    <div class="intro-y box mt-5">
      <div class="overflow-x-auto">
        <div class="p-5">
          <!-- Form Pencarian dan Filter -->
          <form method="GET" class="mb-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div>
                <label class="form-label">NIS Santri</label>
                <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" value="<?= htmlspecialchars($nisFilter) ?>">
              </div>
              <div>
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control">
                  <option value="">Semua Kelas</option>
                  <?php
                  $kelas_query = "SELECT DISTINCT k.id, k.nama_kelas FROM tb_kelas k 
                                  INNER JOIN tb_hafalan_baru h ON k.id = h.id_kelas 
                                  ORDER BY k.nama_kelas";
                  $kelas_result = mysqli_query($koneksi, $kelas_query);
                  while ($kelas = mysqli_fetch_assoc($kelas_result)) {
                    $selected = ($kelasFilter == $kelas['id']) ? 'selected' : '';
                    echo '<option value="' . $kelas['id'] . '" ' . $selected . '>' . htmlspecialchars($kelas['nama_kelas']) . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                  <option value="">Semua Status</option>
                  <option value="tidak mengulang" <?= ($statusFilter == 'tidak mengulang') ? 'selected' : '' ?>>Tidak Mengulang</option>
                  <option value="mengulang" <?= ($statusFilter == 'mengulang') ? 'selected' : '' ?>>Mengulang</option>
                </select>
              </div>
              <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">
                  <i data-lucide="search" class="w-4 h-4 mr-2"></i> Cari
                </button>
                <?php if (!empty($nisFilter) || !empty($kelasFilter) || !empty($statusFilter)): ?>
                  <a href="?" class="btn btn-outline-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-2"></i> Reset
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </form>

          <div class="grid grid-cols-12 gap-x-5">
            <div class="col-span-12">
              <table id="hafalanTable" class="display table table-striped">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th>Kelas</th>
                    <th>Juz</th>
                    <th>Surat</th>
                    <th>Ayat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $nomor_urut = 1;
                  $hasil = null;
                  $modals = [];

                  // Build query berdasarkan filter
                  $where_conditions = [];
                  $params = [];
                  $types = '';

                  if (!empty($nisFilter)) {
                    $where_conditions[] = "s.nis LIKE ?";
                    $params[] = "%$nisFilter%";
                    $types .= 's';
                  }

                  if (!empty($kelasFilter)) {
                    $where_conditions[] = "k.id = ?";
                    $params[] = $kelasFilter;
                    $types .= 'i';
                  }

                  if (!empty($statusFilter)) {
                    $where_conditions[] = "h.status = ?";
                    $params[] = $statusFilter;
                    $types .= 's';
                  }

                  // Query utama - tampilkan semua data atau berdasarkan filter
                  $base_query = "SELECT h.*, s.nis, s.nama, k.nama_kelas as kelas 
                                FROM tb_hafalan_baru h 
                                INNER JOIN tb_santri s ON h.id_santri = s.id
                                INNER JOIN tb_kelas k ON h.id_kelas = k.id";

                  if (!empty($where_conditions)) {
                    $base_query .= " WHERE " . implode(" AND ", $where_conditions);
                  }

                  $base_query .= " ORDER BY h.updated_at DESC, h.tanggal DESC";

                  // Prepare statement
                  if ($stmt = mysqli_prepare($koneksi, $base_query)) {
                    if (!empty($params)) {
                      mysqli_stmt_bind_param($stmt, $types, ...$params);
                    }
                    
                    mysqli_stmt_execute($stmt);
                    $hasil = mysqli_stmt_get_result($stmt);
                    
                    if (!$hasil) {
                      echo "<tr><td colspan='10' class='text-center text-danger'>Error: " . mysqli_error($koneksi) . "</td></tr>";
                    }
                  } else {
                    echo "<tr><td colspan='10' class='text-center text-danger'>Error preparing statement: " . mysqli_error($koneksi) . "</td></tr>";
                  }

                  if (!empty($hasil) && mysqli_num_rows($hasil) > 0):
                    while ($data = mysqli_fetch_assoc($hasil)):
                  ?>
                  <tr>
                    <td><?= $nomor_urut++ ?></td>
                    <td><?= !empty($data['tanggal']) ? date('d F Y', strtotime($data['tanggal'])) : '-' ?></td>
                    <td><?= htmlspecialchars($data['nis'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['nama'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['kelas'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['juz'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['surat'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['ayat'] ?? '-') ?></td>
                    <td>
                      <?php if (strtolower($data['status']) == 'tidak mengulang'): ?>
                        <span class="text-black">
                          Tidak Mengulang
                        </span>
                      <?php else: ?>
                        <span class="text-black">
                          Mengulang
                        </span>
                      <?php endif; ?>
                    </td>
                    <td class="table-report__action w-56">
                      <div class="flex justify-center items-center">
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#detail-hafalan<?= $data['id'] ?>" class="btn btn-info btn-sm mr-1 mb-2" title="Detail">
                          <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-hafalanbaru<?= $data['id'] ?>" class="btn btn-warning btn-sm mr-1 mb-2" title="Edit">
                          <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview<?= $data['id']?>" class="btn btn-danger btn-sm mb-2" title="Hapus">
                          <i data-lucide="trash" class="w-4 h-4"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <!-- Detail Modal -->
                  <div id="detail-hafalan<?= $data['id'] ?>" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h2 class="font-medium text-base mr-auto">Detail Hafalan</h2>
                          <button type="button" class="btn btn-outline-secondary hidden sm:flex" data-tw-dismiss="modal">
                            <i data-lucide="x" class="w-4 h-4 mr-2"></i> Tutup
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                              <label class="font-medium">Tanggal:</label>
                              <p class="mt-1"><?= !empty($data['tanggal']) ? date('d F Y', strtotime($data['tanggal'])) : '-' ?></p>
                            </div>
                            <div>
                              <label class="font-medium">NIS:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['nis'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Nama Santri:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['nama'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Kelas:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['kelas'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Juz:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['juz'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Surat:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['surat'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Ayat:</label>
                              <p class="mt-1"><?= htmlspecialchars($data['ayat'] ?? '-') ?></p>
                            </div>
                            <div>
                              <label class="font-medium">Status:</label>
                              <p class="mt-1">
                                <?php if (strtolower($data['status']) == 'tidak mengulang'): ?>
                                  <span class="badge bg-success text-white">Tidak Mengulang</span>
                                <?php else: ?>
                                  <span class="badge bg-warning text-white">Mengulang</span>
                                <?php endif; ?>
                              </p>
                            </div>
                            <?php if (!empty($data['created_at'])): ?>
                            <div class="col-span-2">
                              <label class="font-medium">Dibuat pada:</label>
                              <p class="mt-1"><?= date('d F Y H:i:s', strtotime($data['created_at'])) ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($data['updated_at']) && $data['updated_at'] != $data['created_at']): ?>
                            <div class="col-span-2">
                              <label class="font-medium">Terakhir diupdate:</label>
                              <p class="mt-1"><?= date('d F Y H:i:s', strtotime($data['updated_at'])) ?></p>
                            </div>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php 
                  // Include edit modal jika file ada
                  if (file_exists('daftarhafalan_app/edit.php')) {
                    include 'daftarhafalan_app/edit.php'; 
                  }
                  ?>

                  <!-- Delete Modal -->
                  <div id="delete-modal-preview<?= $data['id'] ?>" class="modal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-body p-0">
                          <div class="p-5 text-center">
                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                            <div class="text-3xl mt-5">Apakah anda yakin?</div>
                            <div class="text-slate-500 mt-2">
                              Apakah anda benar-benar ingin menghapus data hafalan ini?<br>
                              <strong><?= htmlspecialchars($data['nama']) ?> - <?= htmlspecialchars($data['surat']) ?></strong>
                            </div>
                          </div>
                          <div class="px-5 pb-8 text-center">
                            <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">Batal</button>
                            <a href="hafalanbaru_app/hapus?id=<?= $data['id'] ?>" class="btn btn-danger w-24">Hapus</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php 
                    endwhile; 
                  else:
                    $message = 'Belum ada data hafalan.';
                    if (!empty($nisFilter) || !empty($kelasFilter) || !empty($statusFilter)) {
                      $message = 'Tidak ada data hafalan ditemukan dengan filter yang dipilih.';
                    }
                  ?>
                  <tr>
                    <td colspan="10" class="text-center text-slate-500 py-8">
                      <i data-lucide="database" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                      <div class="text-lg font-medium mb-2"><?= $message ?></div>
                      <?php if (empty($nisFilter) && empty($kelasFilter) && empty($statusFilter)): ?>
                      <div class="text-sm">
                        <a href="hafalan-baru.php" class="text-primary">Klik disini untuk menambah data hafalan pertama</a>
                      </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- DataTables Script -->
  <script>
    $(document).ready(function () {
      // Auto hide alerts after 5 seconds
      setTimeout(function() {
        $('.alert').fadeOut('slow');
      }, 5000);

      // Inisialisasi DataTables dengan konfigurasi yang benar
      $('#hafalanTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "language": {
          "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
        },
        "responsive": true,
        "autoWidth": false,
        "columnDefs": [
          { "orderable": false, "targets": [9] }, // Kolom aksi tidak bisa diurutkan
          { "className": "text-center", "targets": [0, 4, 5, 8, 9] } // Center alignment untuk kolom tertentu
        ],
        "order": [[1, 'desc']], // Urutkan berdasarkan tanggal (kolom ke-1) secara descending
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
               '<"row"<"col-sm-12"tr>>' +
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        "drawCallback": function(settings) {
          // Reinitialize Lucide icons after table redraw
          if (typeof lucide !== 'undefined') {
            lucide.createIcons();
          }
        }
      });
    });
  </script>

<?php 
// Close statement if it was opened
if (isset($stmt)) {
  mysqli_stmt_close($stmt);
}
include 'layouts/footer.php'; 
?>