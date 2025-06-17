<form action="guru_app/tambah-simpan" method="post">
  <div class="modal-content">
    <!-- BEGIN: Modal Header -->
    <div class="modal-header">
      <h2 class="font-medium text-base mr-auto">Tambah Guru</h2>
    </div> <!-- END: Modal Header -->

    <!-- BEGIN: Modal Body -->
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
      <div class="col-span-12 sm:col-span-6">
        <label for="nama_guru" class="form-label">Nama Guru</label>
        <input id="nama_guru" name="nama_guru" type="text" class="form-control" placeholder="Masukkan Nama Guru" required>
      </div>

      <div class="col-span-12 sm:col-span-6">
        <label for="tanggallahir" class="form-label">Tanggal Lahir</label>
        <input id="tanggallahir" name="tanggallahir" type="date" class="form-control" placeholder="Masukkan Tanggal Lahir" required>
      </div>

      <div class="col-span-12 sm:col-span-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="text" class="form-control" placeholder="Masukkan Email" required>
      </div>

      <div class="col-span-12 sm:col-span-6">
        <label for="no_hp" class="form-label">No HandPhone</label>
        <input id="no_hp" name="no_hp" type="text" class="form-control" placeholder="Masukkan nomor HP yang aktif" required>
      </div>
    </div> <!-- END: Modal Body -->

    <!-- BEGIN: Modal Footer -->
    <div class="modal-footer">
      <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
      <input class="btn btn-primary w-20" type="submit" value="Simpan">
    </div> <!-- END: Modal Footer -->
  </div> <!-- END: Modal Content -->
</form>
