<!-- BEGIN: Large Modal Content -->
<div id="edit-guru<?=$data['id']?>" class="modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form action="guru_app/edit-simpan" method="post">
  <div class="modal-content">
    <!-- BEGIN: Modal Header -->
    <div class="modal-header">
      <h2 class="font-medium text-base mr-auto">Edit Guru</h2>
    </div> <!-- END: Modal Header -->
    <!-- BEGIN: Modal Body -->
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
      <input type="text" name="id" value="<?= $data['id'] ?>" hidden>

      <div class="col-span-12 sm:col-span-6"> <label for="nama_guru" class="form-label">Nama Guru</label>
        <input id="nama_guru" name="nama_guru" type="text" class="form-control" placeholder="Masukkan Nama Guru"
          required value="<?= $data['nama'] ?>">
      </div>

      <div class="col-span-12 sm:col-span-6"> <label for="tanggallahir" class="form-label">Tanggal Lahir</label> <input
          id="tanggallahir" name="tanggallahir" type="date" class="form-control" placeholder="Masukkan Tanggal Lahir"
          required value="<?= $data['tanggallahir'] ?>"></div>

      <div class="col-span-12 sm:col-span-6"> <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="text" class="form-control" placeholder="Masukkan Email"
          required value="<?= $data['email'] ?>" disabled>
          <input id="email" name="email" type="text" class="form-control" placeholder="Masukkan Email"
          required value="<?= $data['email'] ?>" hidden>
      </div>

      <div class="col-span-12 sm:col-span-6"> <label for="no_hp" class="form-label">No HandPhone</label> <input
          id="no_hp" name="no_hp" type="text" class="form-control" placeholder="Masukkan nomor HP yang aktif" required value="<?= $data['no_hp'] ?>">
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