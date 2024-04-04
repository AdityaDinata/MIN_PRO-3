<?php
session_start();
include 'database.php';
$conn = connectDB();

// Inisialisasi variabel
$new_username = "";
$new_password = "";
$new_name = "";
$new_username_err = "";
$new_password_err = "";
$new_name_err = "";
$username_exists_err = "";

// Memproses data form saat dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $new_name = $_POST['new_name'];

        // Validasi form
        if (empty(trim($new_username))) {
            $new_username_err = "Please enter username.";
        }
        if (empty(trim($new_password))) {
            $new_password_err = "Please enter your password.";
        }
        if (empty(trim($new_name))) {
            $new_name_err = "Please enter your name.";
        }

        // Jika tidak ada kesalahan validasi, proses registrasi
        if (empty($new_username_err) && empty($new_password_err) && empty($new_name_err)) {
            // Periksa apakah username sudah digunakan
            $sql_check_username = "SELECT id FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($conn, $sql_check_username)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $new_username;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $username_exists_err = "Username already exists.";
                    } else {
                        // Simpan data pengguna ke database
                        $sql = "INSERT INTO users (username, password, Role, Nama) VALUES (?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($conn, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_role, $param_name);
                            $param_username = $new_username;
                            $param_password = $new_password;
                            $param_role = "User"; // Default role
                            $param_name = $new_name;
                            if (mysqli_stmt_execute($stmt)) {
                                header("location: login_register.php");
                            } else {
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Register</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Nama Lengkap:</label>
                                <input type="text" name="new_name" class="form-control" value="<?php echo $new_name; ?>">
                                <span class="text-danger"><?php echo $new_name_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Username:</label>
                                <input type="text" name="new_username" class="form-control" value="<?php echo $new_username; ?>">
                                <span class="text-danger"><?php echo $new_username_err; ?></span>
                                <span class="text-danger"><?php echo $username_exists_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Password:</label>
                                <input type="password" name="new_password" class="form-control">
                                <span class="text-danger"><?php echo $new_password_err; ?></span>
                            </div>
                            <br>
                            <div class="form-group">
                                <input type="submit" name="register" class="btn btn-success" value="Register">
                                <a href="login_register.php" class="btn btn-secondary">Kembali ke Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

