<?php
$title = 'Prestasi';

include 'layouts/header.php';
include 'layouts/navbar.php';
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">

    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-lg font-medium mr-auto"><?= $title ?> </h2>
        <!-- <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="kelas" class="btn btn-outline-secondary shadow-md mr-2">Kembali</a>
        </div> -->
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#tambah-prestasi"
                class="btn btn-primary shadow-md mr-2">+ Tambah Prestasi Santri</a>
        </div>
    </div>
    <!-- BEGIN: HTML Table Data -->
    <div class="intro-y box p-5 mt-5">
        <!-- <div class="flex flex-col sm:flex-row sm:items-end xl:items-start">
            <form id="tabulator-html-filter-form" class="xl:flex sm:mr-auto">
                <div class="sm:flex items-center sm:mr-4">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Field</label>
                    <select id="tabulator-html-filter-field"
                        class="form-select w-full sm:w-32 2xl:w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="name">Name</option>
                        <option value="category">Category</option>
                        <option value="remaining_stock">Remaining Stock</option>
                    </select>
                </div>
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Type</label>
                    <select id="tabulator-html-filter-type" class="form-select w-full mt-2 sm:mt-0 sm:w-auto">
                        <option value="like" selected>like</option>
                        <option value="=">=</option>
                        <option value="<">&lt;</option>
                        <option value="<=">&lt;=</option>
                        <option value=">">></option>
                        <option value=">=">>=</option>
                        <option value="!=">!=</option>
                    </select>
                </div>
                <div class="sm:flex items-center sm:mr-4 mt-2 xl:mt-0">
                    <label class="w-12 flex-none xl:w-auto xl:flex-initial mr-2">Value</label>
                    <input id="tabulator-html-filter-value" type="text"
                        class="form-control sm:w-40 2xl:w-full mt-2 sm:mt-0" placeholder="Search...">
                </div>
                <div class="mt-2 xl:mt-0">
                    <button id="tabulator-html-filter-go" type="button"
                        class="btn btn-primary w-full sm:w-16">Go</button>
                    <button id="tabulator-html-filter-reset" type="button"
                        class="btn btn-secondary w-full sm:w-16 mt-2 sm:mt-0 sm:ml-1">Reset</button>
                </div>
            </form>
        </div> -->

        <div class="card">
            <br>
            <div class="overflow-x-auto">
                <table id="example" class="table table-striped">
                    <thead>
                        <tr>
                            <th class="whitespace-nowrap">#</th>
                            <th class="whitespace-nowrap">NIS</th>
                            <th class="whitespace-nowrap">Nama</th>
                            <th class="whitespace-nowrap">Kelas</th>
                            <th class="whitespace-nowrap">Total Juz</th>
                            <th class="whitespace-nowrap">Tanggal</th>
                            <th class="whitespace-nowrap">Email Orang Tua</th>
                            <th class="whitespace-nowrap">Status Notifikasi</th>
                            <th class="whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 
                        $nomor_urut = 1; 
                        $query = $koneksi->query("SELECT p.*, s.nis, s.kelas, s.nama FROM tb_prestasi p LEFT JOIN tb_santri s ON p.id_santri = s.id ORDER BY p.updated_at DESC"); 
                        $data_santri = $query->fetch_all(MYSQLI_ASSOC);
                        if (empty($data_santri)) {
                            // Tampilkan pesan jika tabel kosong
                            echo "<strong> Data belum ada. </strong>";
                        } else {

                        foreach ($query as $data) : ?>

                        <tr class="intro-x">
                            <td>
                                <?= $nomor_urut ?>
                            </td>
                            <td class="w-40"><?= $data['nis'] ?></td>
                            <td><?= $data['nama'] ?></td>
                            <td><?= $data['kelas'] ?></td>
                            <td><?= $data['total_juz'] ?></td>
                            <td><?= $data['tanggal'] ?></td>
                            <td><?= $data['email_ortu'] ?></td>
                            <td>
                             <?= $data['status_notif'] == 'Terkirim' 
                             ? '<span style="color:green;">Terkirim</span>' 
                                : '<span style="color:red;">Gagal Terkirim</span>'; 
                             ?>
                            </td>

                            <td class="table-report__action w-56">
                                <div class="flex justify-center items-center">
                                    <a href="javascript:;" data-tw-toggle="modal"
                                        data-tw-target="#edit-prestasi<?=$data['id']?>"
                                        class="btn btn-warning btn-sm mr-1 mb-2"><i data-lucide="edit"
                                            class="w-5 h-5"></i> Edit</a>

                                    <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-modal-preview<?= $data['id']?>"
                                        class="btn btn-danger btn-sm mr-1 mb-2"><i data-lucide="trash"
                                            class="w-5 h-5"></i> Hapus</a>

                                     <a href="prestasi_app/sertifikat.php?id=<?= $data['id'] ?>" target="_blank"
                                        class="btn btn-success btn-sm mr-1 mb-2"><i data-lucide="download"
                                            class="w-5 h-5"></i> Unduh Sertifikat</a>

                                </div>
                            </td>

                        </tr>
                        <?php include 'prestasi_app/edit.php'; ?>

                        <div id="delete-modal-preview<?= $data['id']?>" class="modal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body p-0">
                                        <div class="p-5 text-center">
                                            <i data-lucide="x-circle" class="w-16 h-16 text-danger mx-auto mt-3"></i>
                                            <div class="text-3xl mt-5">Apakah anda yakin?</div>
                                            <div class="text-slate-500 mt-2">
                                                Apakah anda benar-benar ingin menghapus data ini?
                                            </div>
                                        </div>
                                        <div class="px-5 pb-8 text-center">
                                            <button type="button" data-tw-dismiss="modal"
                                                class="btn btn-outline-secondary w-24 mr-1">Cancel</button>
                                            <a href="prestasi_app/hapus?id=<?= $data['id']?>"
                                                class="btn btn-danger w-24">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php $nomor_urut++; endforeach; ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!-- END: HTML Table Data -->
</div>


<!-- BEGIN: Large Modal Content -->
<div id="tambah-prestasi" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php include 'prestasi_app/tambah.php'; ?>
        </div>
    </div>
</div>
<!-- END: Large Modal Content -->

<?php
include 'layouts/footer.php';
?>