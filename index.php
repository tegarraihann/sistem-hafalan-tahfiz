<?php
ob_start();
include("config/koneksi.php");
session_start();

if (isset($_POST["submit"])) {
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));

    $username_escape = $koneksi->escape_string($username);
    $password_escape = $koneksi->escape_string($password);
    $password_sha1 = md5(sha1(md5($password_escape)));

    $query = "SELECT * FROM tb_pengguna WHERE username = '$username_escape' AND password = '$password_sha1'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $akun = $result->fetch_assoc();
        $level = $akun["level"];

        // Normalisasi level
        if ($level === 'Administrator') {
            $level = 'Admin';
        }

        if (in_array($level, ['Guru', 'Admin', 'Pimpinan'])) {
            $_SESSION["username"] = $akun["username"];
            $_SESSION["nama"] = $akun["nama"];
            $_SESSION["level"] = $level;
            $_SESSION["id"] = $akun['id'];
            $_SESSION["foto"] = $akun['foto'];

            // Redirect sesuai level
            if ($level === 'Guru') {
                echo "<script>document.location.href='user/index';</script>";
            } elseif ($level === 'Pimpinan') {
                echo "<script>document.location.href='pimpinan/index';</script>";
            } elseif ($level === 'Admin') {
                echo "<script>document.location.href='admin/index';</script>";
            }
        }
    } else {
        $_SESSION['pesan'] = '<div class="bg-red-500 text-white p-3 rounded mb-4 text-sm">
            Username atau Password tidak ditemukan.
        </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIHAT</title>
    <link rel="icon" href="assets/img/Favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        .bg-pattern {
            background-image: url('assets/img/pattern.svg');
            background-repeat: repeat;
            background-size: contain;
        }
    </style>
</head>
<body class="h-screen bg-gray-100">
    <div class="flex h-full">
        <div class="hidden lg:flex w-1/2 relative">
            <img src="assets/img/ppmit-bg.png" alt="Pesantren" class="object-cover w-full h-full">
            <div class="absolute inset-0 bg-gradient-to-tr from-green-900 to-amber-900 opacity-60"></div>
            <div class="absolute bottom-10 left-10 text-white">
                <h2 class="text-3xl font-bold drop-shadow-lg">Sistem Hafalan Tahfizh (SIHAT) Al-Qur'an</h2>
                <p class="mt-2">PPMIT - Pondok Pesantren Modern I'aanatuh Thalibiin</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-pattern bg-white bg-opacity-80 backdrop-blur-md">
            <div class="max-w-md w-full bg-white/60 shadow-xl rounded-xl p-8">
                <div class="text-center mb-6">
                    <img src="assets/img/Logo.png" alt="Logo" class="w-20 mx-auto mb-2">
                    <h1 class="text-2xl font-semibold text-gray-800">Login Sistem</h1>
                    <p class="text-sm text-gray-600">Masukkan data Anda</p>
                </div>

                <?php
                if (isset($_SESSION['pesan']) && $_SESSION['pesan'] !== '') {
                    echo $_SESSION['pesan'];
                    $_SESSION['pesan'] = '';
                }
                ?>

                <form action="" method="post" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm mb-1">Username</label>
                        <input type="text" name="username" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500"
                               placeholder="Masukkan username">
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm mb-1">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 pr-10"
                                   placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-3 flex items-center text-gray-600">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="submit"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition duration-300">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-amber-700 italic">
                    <p>إِنَّمَا الْأَعْمَالُ بِالنِّيَّاتِ</p>
                    <p class="text-gray-500 text-xs mt-1">Sesungguhnya segala amal tergantung niatnya</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (input.type === "password") {
                input.type = "text";
                eyeIcon.innerHTML = `
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.407-4.033m2.133-1.57A9.963 9.963 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.957 9.957 0 01-4.092 5.323M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18"/>`;
            } else {
                input.type = "password";
                eyeIcon.innerHTML = `
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }
        }
    </script>
</body>
</html>
