<?php
// Include the database configuration file
include '../config.php';

// Initialize variables to store form data and error messages
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Harap masukkan nama pengguna.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $username_err = "Nama pengguna ini sudah diambil.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Ups! Terjadi kesalahan. Silakan coba lagi nanti.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Harap masukkan email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Format email tidak valid.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Harap masukkan kata sandi.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Kata sandi harus memiliki setidaknya 6 karakter.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Harap konfirmasi kata sandi.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Kata sandi tidak cocok.";
        }
    }

    // Check input errors before inserting in database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_username, $param_email, $param_password, $param_role);
            
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Create a password hash
            $param_role = 2; // Set default role to 2
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
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
            <h2 class="text-4xl font-bold text-center text-white mb-6">Buat Akun</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-4">
                    <label for="username" class="block text-white text-sm font-bold mb-2">Nama Pengguna</label>
                    <input type="text" id="username" name="username" value="<?php echo $username; ?>" placeholder="Masukkan nama pengguna Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <span class="text-red-500 text-sm"><?php echo $username_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="Masukkan email Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <span class="text-red-500 text-sm"><?php echo $email_err; ?></span>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-white text-sm font-bold mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Masukkan kata sandi Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <span class="text-red-500 text-sm"><?php echo $password_err; ?></span>
                </div>
                <div class="mb-6">
                    <label for="confirm_password" class="block text-white text-sm font-bold mb-2">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Konfirmasi kata sandi Anda" class="input shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition duration-300 ease-in-out">
                    <span class="text-red-500 text-sm"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <button type="submit" class="btn w-full bg-gradient-to-r from-purple-500 to-blue-500 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform">Daftar</button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-white">Sudah punya akun? <a href="/login" class="text-blue-500 hover:text-blue-800 transition duration-300 ease-in-out transform hover:scale-105">Login</a></p>
            </div>
        </div>
    </div>

    <script src="/public/js/script.js"></script>
</body>
</html>
