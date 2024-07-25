<?php
include '../config.php';

// Memulai sesi
session_start();

// Inisialisasi variabel untuk menyimpan data formulir dan pesan kesalahan
$email = $password = "";
$email_err = $password_err = "";

// Memproses data formulir saat formulir dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Harap masukkan email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Format email tidak valid.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validasi kata sandi
    if (empty(trim($_POST["password"]))) {
        $password_err = "Harap masukkan kata sandi.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validasi kredensial
    if (empty($email_err) && empty($password_err)) {
        // Siapkan pernyataan select
        $sql = "SELECT id, username, password, role FROM users WHERE email = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variabel ke pernyataan yang disiapkan sebagai parameter
            $stmt->bind_param("s", $param_email);

            // Set parameter
            $param_email = $email;

            // Coba untuk mengeksekusi pernyataan yang disiapkan
            if ($stmt->execute()) {
                // Simpan hasil
                $stmt->store_result();

                // Periksa apakah email ada, jika ya maka verifikasi kata sandi
                if ($stmt->num_rows == 1) {
                    // Bind variabel hasil
                    $stmt->bind_result($id, $username, $hashed_password, $role);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Kata sandi benar, jadi mulai sesi baru
                            session_start();

                            // Simpan data dalam variabel sesi
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["role"] = $role;

                            // Arahkan pengguna ke halaman yang sesuai berdasarkan peran
                            if ($role == 1) {
                                header("location: ../admin/home_admin.php");
                            } else {
                                header("location: home.php");
                            }
                            exit;
                        } else {
                            // Tampilkan pesan kesalahan jika kata sandi tidak valid
                            $password_err = "Kata sandi yang Anda masukkan salah.";
                        }
                    }
                } else {
                    // Tampilkan pesan kesalahan jika email tidak ada
                    $email_err = "Tidak ada akun yang ditemukan dengan email ini.";
                }
            } else {
                echo "Ups! Terjadi kesalahan. Silakan coba lagi nanti.";
            }

            // Tutup pernyataan
            $stmt->close();
        }
    }

    // Tutup koneksi
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="/public/css/output.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .background-animation {
            background: linear-gradient(-45deg, #6a11cb, #2575fc, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            position: relative;
            overflow: hidden;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .input:hover, .input:focus {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .input {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .floating-circles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            overflow: hidden;
        }
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 10s infinite;
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>
<body class="background-animation font-sans leading-normal tracking-normal">
    <div class="floating-circles">
        <div class="circle w-20 h-20" style="top: 10%; left: 20%;"></div>
        <div class="circle w-24 h-24" style="top: 30%; left: 70%;"></div>
        <div class="circle w-16 h-16" style="top: 60%; left: 40%;"></div>
        <div class="circle w-32 h-32" style="top: 80%; left: 10%;"></div>
        <div class="circle w-28 h-28" style="top: 50%; left: 80%;"></div>
    </div>

    <div class="flex items-center justify-center min-h-screen relative">
        <div class="bg-white bg-opacity-10 glass-effect p-10 rounded-lg shadow-2xl w-full max-w-md">
            <h2 class="text-4xl font-bold text-center text-white mb-6">Selamat Datang Kembali!</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Masukkan email Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <span class="text-red-500 text-sm"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-6 relative">
                    <label for="password" class="block text-white text-sm font-bold mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                        <i class="fas fa-eye text-gray-500 cursor-pointer hover:text-purple-700" onclick="togglePassword()"></i>
                    </div>
                    <span class="text-red-500 text-sm"><?php echo $password_err; ?></span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <button type="submit" class="btn w-full bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform">Login</button>
                </div>
                <div class="text-center">
                    <a href="#" class="inline-block align-baseline font-bold text-sm text-white hover:text-blue-800 transition duration-300 ease-in-out transform hover:scale-105">Lupa Kata Sandi?</a>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-white">Tidak punya akun? <a href="register.php" class="text-blue-500 hover:text-blue-800 transition duration-300 ease-in-out transform hover:scale-105">Daftar</a></p>
            </div>
        </div>
    </div>

    <script src="/public/js/script.js"></script>
    <script>
        function togglePassword() {
            var password = document.getElementById("password");
            var eyeIcon = document.querySelector(".fa-eye");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
            }
        }
    </script>
</body>
</html>
