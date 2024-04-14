<?php
include 'database.php'; // Include database connection file
$conn = connectDB(); // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $edit_user_id = $_POST['id'];
    $sql_get_user = "SELECT * FROM users WHERE id='$edit_user_id'";
    $result_get_user = $conn->query($sql_get_user);
    if ($result_get_user->num_rows > 0) {
        $userData = $result_get_user->fetch_assoc();
        echo json_encode($userData);
    } else {
        echo "User not found";
    }
} else {
    echo "Invalid request";
}
?>
