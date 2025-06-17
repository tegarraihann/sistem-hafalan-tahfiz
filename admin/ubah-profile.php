<?php
$title = 'Update Profile';
include 'layouts/header.php';
include 'layouts/navbar.php';

$id = $_SESSION['id'];
$query = $koneksi->query("SELECT * FROM tb_pengguna WHERE id = '$id' ");
?>

<div class="content content--top-nav">
  <div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Update Profile</h2>
  </div>
  <div class="grid grid-cols-12 gap-6">
    <?php include './layouts/menu_setting.php'; ?>
    <div class="col-span-12 lg:col-span-8 2xl:col-span-9">

      <?php
      if (isset($_SESSION['pesana']) && $_SESSION['pesana'] != '') {
          echo '<div id="successMessage" class="px-5 py-3 rounded-md text-white bg-[#0a0a7c] mb-3 w-full">'
               . $_SESSION['pesana'] . 
               '</div>';
          $_SESSION['pesana'] = '';
          unset($_SESSION['pesana']);
      }
      ?>

      <script>
        // Hilangkan pesan sukses setelah 3 detik
        setTimeout(function () {
          var message = document.getElementById('successMessage');
          if (message) {
            message.style.display = 'none';
          }
        }, 3000);
      </script>

      <div class="intro-y box lg:mt-5">
        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
          <h2 class="font-medium text-base mr-auto">Informasi Profile</h2>
        </div>
        <div class="p-5">
          <?php foreach ($query as $data) : ?>
          <form class="form-horizontal" action="./setting/change-profile.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>" class="form-control">

            <div class="flex flex-col-reverse xl:flex-row flex-col">
              <div class="flex-1 mt-6 xl:mt-0">
                <div class="grid grid-cols-12 gap-x-5">
                  <div class="col-span-12 2xl:col-span-6">
                    <div>
                      <label class="form-label">Username</label>
                      <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>">
                    </div>
                    <div class="mt-3">
                      <label class="form-label">Level</label>
                      <input type="text" disabled class="form-control" value="<?= $data['level'] ?>">
                    </div>
                  </div>
                  <div class="col-span-12 2xl:col-span-6">
                    <div class="mt-3 2xl:mt-0">
                      <label class="form-label">Full Name</label>
                      <input type="text" name="full_name" class="form-control" value="<?= $data['nama'] ?>">
                    </div>
                    <div class="mt-3">
                      <label class="form-label">Foto Profile</label>
                      <input type="file" name="foto" class="form-control">
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary w-20 mt-3">Save</button>
              </div>
            </div>
          </form>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'layouts/footer.php'; ?>
