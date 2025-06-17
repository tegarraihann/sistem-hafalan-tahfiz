<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="SIHAT Logo" class="w-6" src="../assets/dist/images/logo.svg">
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
                    <div class="menu__icon"><i data-lucide="home"></i></div>
                    <div class="menu__title">Dashboard</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu">
                    <div class="menu__icon"><i data-lucide="box"></i></div>
                    <div class="menu__title">Master Data<i data-lucide="chevron-down" class="menu__sub-icon"></i></div>
                </a>
                <ul>
                    <li>
                        <a href="./kelas" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Data Kelas</div>
                        </a>
                    </li>
                    <li>
                        <a href="./guru" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Data Guru</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;" class="menu">
                    <div class="menu__icon"><i data-lucide="layout"></i></div>
                    <div class="menu__title">Hafalan<i data-lucide="chevron-down" class="menu__sub-icon"></i></div>
                </a>
                <ul>
                    <li>
                        <a href="./hafalan-baru" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Tambah Hafalan</div>
                        </a>
                    </li>
                    <li>
                        <a href="./daftar-hafalan" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Daftar Hafalan</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="prestasi" class="menu">
                    <div class="menu__icon"><i data-lucide="sidebar"></i></div>
                    <div class="menu__title">Prestasi Hafalan</div>
                </a>
            </li>
            <li>
                <a href="javascript:;" class="menu">
                    <div class="menu__icon"><i data-lucide="inbox"></i></div>
                    <div class="menu__title">Laporan<i data-lucide="chevron-down" class="menu__sub-icon"></i></div>
                </a>
                <ul>
                    <li>
                        <a href="laporan-hafalan" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Laporan Hafalan</div>
                        </a>
                    </li>
                    <li>
                        <a href="laporan-rekap" class="menu">
                            <div class="menu__icon"><i data-lucide="activity"></i></div>
                            <div class="menu__title">Laporan Rekap</div>
                        </a>
                    </li>
                </ul>
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
            <img class="w-6" src="../assets/dist/images/logo.svg">
            <span class="text-white text-lg ml-3">SIHAT Al-Quran</span>
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
                <?php
                $foto = isset($_SESSION['foto']) && $_SESSION['foto'] != '' ? $_SESSION['foto'] : 'anonim.png';
                ?>
                <img alt="Foto Profil" src="../assets/img/<?= htmlspecialchars($foto) ?>" class="rounded-full">
            </div>
            <div class="dropdown-menu w-56">
                <ul class="dropdown-content bg-primary/70 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
                    <li class="p-2">
                        <div class="font-medium"><?= htmlspecialchars($_SESSION["nama"] ?? 'User') ?></div>
                        <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500"><?= htmlspecialchars($_SESSION["level"] ?? '') ?></div>
                    </li>
                    <li><hr class="dropdown-divider border-white/[0.08]"></li>
                    <li>
                        <a href="ubah-profile" class="dropdown-item hover:bg-white/5">
                            <i data-lucide="user" class="w-4 h-4 mr-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="ubah-password" class="dropdown-item hover:bg-white/5">
                            <i data-lucide="lock" class="w-4 h-4 mr-2"></i> Ubah Password
                        </a>
                    </li>
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
            <a href="index" class="top-menu <?= ($title == 'Dashboard') ? 'top-menu--active' : '' ?>">
                <div class="top-menu__icon"><i data-lucide="home"></i></div>
                <div class="top-menu__title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="top-menu <?= in_array($title, ['Data Kelas', 'Data Guru']) ? 'top-menu--active' : '' ?>">
                <div class="top-menu__icon"><i data-lucide="box"></i></div>
                <div class="top-menu__title">Master Data<i data-lucide="chevron-down" class="top-menu__sub-icon"></i></div>
            </a>
            <ul>
                <li>
                    <a href="./kelas" class="top-menu <?= ($title == 'Data Kelas') ? 'top-menu--active' : '' ?>">
                        <div class="top-menu__icon"><i data-lucide="activity"></i></div>
                        <div class="top-menu__title">Data Kelas</div>
                    </a>
                </li>
                <li>
                    <a href="./guru" class="top-menu <?= ($title == 'Data Guru') ? 'top-menu--active' : '' ?>">
                        <div class="top-menu__icon"><i data-lucide="activity"></i></div>
                        <div class="top-menu__title">Data Guru</div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="top-menu <?= in_array($title, ['Tambah Hafalan', 'Daftar Hafalan']) ? 'top-menu--active' : '' ?>">
                <div class="top-menu__icon"><i data-lucide="layout"></i></div>
                <div class="top-menu__title">Hafalan<i data-lucide="chevron-down" class="top-menu__sub-icon"></i></div>
            </a>
            <ul>
                <li>
                    <a href="./hafalan-baru" class="top-menu <?= ($title == 'Tambah Hafalan') ? 'top-menu--active' : '' ?>">
                        <div class="top-menu__icon"><i data-lucide="activity"></i></div>
                        <div class="top-menu__title">Tambah Hafalan</div>
                    </a>
                </li>
                <li>
                    <a href="./daftar-hafalan" class="top-menu <?= ($title == 'Daftar Hafalan') ? 'top-menu--active' : '' ?>">
                        <div class="top-menu__icon"><i data-lucide="activity"></i></div>
                        <div class="top-menu__title">Daftar Hafalan</div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="./prestasi" class="top-menu <?= ($title == 'Sertifikat') ? 'top-menu--active' : '' ?>">
                <div class="top-menu__icon"><i data-lucide="sidebar"></i></div>
                <div class="top-menu__title">Sertifikat</div>
            </a>
        </li>
        <li>
            <a href="laporan-hafalan" class="top-menu <?= ($title == 'Laporan Hafalan') ? 'top-menu--active' : '' ?>">
                <div class="top-menu__icon"><i data-lucide="inbox"></i></div>
                <div class="top-menu__title">Laporan Hafalan</div>
            </a>
        </li>
    </ul>
</nav>
<!-- END: Top Menu -->