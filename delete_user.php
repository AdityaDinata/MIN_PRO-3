<?php
session_start(); // Memulai sesi

include 'database.php'; // Include database connection file
$conn = connectDB(); // Connect to the database

if (isset($_GET['id'])) {
    $delete_user_id = $_GET['id'];

    // Hapus terlebih dahulu semua entri terkait dari tabel consultations
    $sql_delete_consultations = "DELETE FROM consultations WHERE patient_id='$delete_user_id'";

    if ($conn->query($sql_delete_consultations)) {
        // Setelah berhasil menghapus entri terkait, lanjutkan dengan menghapus pengguna dari tabel users
        $sql_delete_user = "DELETE FROM users WHERE id='$delete_user_id'";

        if ($conn->query($sql_delete_user) === TRUE) {
            $_SESSION['success_message'] = "Pengguna berhasil dihapus.";
        } else {
            echo "Error: " . $sql_delete_user . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql_delete_consultations . "<br>" . $conn->error;
    }

    // Setelah selesai menghapus, arahkan pengguna kembali ke halaman utama
    header("Location: admin.php"); // Ganti "index.php" dengan halaman tujuan yang sesuai
    exit();

} else {
    // Redirect ke halaman utama jika tidak ada pengalihan sebelumnya
    header("Location: admin.php"); // Ganti "index.php" dengan halaman tujuan yang sesuai
    exit();
}
?>
