<?php
$title = 'Input Data Hafalan Baru';

include 'layouts/header.php';
include 'layouts/navbar.php';
?>

<!-- BEGIN: Content -->
<div class="content content--top-nav">
  <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
    <h2 class="text-lg font-medium mr-auto"><?= $title ?> </h2>
  </div>

  <!-- Notifikasi -->
  <?php if (!empty($status)): ?>
    <div class="intro-y box col-span-12 mt-5">
      <div class="p-5">
        <?php if ($status === 'sukses'): ?>
          <div class="alert alert-success show mb-2" role="alert">
            ✅ Hafalan berhasil ditambahkan.
          </div>
        <?php else: ?>
          <div class="alert alert-danger show mb-2" role="alert">
            ❌ Gagal menambahkan hafalan.
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

  <div class="grid grid-cols-12 gap-6">
    <!-- BEGIN: Personal Information -->
    <div class="intro-y box col-span-12 mt-5">

      <div class="p-5">
        <form action="hafalanbaru_app/tambah.php" method="post">
          <input type="hidden" name="id" value="<?= $id ?? '' ?>">

          <div class="grid grid-cols-12 gap-5">
            <!-- Kolom Kiri -->
            <div class="col-span-12 xl:col-span-6">
            <div class="mb-3">
  <label class="form-label">NIS</label>
  <input type="text" class="form-control" id="nis" name="nis" value="<?= $nis ?? '' ?>" required>
</div>

<div class="mb-3">
  <label class="form-label">Nama</label>
  <input type="text" class="form-control bg-slate-100" id="nama" name="nama" readonly>
</div>

<div class="mb-3">
  <label class="form-label">Kelas</label>
  <input type="text" class="form-control bg-slate-100" id="kelas" name="kelas" readonly>
