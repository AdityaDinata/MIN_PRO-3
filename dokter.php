<?php
session_start();

include 'database.php'; // Include database connection file
$conn = connectDB(); // Connect to the database

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login_register.php");
    exit();
}

// Process logout
if(isset($_POST['logout'])){
    // Destroy the session
    session_destroy();
    header("Location: login_register.php");
    exit();
}

// Check the role of the user
$sql = "SELECT role FROM users WHERE id = '{$_SESSION['id']}'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['role'];
    
 
    if ($role !== 'Dokter') {
        header("Location: login_register.php");
        exit();
    }
} else {

    header("Location: login_register.php");
    exit();
}


// Process logout
if(isset($_POST['logout'])){
    // Destroy the session
    session_destroy();
    header("Location: login_register.php");
    exit();
}

$errors = [];



// Process response to consultation form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['respond_consultation'])) {
    $consultation_id = $_POST['consultation_id'];
    $response = $_POST['response'];

    // Validate input if needed

    // Save doctor's response to the consultation in the database
    $sql_update_response = "UPDATE consultations SET response='$response', responded=1 WHERE id='$consultation_id'";
    if ($conn->query($sql_update_response) === TRUE) {
        $_SESSION['success_message'] = "Respon Berhasil dikirim ke pengguna.";
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        $errors[] = "Error updating response: " . $conn->error;
    }
}

// Retrieve consultations that have not been responded to yet
$sql_get_unanswered_consultations = "SELECT * FROM consultations WHERE responded=0";
$result_unanswered_consultations = $conn->query($sql_get_unanswered_consultations);
$unansweredConsultations = [];
while ($row = $result_unanswered_consultations->fetch_assoc()) {
    $unansweredConsultations[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Burger Icon</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .container {
            margin-top: 50px;
        }
        body {
            background-color: #add8e6; /* Ubah warna background menjadi biru */
        }
        /* Ubah warna navbar menjadi gradient hijau */
        .navbar {
            background: linear-gradient(to right, #28a745, #218838) !important;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-gradient" style="background-color: #4CAF50; /* Green */">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="Img/health2.png" alt="Health Tracker Logo"></a>
        <a class="navbar-brand text-white" href="#"><b>Health Tracker Online</b></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <!-- Tambahkan link navigasi lainnya di sini jika diperlukan -->
            </ul>
            <form class="d-flex" method="post">
                <button class="btn btn-outline-danger" type="submit" name="logout">Logout</button>
            </form>
        </div>
    </div>
</nav>


    <!-- Dashboard Dokter -->
    <div class="container" id="consultations">
        <h2 class="text-center mb-4">Konsultasi Pasien</h2>
        <!-- Tampilkan daftar konsultasi yang belum ditanggapi -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Konsultasi yang belum ditanggapi:</h5>
                <ul>
                    <?php foreach ($unansweredConsultations as $consultation) : ?>
                        <li>
                            <strong>ID Pasien:</strong> <?php echo $consultation['patient_id']; ?><br>
                            <strong>Tanggal Konsultasi:</strong> <?php echo $consultation['consultation_date']; ?><br>
                            <strong>Pesan:</strong> <?php echo $consultation['message']; ?><br>
                            <!-- Form untuk memberi masukan kepada pasien -->
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="consultation_id" value="<?php echo $consultation['id']; ?>">
                                <div class="mb-3">
                                    <label for="response">Masukan Dokter:</label>
                                    <textarea name="response" class="form-control" rows="3" required></textarea>
                                </div>
                                <button type="submit" name="respond_consultation" class="btn btn-primary">Kirim Masukan</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        <?php if(isset($_SESSION['success_message'])): ?>
            alert("<?php echo $_SESSION['success_message']; ?>");
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
    });
    </script>

</body>
</html>
