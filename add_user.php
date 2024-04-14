<?php
session_start();

include 'database.php'; // Include database connection file
$conn = connectDB(); // Connect to the database

// Proses penambahan pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_add_user'])) {
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $umur = $_POST['umur'];
    $gender = $_POST['gender'];
    $no_telpon = $_POST['no_telpon'];
    $email = $_POST['email'];

    // Lakukan validasi input di sini

    // Jika tidak ada kesalahan, tambahkan pengguna ke database
    // Lakukan penanganan data, misalnya penyimpanan ke database
    $sql_add_user = "INSERT INTO users (username, Nama, umur, gender, no_telpon, email) VALUES ('$username', '$nama', '$umur', '$gender', '$no_telpon', '$email')";

    if ($conn->query($sql_add_user) === TRUE) {
        $_SESSION['success_message'] = "Pengguna berhasil ditambahkan.";
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sql_add_user . "<br>" . $conn->error;
    }
}

// Redirect ke halaman utama jika tidak ada data yang dikirim
header("Location: {$_SERVER['PHP_SELF']}");
exit();
?>
