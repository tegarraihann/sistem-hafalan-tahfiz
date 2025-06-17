<form action="prestasi_app/tambah-simpan" method="post">
  <div class="modal-content">
    <!-- BEGIN: Modal Header -->
    <div class="modal-header">
      <h2 class="font-medium text-base mr-auto">Tambah Prestasi Santri</h2>
    </div> 
    <!-- END: Modal Header -->

    <!-- BEGIN: Modal Body -->
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
      
      <div class="col-span-12 sm:col-span-12"> 
        <label for="id_santri" class="form-label text-primary font-medium">Pilih Nama Santri</label>
        <select id="id_santri" name="id_santri" data-search="true" class="form-control tom-select w-full">
          <?php
          $query = "SELECT * FROM tb_santri";
          $hasil = mysqli_query($koneksi, $query);
          while ($row = mysqli_fetch_assoc($hasil)) {
              echo "<option value='".$row['id']."'>".$row['nis']." - ".$row['nama']."</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-span-12 sm:col-span-6">
        <label for="total_juz" class="form-label">Total Hafalan (Juz)</label>
        <input id="total_juz" name="total_juz" type="number" min="1" max="30" value="" class="form-control" placeholder="isi prestasi total juz" required>
      </div>


      <div class="col-span-12 sm:col-span-6"> 
        <label for="tanggal" class="form-label">Tanggal</label>
        <input id="tanggal" name="tanggal" type="date" class="form-control" required>
      </div>

      <div class="col-span-12 sm:col-span-12"> 
        <label for="email_ortu" class="form-label">Email Orang Tua</label>
        <input id="email_ortu" name="email_ortu" type="email" class="form-control" placeholder="contoh: ortu@gmail.com" required>
      </div>

    </div> 
    <!-- END: Modal Body -->

    <!-- BEGIN: Modal Footer -->
    <div class="modal-footer">
      <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
      <input class="btn btn-primary w-20" type="submit" value="Simpan">
    </div> 
    <!-- END: Modal Footer -->

  </div> 
</form>
