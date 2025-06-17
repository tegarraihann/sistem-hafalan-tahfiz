<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Midone - HTML Admin Template" class="w-6" src="../assets/dist/images/logo.svg">
        </a>
        <a href="javascript:;" class="mobile-menu-toggler">
            <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
        </a>
    </div>
    <div class="scrollable">
        <a href="javascript:;" class="mobile-menu-toggler">
            <i data-lucide="x-circle" class="w-8 h-8 text-white transform -rotate-90"></i>
        </a>
        <ul class="scrollable__content py-2">
            <li>
                <a href="index" class="menu">
                    <div class="menu__icon"> <i data-lucide="home"></i> </div>
                    <div class="menu__title"> Dashboard </div>
                </a>
            </li>
            <li>
                <a href="prestasi" class="menu">
                    <div class="menu__icon"> <i data-lucide="sidebar"></i> </div>
                    <div class="menu__title"> Prestasi Hafalan</div>
                </a>
            </li>
            <li>
                <a href="laporan-hafalan" class="menu">
                    <div class="menu__icon"> <i data-lucide="activity"></i> </div>
                    <div class="menu__title"> Laporan Hafalan </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<!-- END: Mobile Menu -->

<!-- BEGIN: Top Bar -->
<div class="top-bar-boxed border-b border-white/[0.08] mt-12 md:mt-0 -mx-3 sm:-mx-8 md:mx-0 px-3 sm:px-8 md:px-6 mb-10 md:mb-8">
    <div class="h-full flex items-center">
        <!-- BEGIN: Logo -->
        <a href="" class="-intro-x hidden md:flex">
            <img alt="Midone - HTML Admin Template" class="w-6" src="../assets/dist/images/logo.svg">
            <span class="text-white text-lg ml-3"> SIHAT Al-Qur'an </span>
        </a>
        <!-- END: Logo -->

        <!-- BEGIN: Breadcrumb -->
        <nav aria-label="breadcrumb" class="-intro-x h-full mr-auto">
            <ol class="breadcrumb breadcrumb-light">
                <li class="breadcrumb-item"><a href="#">Application</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </nav>
        <!-- END: Breadcrumb -->

        <!-- BEGIN: Account Menu -->
        <div class="intro-x dropdown w-8 h-8">
            <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110"
                role="button" aria-expanded="false" data-tw-toggle="dropdown">
                <img alt="User" src="../assets/img/<?= $_SESSION['foto'] ?? 'anonim.png' ?>">
            </div>
            <div class="dropdown-menu w-56">
                <ul class="dropdown-content bg-primary/70 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                    <li class="p-2">
                        <?php
                        include("../config/koneksi.php");
                        $nip = isset($_SESSION['username']) ? $koneksi->real_escape_string($_SESSION['username']) : null;
                        $konf = null;

                        if ($nip !== null) {
                            $query = $koneksi->query("SELECT * FROM tb_pengguna WHERE username = '$nip'");
                            if ($query && $query->num_rows > 0) {
                                $konf = $query->fetch_assoc();
                            }
                        }
                        ?>
                        <div class="font-medium">
                            <?= isset($konf["nama"]) ? htmlspecialchars($konf["nama"]) : 'Nama tidak ditemukan' ?>
                        </div>
                        <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">
                            <?= $_SESSION["level"] ?? 'Level tidak ditemukan' ?>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider border-white/[0.08]"></li>
                    <li>
                        <a href="ubah-profile" class="dropdown-item hover:bg-white/5">
                            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile
                        </a>
                    <li><hr class="dropdown-divider border-white/[0.08]"></li>
                    <li>
                        <a href="../logout" class="dropdown-item hover:bg-white/5">
                            <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END: Account Menu -->
    </div>
</div>
<!-- END: Top Bar -->

<!-- BEGIN: Top Menu -->
<nav class="top-nav">
    <ul>
        <li>
            <a href="index" class="top-menu <?php if($title == 'Dashboard') echo 'top-menu--active' ?>">
                <div class="top-menu__icon"> <i data-lucide="home"></i> </div>
                <div class="top-menu__title"> Dashboard</div>
            </a>
        </li>
        <li>
            <a href="./hafalan-baru" class="top-menu <?php if($title == 'Hafalan Baru') echo 'top-menu--active' ?>">
                <div class="top-menu__icon"> <i data-lucide="layout"></i> </div>
                <div class="top-menu__title"> Hafalan Baru</div>
            </a>
        </li>
        <li>
            <a href="./prestasi" class="top-menu <?php if($title == 'Prestasi') echo 'top-menu--active' ?>">
                <div class="top-menu__icon"> <i data-lucide="sidebar"></i> </div>
                <div class="top-menu__title"> Prestasi</div>
            </a>
        </li>
        <li>
            <a href="./laporan-hafalan" class="top-menu <?php if($title == 'Laporan Hafalan') echo 'top-menu--active' ?>">
                <div class="top-menu__icon"> <i data-lucide="inbox"></i> </div>
                <div class="top-menu__title"> Laporan Hafalan</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Top Menu -->