</div>

              <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input id="tanggal" name="tanggal" type="date" class="form-control" required>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-span-12 xl:col-span-6">
              <div class="mb-3">
                <label for="juz" class="form-label">Juz Dalam Al-Quran</label>
                <select id="juz" name="juz" class="form-select" required>
                  <?php for ($i = 1; $i <= 30; $i++): ?>
                    <option value="Juz <?= $i ?>">Juz <?= $i ?></option>
                  <?php endfor; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="surat" class="form-label">Surat</label>
                <select id="surat" name="surat" class="form-select" required>
                    <option value="Al-Fatihah">1. Al-Fatihah (Pembuka)</option>
                    <option value="Al-Baqarah">2. Al-Baqarah (Sapi Betina)</option>
                    <option value="Ali-Imran">3. Ali-Imran (Keluarga Imran)</option>
                    <option value="An-Nisa'">4. An-Nisa' (Wanita)</option>
                    <option value="Al-Ma'idah">5. Al-Ma'idah (Meja)</option>
                    <option value="Al-An'am">6. Al-An'am (Binatang Ternak)</option>
                    <option value="Al-A'raf">7. Al-A'raf (Puncak-Puncak)</option>
                    <option value="Al-Anfal">8. Al-Anfal (Harta Rampasan Perang)</option>
                    <option value="At-Tawbah">9. At-Tawbah (Pengampunan)</option>
                    <option value="Yunus">10. Yunus (Nabi Yunus)</option>
                    <option value="Hud">11. Hud (Nabi Hud)</option>
                    <option value="Yusuf">12. Yusuf (Nabi Yusuf)</option>
                    <option value="Ar-Ra'd">13. Ar-Ra'd (Guruh)</option>
                    <option value="Ibrahim">14. Ibrahim (Nabi Ibrahim)</option>
                    <option value="Al-Hijr">15. Al-Hijr (Bukit-Hijr)</option>
                    <option value="An-Nahl">16. An-Nahl (Lebah)</option>
                    <option value="Al-Isra'">17. Al-Isra' (Malam Isra')</option>
                    <option value="Al-Kahf">18. Al-Kahf (Gua)</option>
                    <option value="Maryam">19. Maryam (Maryam)</option>
                    <option value="Ta-Ha">20. Ta-Ha (Ta-Ha)</option>
                    <option value="Al-Anbiya'">21. Al-Anbiya' (Para Nabi)</option>
                    <option value="Al-Hajj">22. Al-Hajj (Haji)</option>
                    <option value="Al-Mu'minun">23. Al-Mu'minun (Orang-Orang Mukmin)</option>
                    <option value="An-Nur">24. An-Nur (Cahaya)</option>
                    <option value="Al-Furqan">25. Al-Furqan (Pembeda)</option>
                    <option value="Asy-Syu'ara'">26. Asy-Syu'ara' (Penyair)</option>
                    <option value="An-Naml">27. An-Naml (Semut)</option>
                    <option value="Al-Qasas">28. Al-Qasas (Cerita)</option>
                    <option value="Al-Ankabut">29. Al-Ankabut (Labah-Labah)</option>
                    <option value="Ar-Rum">30. Ar-Rum (Romawi)</option>
                    <option value="Luqman">31. Luqman (Luqman)</option>
                    <option value="As-Sajda">32. As-Sajda (Sujud)</option>
                    <option value="Al-Ahzab">33. Al-Ahzab (Kumpulan-Kumpulan)</option>
                    <option value="Saba'">34. Saba' (Saba')</option>
                    <option value="Fatir">35. Fatir (Pencipta)</option>
                    <option value="Ya-Sin">36. Ya-Sin (Ya-Sin)</option>
                    <option value="As-Saffat">37. As-Saffat (Orang-Orang Yang Bersaf-Saf)</option>
                    <option value="Sad">38. Sad (Shaad)</option>
                    <option value="Az-Zumar">39. Az-Zumar (Orang-Orang Yang Berkumpul)</option>
                    <option value="Ghafir">40. Ghafir (Maha Pengampun)</option>
                    <option value="Fussilat">41. Fussilat (Yang Dijelaskan)</option>
                    <option value="Ash-Shura">42. Ash-Shura (Musyawarah)</option>
                    <option value="Az-Zukhruf">43. Az-Zukhruf (Perhiasan)</option>
                    <option value="Ad-Dukhan">44. Ad-Dukhan (Kabut)</option>
                    <option value="Al-Jathiya">45. Al-Jathiya (Berlutut)</option>
                    <option value="Al-Ahqaf">46. Al-Ahqaf (Bukit Pasir)</option>
                    <option value="Muhammad">47. Muhammad (Nabi Muhammad)</option>
                    <option value="Al-Fath">48. Al-Fath (Kemenangan)</option>
                    <option value="Al-Hujuraat">49. Al-Hujuraat (Kamar-Kamar)</option>
                    <option value="Qaaf">50. Qaaf (Qaaf)</option>
                    <option value="Adh-Dhariyat">51. Adh-Dhariyat (Angin Yang Menerbangkan)</option>
                    <option value="At-Tur">52. At-Tur (Bukit Tur)</option>
                    <option value="An-Najm">53. An-Najm (Bintang)</option>
                    <option value="Al-Qamar">54. Al-Qamar (Bulan)</option>
                    <option value="Ar-Rahman">55. Ar-Rahman (Maha Pengasih)</option>
                    <option value="Al-Waqi'ah">56. Al-Waqi'ah (Hari Kiamat)</option>
                    <option value="Al-Hadid">57. Al-Hadid (Besi)</option>
                    <option value="Al-Mujadila">58. Al-Mujadila (Wanita Yang Mengadu)</option>
                    <option value="Al-Hashr">59. Al-Hashr (Pengusiran)</option>
                    <option value="Al-Mumtahanah">60. Al-Mumtahanah (Yang Diuji)</option>
                    <option value="Ash-shaf">61. Ash-shaf (Barisan)</option>
                    <option value="Al-Jumu'ah">62. Al-Jumu'ah (Jum'ah)</option>
                    <option value="Al-Munafiqun">63. Al-Munafiqun (Orang-Orang Munafik)</option>
                    <option value="At-Taghabun">64. At-Taghabun (Tuntutan Membuktikan)</option>
                    <option value="At-Talaq">65. At-Talaq (Perceraian)</option>
                    <option value="At-Tahrim">66. At-Tahrim (Mengharamkan)</option>
                    <option value="Al-Mulk">67. Al-Mulk (Kerajaan)</option>
                    <option value="Al-Qalam">68. Al-Qalam (Pena)</option>
                    <option value="Al-Haaqqa">69. Al-Haaqqa (Kenyataan Yang Tak Terhindarkan)</option>
                    <option value="Al-Ma'arij">70. Al-Ma'arij (Tempat-Tempat Naik)</option>
                    <option value="Nuh">71. Nuh (Nabi Nuh)</option>
                    <option value="Al-Jinn">72. Al-Jinn (Jin)</option>
                    <option value="Al-Muzzammil">73. Al-Muzzammil (Orang Yang Berselimut)</option>
                    <option value="Al-Muddaththir">74. Al-Muddaththir (Orang Yang Berkemul)</option>
                    <option value="Al-Qiyama">75. Al-Qiyama (Hari Kebangkitan)</option>
                    <option value="Al-Insan">76. Al-Insan (Manusia)</option>
                    <option value="Al-Mursalat">77. Al-Mursalat (Malaikat-Malaikat Yang Diutus)</option>
                    <option value="An-Naba'">78. An-Naba' (Berita Besar)</option>
                    <option value="An-Nazi'at">79. An-Nazi'at (Malaikat-Malaikat Yang Mencabut)</option>
                    <option value="Abasa">80. Abasa (Ia Bermuka Masam)</option>
                    <option value="At-Takwir">81. At-Takwir (Bintang-Bintang Yang Jatuh)</option>
                    <option value="Al-Infitar">82. Al-Infitar (Terbelah)</option>
                    <option value="Al-Mutaffifin">83. Al-Mutaffifin (Orang-Orang Yang Curang)</option>
                    <option value="Al-Inshiqaq">84. Al-Inshiqaq (Terbelah)</option>
                    <option value="Al-Buruj">85. Al-Buruj (Bintang-Bintang Yang Bersinar)</option>
                    <option value="At-Tariq">86. At-Tariq (Yang Datang Di Malam Hari)</option>
                    <option value="Al-A'la">87. Al-A'la (Yang Paling Tinggi)</option>
                    <option value="Al-Ghashiyah">88. Al-Ghashiyah (Hari Pembalasan)</option>
                    <option value="Al-Fajr">89. Al-Fajr (Fajar)</option>
                    <option value="Al-Balad">90. Al-Balad (Negeri)</option>
                    <option value="Ash-Shams">91. Ash-Shams (Matahari)</option>
                    <option value="Al-Layl">92. Al-Layl (Malam)</option>
                    <option value="Adh-Dhuha">93. Adh-Dhuha (Duha)</option>
                    <option value="Ash-Sharh">94. Ash-Sharh (Melepaskan)</option>
                    <option value="At-Tin">95. At-Tin (Buah Tin)</option>
                    <option value="Al-Alaq">96. Al-Alaq (Segumpal Darah)</option>
                    <option value="Al-Qadr">97. Al-Qadr (Kemuliaan)</option>
                    <option value="Al-Bayyina">98. Al-Bayyina (Bukti Yang Nyata)</option>
                    <option value="Az-Zalzalah">99. Az-Zalzalah (Kegoncangan)</option>
                    <option value="Al-Adiyat">100. Al-Adiyat (Kuda Perang Yang Berlari Kencang)</option>
                    <option value="Al-Qari'ah">101. Al-Qari'ah (Hari Pembalasan)</option>
                    <option value="At-Takathur">102. At-Takathur (Bermegah-Megah)</option>
                    <option value="Al-Asr">103. Al-Asr (Masa)</option>
                    <option value="Al-Humazah">104. Al-Humazah (Pengumpat)</option>
                    <option value="Al-Fil">105. Al-Fil (Gajah)</option>
                    <option value="Quraish">106. Quraish (Suku Quraish)</option>
                    <option value="Al-Ma'un">107. Al-Ma'un (Barang Yang Berguna)</option>
                    <option value="Al-Kawthar">108. Al-Kawthar (Nikmat Yang Berlimpah)</option>
                    <option value="Al-Kafirun">109. Al-Kafirun (Orang-Orang Kafir)</option>
                    <option value="An-Nasr">110. An-Nasr (Pertolongan)</option>
                    <option value="Al-Masad">111. Al-Masad (Bambu Api)</option>
                    <option value="Al-Ikhlas">112. Al-Ikhlas (Kesucian)</option>
                    <option value="Al-Falaq">113. Al-Falaq (Waktu Subuh)</option>
                    <option value="An-Nas">114. An-Nas (Manusia)</option>
                  </select>
                </div>

                <div class="mb-3">
                <label for="ayat" class="form-label">Ayat dalam Al-quran</label>
                <input type="text" id="ayat" name="ayat" class="form-control" placeholder="Masukkan Ayat" required>
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                  <option value="Tidak Mengulang">Tidak Mengulang</option>
                  <option value="Mengulang">Mengulang</option>
                </select>
              </div>
            </div>
            <div class="flex justify-end mt-4">
              <button type="submit" class="btn btn-primary w-20 mr-auto">Tambah</button>
              <!-- <a href="" class="text-danger flex items-center"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete
              Account </a> -->
            </div>
          </form>
        </div>
      </div>
      <!-- END: Personal Information -->
    </div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const nisInput = document.getElementById("nis");
    const namaInput = document.getElementById("nama");
    const kelasInput = document.getElementById("kelas");

    // Setiap kali user mengetik NIS
    nisInput.addEventListener("input", function() {
      const nis = this.value.trim();
      if (nis === "") {
        // kosongkan jika NIS dihapus
        namaInput.value = "";
        kelasInput.value = "";
        return;
      }

      // Kirim request ke cari_santri.php
      fetch("hafalanbaru_app/cari_santri.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "idsantri=" + encodeURIComponent(nis)
      })
      .then(res => {
        if (!res.ok) throw new Error("HTTP error " + res.status);
        return res.json();
      })
      .then(data => {
        // jika data ada, isi; jika tidak, kosongkan
        namaInput.value  = data.nama  || "";
        kelasInput.value = data.kelas || "";
      })
      .catch(err => {
        console.error("Fetch error:", err);
        namaInput.value = "";
        kelasInput.value = "";
      });
    });
  });
  </script>

<?php
include 'layouts/footer.php';
?>