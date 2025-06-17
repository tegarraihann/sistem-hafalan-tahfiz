<!-- BEGIN: Large Modal Content -->
<div id="edit-prestasi<?=$data['id']?>" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form action="prestasi_app/edit-simpan" method="post">
  <div class="modal-content">
    <!-- BEGIN: Modal Header -->
    <div class="modal-header">
      <h2 class="font-medium text-base mr-auto">Edit Prestasi</h2>
    </div> <!-- END: Modal Header -->
    <!-- BEGIN: Modal Body -->
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
      <input type="text" name="id" value="<?= $data['id'] ?>" hidden>
      <div class="col-span-12 sm:col-span-12"> 
        <label for="id_santri" class="form-label text-primary font-medium">Pilih Nama Santri</label>
        <select id="id_santri" name="id_santri" data-search="true" class="form-control tom-select w-full">
        <option value="<?= $data['id_santri'] ?>"><?= $data['nis'].' - '.$data['nama'] ?></option>
        <?php
              $query = "SELECT * FROM tb_santri";
              $hasil = mysqli_query($koneksi, $query);
              while ($row = mysqli_fetch_assoc($hasil)) {
                echo "<option value='".$row['id']."'>".$row['nis']." - ".$row['nama']."</option> ";
              }
              ?>
      </select>
      </div>
      <div class="col-span-12 sm:col-span-6"> <label for="total_juz" class="form-label">Total Juz</label>
        <input id="total_juz" name="total_juz" type="text" class="form-control" placeholder="" value="<?= $data['total_juz'] ?>" required>
      </div>

      <div class="col-span-12 sm:col-span-6"> <label for="tanggal" class="form-label">Tanggal</label>
        <input id="tanggal" name="tanggal" type="date" class="form-control" required value="<?= $data['tanggal']; ?>">
      </div>

    </div> <!-- END: Modal Body -->
    <!-- BEGIN: Modal Footer -->
    <div class="modal-footer">
      <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
      <input class="btn btn-primary w-20" type="submit" value="Simpan">
    </div> <!-- END: Modal Footer -->

  </div> <!-- END: Modal Content -->
</form>
    </div>
  </div>
</div>
<!-- END: Large Modal Content -->