<?php
$title = 'Daftar Hafalan';
include 'layouts/header.php';
include 'layouts/navbar.php';

$nisFilter = $_GET['nis'] ?? '';
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto"><?= $title ?></h2>
  </div>

  <div class="min-h-screen bg-white col-span-12">
    <div class="intro-y box mt-5">
      <div class="overflow-x-auto">
        <div class="p-5">
          <!-- Form Pencarian NIS -->
          <form method="GET" class="mb-5">
            <div class="flex items-center gap-2">
              <input type="text" name="nis" class="form-control w-60" placeholder="Masukkan NIS" value="<?= htmlspecialchars($nisFilter) ?>">
              <button type="submit" class="btn btn-primary">Cari</button>
              <?php if (!empty($nisFilter)): ?>
                <a href="?" class="btn btn-outline-secondary">Reset</a>
              <?php endif; ?>
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

                  if (!empty($nisFilter)) {
                    $query = "SELECT h.*, s.nis, s.kelas, s.nama 
                              FROM tb_hafalan_baru h 
                              INNER JOIN tb_santri s ON h.id_santri = s.id
                              WHERE s.nis = '" . mysqli_real_escape_string($koneksi, $nisFilter) . "'
                              ORDER BY h.updated_at DESC";

                    $hasil = mysqli_query($koneksi, $query);
                    
                    if (!$hasil) {
                      echo "<tr><td colspan='10' class='text-center text-danger'>Error: " . mysqli_error($koneksi) . "</td></tr>";
                    }
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
                        <span class="badge bg-success text-white">Tidak Mengulang</span>
                      <?php else: ?>
                        <span class="badge bg-danger text-white">Mengulang</span>
                      <?php endif; ?>
                    </td>
                    <td class="table-report__action w-56">
                      <div class="flex justify-center items-center">
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#edit-hafalanbaru<?= $data['id'] ?>" class="btn btn-warning btn-sm mr-1 mb-2">
                          <i data-lucide="edit" class="w-4 h-4"></i> Edit
                        </a>
                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview<?= $data['id']?>" class="btn btn-danger btn-sm mb-2">
                          <i data-lucide="trash" class="w-4 h-4"></i> Hapus
                        </a>
                      </div>
                    </td>
                  </tr>

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
                            <div class="text-slate-500 mt-2">Apakah anda benar-benar ingin menghapus data ini?</div>
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
                  elseif (!empty($nisFilter)): 
                  ?>
                  <tr>
                    <td colspan="10" class="text-center text-slate-500 py-4">
                      <i data-lucide="search" class="w-8 h-8 mx-auto mb-2 text-slate-400"></i>
                      <div>Tidak ada data hafalan ditemukan untuk NIS: <strong><?= htmlspecialchars($nisFilter) ?></strong></div>
                    </td>
                  </tr>
                  <?php else: ?>
                  <tr>
                    <td colspan="10" class="text-center text-slate-500 py-4">
                      <i data-lucide="info" class="w-8 h-8 mx-auto mb-2 text-slate-400"></i>
                      <div>Masukkan NIS untuk mencari data hafalan</div>
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
               '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
      });
    });
  </script>

<?php include 'layouts/footer.php'; ?>