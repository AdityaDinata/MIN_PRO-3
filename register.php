<?php
session_start();
include 'database.php';
$conn = connectDB();

// Inisialisasi variabel
$new_username = "";
$new_password = "";
$new_name = "";
$umur = "";
$gender = "";
$no_telpon = "";
$email = "";
$new_username_err = "";
$new_password_err = "";
$new_name_err = "";
$umur_err = "";
$gender_err = "";
$email_err = "";
$username_exists_err = "";
$registration_success = false;
$no_telpon_err = ""; // Menambah definisi variabel $no_telpon_err

// Memproses data form saat dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        $new_name = $_POST['new_name'];
        $umur = $_POST['umur'];
        $gender = $_POST['gender'];
        $no_telpon = $_POST['no_telpon'];
        $email = $_POST['email'];

        // Validasi form
        if (empty(trim($new_username))) {
            $new_username_err = "Harap masukkan nama pengguna.";
        }
        if (empty(trim($new_password))) {
            $new_password_err = "Harap masukkan kata sandi Anda.";
        }
        if (empty(trim($new_name))) {
            $new_name_err = "Harap masukkan nama Anda.";
        }
        if (empty(trim($umur))) {
            $umur_err = "Harap masukkan umur Anda.";
        } elseif ($umur < 0) {
            $umur_err = "Umur harus lebih besar dari atau sama dengan 0.";
        }
        if (empty(trim($gender))) {
            $gender_err = "Harap pilih jenis kelamin Anda.";
        }
        if (empty(trim($email))) {
            $email_err = "Harap masukkan alamat email Anda.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Format email tidak valid.";
        }

        // Periksa apakah nomor telepon sudah digunakan
        $sql_check_telpon = "SELECT id FROM users WHERE no_telpon = ?";
        if ($stmt = mysqli_prepare($conn, $sql_check_telpon)) {
            mysqli_stmt_bind_param($stmt, "s", $param_telpon);
            $param_telpon = $no_telpon;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $no_telpon_err = "Nomor telepon sudah digunakan.";
                }
            } else {
                echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt);
        }

        // Periksa apakah email sudah digunakan
        $sql_check_email = "SELECT id FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql_check_email)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $email_err = "Alamat email sudah digunakan.";
                }
            } else {
                echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt);
        }

        // Jika tidak ada kesalahan validasi, proses registrasi
        if (empty($new_username_err) && empty($new_password_err) && empty($new_name_err) && empty($umur_err) && empty($gender_err) && empty($email_err) && empty($no_telpon_err)) {
            // Periksa apakah username sudah digunakan
            $sql_check_username = "SELECT id FROM users WHERE username = ?";
            if ($stmt = mysqli_prepare($conn, $sql_check_username)) {
                mysqli_stmt_bind_param($stmt, "s", $param_username);
                $param_username = $new_username;
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $username_exists_err = "Nama pengguna sudah digunakan.";
                    } else {
                        // Simpan data pengguna ke database
                        $sql = "INSERT INTO users (username, password, Role, Nama, umur, gender, no_telpon, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        if ($stmt = mysqli_prepare($conn, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ssssisss", $param_username, $param_password, $param_role, $param_name, $param_umur, $param_gender, $param_no_telpon, $param_email);
                            $param_username = $new_username;
                            $param_password = $new_password;
                            $param_role = "User"; // Peran default
                            $param_name = $new_name;
                            $param_umur = $umur;
                            $param_gender = $gender;
                            $param_no_telpon = $no_telpon;
                            $param_email = $email;
                            if (mysqli_stmt_execute($stmt)) {
                                $registration_success = true;
                            } else {
                                echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
                }
            }
        }
        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #98FB98; /* Warna latar belakang di luar kotak registrasi */
        }
        .card {
            background-color: #f8f9fa; /* Warna latar belakang kotak registrasi */
            border-radius: 10px; /* Sudut melengkung kotak registrasi */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Bayangan untuk efek kedalaman */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div style="text-align: center;">
                            <img src="Img/health4.png" alt="">
                        </div>
                        <h2 class="text-center mb-4">Registrasi</h2>
                        <?php if ($registration_success) : ?>
                            <div class="alert alert-success" role="alert">
                                Registrasi berhasil! Silakan <a href="login_register.php" class="alert-link">masuk</a> ke akun Anda.
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Nama Lengkap:</label>
                                <input type="text" name="new_name" class="form-control" value="<?php echo $new_name; ?>">
                                <span class="text-danger"><?php echo $new_name_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Nama Pengguna:</label>
                                <input type="text" name="new_username" class="form-control" value="<?php echo $new_username; ?>">
                                <span class="text-danger"><?php echo $new_username_err; ?></span>
                                <span class="text-danger"><?php echo $username_exists_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Kata Sandi:</label>
                                <input type="password" name="new_password" class="form-control">
                                <span class="text-danger"><?php echo $new_password_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Umur:</label>
                                <input type="number" name="umur" class="form-control" value="<?php echo $umur; ?>">
                                <span class="text-danger"><?php echo $umur_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Jenis Kelamin:</label><br>
                                <input type="radio" name="gender" value="Laki-laki" <?php if ($gender == "Laki-laki") echo "checked"; ?>> Laki-laki
                                <input type="radio" name="gender" value="Perempuan" <?php if ($gender == "Perempuan") echo "checked"; ?>> Perempuan
                                <span class="text-danger"><?php echo $gender_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>No Telepon:</label>
                                <input type="text" name="no_telpon" class="form-control" value="<?php echo $no_telpon; ?>">
                                <span class="text-danger"><?php echo $no_telpon_err; ?></span>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                                <span class="text-danger"><?php echo $email_err; ?></span>
                            </div>
                            <br>
                            <div class="form-group">
                                <input type="submit" name="register" class="btn btn-success btn-block" value="Registrasi">
                                <a href="login_register.php" class="btn btn-secondary btn-block">Kembali ke Halaman Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
