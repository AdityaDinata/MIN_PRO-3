<?php
session_start();
include 'database.php';
$conn = connectDB();

// Inisialisasi variabel
$username = "";
$password = "";
$username_err = "";
$password_err = "";

// Memproses data form saat dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role']; // Menyimpan peran yang dipilih

        // Validasi form
        if (empty(trim($username))) {
            $username_err = "Please enter username.";
        }
        if (empty(trim($password))) {
            $password_err = "Please enter your password.";
        }

        // Jika tidak ada kesalahan validasi, proses login
        if (empty($username_err) && empty($password_err)) {
            // Cari pengguna berdasarkan nama pengguna
            $sql = "SELECT id, username, password, Role FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $username;

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    // Periksa apakah pengguna ada, jika ya, verifikasi kata sandi
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $username, $db_password, $db_role);
                        if (mysqli_stmt_fetch($stmt)) {
                            // Verifikasi kata sandi
                            if ($password === $db_password) { // Membandingkan secara langsung tanpa hashing
                                // Kata sandi cocok, verifikasi peran pengguna
                                if ($role == $db_role) {
                                    $_SESSION["loggedin"] = true;
                                    $_SESSION["id"] = $id;
                                    $_SESSION["username"] = $username;
                                    $_SESSION["role"] = $db_role;
                                    if ($role == "Admin") {
                                        header("location: admin.php");
                                    } elseif ($role == "Dokter") {
                                        header("location: dokter.php");
                                    } else {
                                        header("location: index.php");
                                    }
                                } else {
                                    $username_err = "Incorrect role selected for this user.";
                                }
                            } else {
                                // Tampilkan pesan error jika kata sandi salah
                                $password_err = "The password you entered was not valid.";
                            }
                        }
                    } else {
                        // Tampilkan pesan error jika nama pengguna tidak ditemukan
                        $username_err = "No account found with that username.";
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                // Menutup statement
                mysqli_stmt_close($stmt);
            }
        }
        // Menutup koneksi
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #98FB98; /* Warna latar belakang di luar kotak login */
        }
        .login-container {
            background-color: #f8f9fa; /* Warna latar belakang kotak login */
            border-radius: 10px; /* Sudut melengkung kotak login */
            padding: 20px; /* Ruang dalam kotak login */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Bayangan untuk efek kedalaman */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-container">
                    <div style="text-align: center;">
                     <img src="Img/health4.png" alt="">
                    </div>
                    <h2 class="text-center mb-4">Login</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                            <span class="text-danger"><?php echo $username_err; ?></span>
                        </div>
                        <div class="form-group">
                        <label>Password:</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i id="eyeIcon" class="bi bi-eye-slash-fill"></i>
                            </button>
                        </div>
                        <span class="text-danger"><?php echo $password_err; ?></span>
                        </div>

                        <!-- Dropdown untuk memilih peran -->
                        <div class="form-group">
                            <label>Role:</label>
                            <select name="role" class="form-select">
                                <option value="User" selected>User</option>
                                <option value="Admin">Admin</option>
                                <option value="Dokter">Dokter</option> <!-- Menambahkan pilihan untuk peran "Dokter" -->
                            </select>
                        </div>
                        <br>
                        <div class="d-grid gap-2">
                            <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
                            <a href="register.php" class="btn btn-secondary btn-block">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordInput.getAttribute('type') === 'password') {
            passwordInput.setAttribute('type', 'text');
            eyeIcon.classList.remove('bi-eye-slash-fill');
            eyeIcon.classList.add('bi-eye-fill');
        } else {
            passwordInput.setAttribute('type', 'password');
            eyeIcon.classList.remove('bi-eye-fill');
            eyeIcon.classList.add('bi-eye-slash-fill');
        }
    });
    </script>

</body>
</html>
