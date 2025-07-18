<!-- BEGIN: Profile Menu -->
<div class="col-span-12 lg:col-span-4 2xl:col-span-3 flex lg:block flex-col-reverse">
  <div class="intro-y box mt-5">
    <div class="relative flex items-center p-5">
      <div class="w-12 h-12 image-fit">

        <?php
        $foto = $_SESSION["foto"];
        if ($foto === ''){ ?>
        <img class="rounded-full" src="../assets/dist/images/profile-8.jpg">
        <?php }else{?>
        <img class="rounded-full" src="../assets/img/<?=$_SESSION["foto"] ?>">
        <?php } ?>
      </div>
      <div class="ml-4 mr-auto">
        <div class="font-medium text-base"><?=$_SESSION["nama"] ?></div>
        <div class="text-slate-500"><?=$_SESSION["level"] ?></div>
      </div>
    </div>
    <div class="p-5 border-t border-slate-200/60 dark:border-darkmode-400">
      <!-- <a class="flex items-center" href=""> <i data-lucide="activity" class="w-4 h-4 mr-2"></i> Personal Information
      </a> -->
      <a class="flex items-center  <?php if($title == 'Update Profile') echo ' text-primary font-medium' ?> mt-5"
        href="ubah-profile"> <i data-lucide="box" class="w-4 h-4 mr-2"></i> Account Settings
      </a>
    </div>
  </div>
</div>
<!-- END: Profile Menu -->