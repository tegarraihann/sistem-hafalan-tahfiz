<form action="prestasi_app/tambah-simpan" method="post" id="formTambahPrestasi">
  <div class="modal-content">
    <!-- BEGIN: Modal Header -->
    <div class="modal-header">
      <h2 class="font-medium text-base mr-auto">Tambah Sertifikat Santri</h2>
    </div> 
    <!-- END: Modal Header -->

    <!-- BEGIN: Modal Body -->
    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
      
      <div class="col-span-12 sm:col-span-12"> 
        <label for="id_santri" class="form-label text-primary font-medium">Pilih Nama Santri</label>
        <select id="id_santri" name="id_santri" data-search="true" class="form-control tom-select w-full" required>
          <option value="">-- Pilih Santri --</option>
          <?php
          $query = "SELECT * FROM tb_santri ORDER BY nama ASC";
          $hasil = mysqli_query($koneksi, $query);
          while ($row = mysqli_fetch_assoc($hasil)) {
              echo "<option value='".$row['id']."'>".$row['nis']." - ".$row['nama']."</option>";
          }
          ?>
        </select>
        <div class="form-help mt-1">Pilih nama santri yang akan diberikan sertifikat</div>
      </div>

      <div class="col-span-12 sm:col-span-6">
        <label for="total_juz" class="form-label">Total Juz</label>
        <input id="total_juz" name="total_juz" type="number" min="1" max="30" value="" class="form-control" placeholder="Contoh: 5" required>
        <div class="form-help mt-1">Masukkan jumlah juz yang telah dihafal (1-30)</div>
      </div>

      <div class="col-span-12 sm:col-span-6"> 
        <label for="tanggal" class="form-label">Tanggal</label>
        <input id="tanggal" name="tanggal" type="date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        <div class="form-help mt-1">Tanggal pencapaian prestasi</div>
      </div>

      <div class="col-span-12 sm:col-span-12"> 
        <label for="whatsapp_ortu" class="form-label">WhatsApp Orang Tua</label>
        <input id="whatsapp_ortu" name="whatsapp_ortu" type="tel" class="form-control" placeholder="Contoh: 081234567890 atau 6281234567890" pattern="^(0[8-9][0-9]{8,11}|62[8-9][0-9]{8,11})$" required>
        <div class="form-help mt-1">
          Format: 08xxxxxxxxxx atau 628xxxxxxxxxx<br>
          <small class="text-slate-500">Nomor ini akan digunakan untuk mengirim notifikasi WhatsApp</small>
        </div>
      </div>

    </div> 
    <!-- END: Modal Body -->

    <!-- BEGIN: Modal Footer -->
    <div class="modal-footer">
      <button type="button" data-tw-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">Cancel</button>
      <button type="submit" class="btn btn-primary w-20" id="btnSimpan">
        <span class="btn-text">Simpan</span>
        <span class="btn-loading hidden">
          <i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Proses...
        </span>
      </button>
    </div> 
    <!-- END: Modal Footer -->

  </div> 
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formTambahPrestasi');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnText = btnSimpan.querySelector('.btn-text');
    const btnLoading = btnSimpan.querySelector('.btn-loading');
    
    // Validasi form sebelum submit
    form.addEventListener('submit', function(e) {
        const whatsapp = document.getElementById('whatsapp_ortu').value.trim();
        
        // Validasi nomor WhatsApp
        const whatsappPattern = /^(0[8-9][0-9]{8,11}|62[8-9][0-9]{8,11})$/;
        if (!whatsappPattern.test(whatsapp)) {
            e.preventDefault();
            alert('Format nomor WhatsApp tidak valid!\nGunakan format: 08xxxxxxxxxx atau 628xxxxxxxxxx');
            return false;
        }
        
        // Tampilkan loading
        btnSimpan.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        
        // Auto enable kembali setelah 10 detik (failsafe)
        setTimeout(function() {
            btnSimpan.disabled = false;
            btnText.classList.remove('hidden');
            btnLoading.classList.add('hidden');
        }, 10000);
    });
    
    // Format nomor WhatsApp otomatis
    document.getElementById('whatsapp_ortu').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        
        // Auto format ke 62 jika dimulai dengan 0
        if (value.startsWith('0')) {
            value = '62' + value.substring(1);
        }
        
        e.target.value = value;
    });
});
</script>