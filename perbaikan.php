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

$errors = [];

// Get the user_id from the session
$user_id = $_SESSION['id'];

// Process physical activity form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_activity'])) {
    $activity_name = $_POST['activity_name'];
    $duration_minutes = $_POST['duration_minutes'];
    $activity_date = $_POST['activity_date'];

    // Validate input
    if (empty($activity_name)) {
        $errors[] = "Nama aktivitas fisik harus diisi";
    }

    if (empty($duration_minutes) || !is_numeric($duration_minutes) || $duration_minutes <= 0) {
        $errors[] = "Durasi aktivitas fisik harus diisi dengan angka positif";
    }

    // If no errors, save data to the database
    if (empty($errors)) {
        // Check if the activity already exists for the same date
        $sql_check_activity = "SELECT * FROM activity_table WHERE activity_name = '$activity_name' AND activity_date = '$activity_date' AND user_id = '$user_id'";
        $result_check_activity = $conn->query($sql_check_activity);
        if ($result_check_activity->num_rows > 0) {
            // If the activity already exists for the same date, update the duration
            $existing_activity = $result_check_activity->fetch_assoc();
            $existing_duration = $existing_activity['duration_minutes'];
            $new_duration = $existing_duration + $duration_minutes;
            $sql_update_activity = "UPDATE activity_table SET duration_minutes = '$new_duration' WHERE activity_name = '$activity_name' AND activity_date = '$activity_date' AND user_id = '$user_id'";
            $conn->query($sql_update_activity);
        } else {
            // If the activity does not exist for the same date, insert into database
            $sql_activity = "INSERT INTO activity_table (activity_name, duration_minutes, activity_date, user_id) VALUES ('$activity_name', '$duration_minutes', '$activity_date', '$user_id')";
            $result_activity = $conn->query($sql_activity);
        }
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// Process food intake form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_food'])) {
    $food_name = $_POST['food_name'];
    $quantity = $_POST['quantity'];
    $intake_date = $_POST['intake_date'];

    // Validate input
    if (empty($food_name)) {
        $errors[] = "Nama makanan harus diisi";
    }

    if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
        $errors[] = "Jumlah makanan harus diisi dengan angka positif";
    }

    // If no errors, save data to the database
    if (empty($errors)) {
        // Check if the food already exists for the same date
        $sql_check_food = "SELECT * FROM food_intake_table WHERE food_name = '$food_name' AND intake_date = '$intake_date' AND user_id = '$user_id'";
        $result_check_food = $conn->query($sql_check_food);
        if ($result_check_food->num_rows > 0) {
            // If the food already exists for the same date, update the quantity
            $existing_food = $result_check_food->fetch_assoc();
            $existing_quantity = $existing_food['quantity'];
            $new_quantity = $existing_quantity + $quantity;
            $sql_update_food = "UPDATE food_intake_table SET quantity = '$new_quantity' WHERE food_name = '$food_name' AND intake_date = '$intake_date' AND user_id = '$user_id'";
            $conn->query($sql_update_food);
        } else {
            // If the food does not exist for the same date, insert into database
            $sql_food = "INSERT INTO food_intake_table (food_name, quantity, intake_date, user_id) VALUES ('$food_name', '$quantity', '$intake_date', '$user_id')";
            $result_food = $conn->query($sql_food);
        }
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// Process health data form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_health'])) {
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $measurement_date = $_POST['measurement_date'];

    // Validate input
    if (empty($weight) || !is_numeric($weight) || $weight <= 0) {
        $errors[] = "Berat badan harus diisi dengan angka positif";
    }

    if (empty($height) || !is_numeric($height) || $height <= 0) {
        $errors[] = "Tinggi badan harus diisi dengan angka positif";
    }

    // If no errors, save data to the database
    if (empty($errors)) {
        // Check if the health data already exists for the same date
        $sql_check_health = "SELECT * FROM health_data_table WHERE measurement_date = '$measurement_date' AND user_id = '$user_id'";
        $result_check_health = $conn->query($sql_check_health);
        if ($result_check_health->num_rows > 0) {
            // If the health data already exists for the same date, update the data
            $existing_health = $result_check_health->fetch_assoc();
            $existing_weight = $existing_health['weight'];
            $existing_height = $existing_health['height'];
            // You may want to decide how to handle updating existing health data here
        } else {
            // If the health data does not exist for the same date, insert into database
            $sql_health = "INSERT INTO health_data_table (weight, height, measurement_date, user_id) VALUES ('$weight', '$height', '$measurement_date', '$user_id')";
            $result_health = $conn->query($sql_health);
        }
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}



// Process edit activity form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_activity'])) {
    $edit_activity_id = $_POST['edit_activity_id'];
    $edit_activity_name = $_POST['edit_activity_name'];
    $edit_duration_minutes = $_POST['edit_duration_minutes'];
    $edit_activity_date = $_POST['edit_activity_date'];

    // Validate input
    if (empty($edit_activity_name)) {
        $errors[] = "Nama aktivitas fisik harus diisi";
    }

    if (empty($edit_duration_minutes) || !is_numeric($edit_duration_minutes) || $edit_duration_minutes <= 0) {
        $errors[] = "Durasi aktivitas fisik harus diisi dengan angka positif";
    }

    if (empty($edit_activity_date)) {
        $errors[] = "Tanggal aktivitas fisik harus diisi";
    }

    // If no errors, update data in the database
    if (empty($errors)) {
        $sql_update_activity = "UPDATE activity_table SET activity_name='$edit_activity_name', duration_minutes='$edit_duration_minutes', activity_date='$edit_activity_date' WHERE id='$edit_activity_id' AND user_id='$user_id'";
        $result_update_activity = $conn->query($sql_update_activity);
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}



// <!-- Process edit food intake form if submitted -->
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_food'])) {
    $edit_food_id = $_POST['edit_food_id'];
    $edit_food_name = $_POST['edit_food_name'];
    $edit_quantity = $_POST['edit_quantity'];
    $edit_intake_date = $_POST['edit_intake_date'];

    // Validate input
    if (empty($edit_food_name)) {
        $errors[] = "Nama makanan harus diisi";
    }

    if (empty($edit_quantity) || !is_numeric($edit_quantity) || $edit_quantity <= 0) {
        $errors[] = "Jumlah makanan harus diisi dengan angka positif";
    }

    if (empty($edit_intake_date)) {
        $errors[] = "Tanggal konsumsi makanan harus diisi";
    }

    // If no errors, update data in the database
    if (empty($errors)) {
        $sql_update_food = "UPDATE food_intake_table SET food_name='$edit_food_name', quantity='$edit_quantity', intake_date='$edit_intake_date' WHERE id='$edit_food_id' AND user_id='$user_id'";
        $result_update_food = $conn->query($sql_update_food);
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}

// Process edit health data form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_health'])) {
    $edit_health_id = $_POST['edit_health_id'];
    $edit_weight = $_POST['edit_weight'];
    $edit_height = $_POST['edit_height'];
    $edit_measurement_date = $_POST['edit_measurement_date'];

    // Validate input
    if (empty($edit_weight) || !is_numeric($edit_weight) || $edit_weight <= 0) {
        $errors[] = "Berat badan harus diisi dengan angka positif";
    }

    if (empty($edit_height) || !is_numeric($edit_height) || $edit_height <= 0) {
        $errors[] = "Tinggi badan harus diisi dengan angka positif";
    }

    // If no errors, update data in the database
    if (empty($errors)) {
        $sql_update_health = "UPDATE health_data_table SET weight='$edit_weight', height='$edit_height', measurement_date='$edit_measurement_date' WHERE id='$edit_health_id' AND user_id='$user_id'";
        $result_update_health = $conn->query($sql_update_health);
        // Redirect to prevent form resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
}
// Retrieve physical activity data for the user
$sql_get_activities = "SELECT * FROM activity_table WHERE user_id = '$user_id'";
$result_activities = $conn->query($sql_get_activities);
$activityData = [];
while ($row = $result_activities->fetch_assoc()) {
    $activityData[] = $row;
}

// Retrieve food intake data for the user
$sql_get_food = "SELECT * FROM food_intake_table WHERE user_id = '$user_id'";
$result_food = $conn->query($sql_get_food);
$foodData = [];
while ($row = $result_food->fetch_assoc()) {
    $foodData[] = $row;
}

// Retrieve health data for the user
$sql_get_health = "SELECT * FROM health_data_table WHERE user_id = '$user_id'";
$result_health = $conn->query($sql_get_health);
$healthData = [];
while ($row = $result_health->fetch_assoc()) {
    $healthData[] = $row;
}

// Initialize arrays to store weight, height, date, and chart background colors
$weights = [];
$heights = [];
$dates = [];
$backgroundColor = [];

// Calculate BMI and determine chart color
foreach ($healthData as $data) {
    $weight = $data['weight'];
    $height = $data['height'];
    $date = $data['measurement_date'];
    if ($weight > 0 && $height > 0) {
        // Store weight, height, and date data
        $weights[] = $weight;
        $heights[] = $height;
        $dates[] = $date;
        $bmi = round($weight / (($height / 100) ** 2), 2);
        // Set chart color based on BMI
        if ($bmi > 25) {
            $backgroundColor[] = 'rgba(255, 99, 132, 0.6)'; // Red color
        } else {
            $backgroundColor[] = 'rgba(75, 192, 192, 0.6)'; // Green color
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_activity'])) {
    $delete_activity_id = $_POST['delete_activity_id'];
    $sql_delete_activity = "DELETE FROM activity_table WHERE id = '$delete_activity_id' AND user_id = '$user_id'";
    $conn->query($sql_delete_activity);
    // Redirect to prevent form resubmission on page refresh
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Process deletion of food intake data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_food'])) {
    $delete_food_id = $_POST['delete_food_id'];
    $sql_delete_food = "DELETE FROM food_intake_table WHERE id = '$delete_food_id' AND user_id = '$user_id'";
    $conn->query($sql_delete_food);
    // Redirect to prevent form resubmission on page refresh
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Process deletion of health data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_health'])) {
    $delete_health_id = $_POST['delete_health_id'];
    $sql_delete_health = "DELETE FROM health_data_table WHERE id = '$delete_health_id' AND user_id = '$user_id'";
    $conn->query($sql_delete_health);
    // Redirect to prevent form resubmission on page refresh
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light  bg-dark bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#"><b>Health Tracker Online</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white"  href="#physical_activity">Aktivitas Fisik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white"  href="#food_intake">Asupan Makanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white"  href="#health_data">Parameter Kesehatan</a>
                    </li>
                </ul>
                <form class="d-flex" method="post">
                    <button class="btn btn-outline-success" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container" id="physical_activity">
        <!-- Physical Activity Form -->
        <h2 class="text-center mb-4">Aktivitas Fisik</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Aktivitas Fisik</h4>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="activity_name" class="form-label">Nama Aktivitas:</label>
                        <input type="text" name="activity_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Durasi (Menit):</label>
                        <input type="number" name="duration_minutes" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="activity_date" class="form-label">Tanggal:</label>
                        <input type="date" name="activity_date" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit_activity" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
          <!-- Display Physical Activity Data -->
          <div>
    <h4>Detail Aktivitas Fisik</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Aktivitas</th>
                <th>Durasi (menit)</th>
                <th>Tanggal</th>
                <th>Action</th> <!-- Add this column for action buttons -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activityData as $activity) : ?>
                <tr>
                    <td><?php echo $activity['activity_name']; ?></td>
                    <td><?php echo $activity['duration_minutes']; ?></td>
                    <td><?php echo $activity['activity_date']; ?></td>
                    <td>
                        <!-- Edit button -->
                        <button class="btn btn-sm btn-primary edit-activity" data-id="<?= $activity['id'] ?>">Edit</button>
                        <!-- Delete button -->
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: inline-block;">
                            <input type="hidden" name="delete_activity_id" value="<?php echo $activity['id']; ?>">
                            <button type="submit" name="delete_activity" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Activity Edit Form (Hidden by default) -->
    <div id="editActivityForm" style="display: none;">
        <h4>Edit Aktivitas Fisik</h4>
        <form method="post" id="editActivityForm">
            <input type="hidden" id="editActivityId" name="edit_activity_id">
            <div class="mb-3">
                <label for="edit_activity_name" class="form-label">Nama Aktivitas:</label>
                <input type="text" id="edit_activity_name" name="edit_activity_name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="edit_duration_minutes" class="form-label">Durasi (Menit):</label>
                <input type="number" id="edit_duration_minutes" name="edit_duration_minutes" class="form-control">
            </div>
            <div class="mb-3">
                <label for="edit_activity_date" class="form-label">Tanggal:</label>
                <input type="date" id="edit_activity_date" name="edit_activity_date" class="form-control">
            </div>
            <div class="d-grid">
                <button type="submit" name="submit_edit_activity" class="btn btn-primary">Save Changes</button>
                <br>
                <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
            </div>
        </form>
    </div>

 
    <div class="container" id="food_intake">
        <!-- Food Intake Form -->
        <h2 class="text-center mb-4">Asupan Makanan</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Asupan Makanan</h4>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="food_name" class="form-label">Nama Makanan:</label>
                        <input type="text" name="food_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah Makanan:</label>
                        <input type="number" name="quantity" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="intake_date" class="form-label">Tanggal:</label>
                        <input type="date" name="intake_date" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit_food" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Display Food Intake Data -->
<div>
    <h4>Detail Asupan Makanan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Makanan</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Action</th> <!-- Add this column for action buttons -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($foodData as $food) : ?>
                <tr>
                    <td><?php echo $food['food_name']; ?></td>
                    <td><?php echo $food['quantity']; ?></td>
                    <td><?php echo $food['intake_date']; ?></td>
                    <td>
                    <button class="btn btn-sm btn-primary edit-food" data-id="<?= $food['id'] ?>">Edit</button>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: inline-block;">
                        <input type="hidden" name="delete_activity_id" value="<?php echo $activity['id']; ?>">
                        <button type="submit" name="delete_activity" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?')">Delete</button>
                    </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <!-- Food Edit Form (Hidden by default) -->
    <div id="editFoodForm" style="display: none;">
        <h4>Edit Konsumsi Makanan</h4>
        <form method="post" id="editFoodForm">
            <input type="hidden" id="editFoodId" name="edit_food_id">
            <div class="mb-3">
                <label for="edit_food_name" class="form-label">Nama Makanan:</label>
                <input type="text" id="edit_food_name" name="edit_food_name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="edit_quantity" class="form-label">Jumlah:</label>
                <input type="number" id="edit_quantity" name="edit_quantity" class="form-control">
            </div>
            <div class="mb-3">
                <label for="edit_intake_date" class="form-label">Tanggal:</label>
                <input type="date" id="edit_intake_date" name="edit_intake_date" class="form-control">
            </div>
            <div class="d-grid">
                <button type="submit" name="submit_edit_food" class="btn btn-primary">Save Changes</button>
                <br>
                <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
            </div>
        </form>
    </div>



    <div class="container" id="health_data">
        <!-- Health Data Form -->
        <h2 class="text-center mb-4">Parameter Kesehatan</h2>
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title">Parameter Kesehatan</h4>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="mb-3">
                        <label for="weight" class="form-label">Berat Badan (KG):</label>
                        <input type="number" name="weight" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="height" class="form-label">Tinggi Badan (CM):</label>
                        <input type="number" name="height" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="measurement_date" class="form-label">Tanggal:</label>
                        <input type="date" name="measurement_date" class="form-control">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="submit_health" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <!-- Display Errors -->
        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

 


        <!-- Display Health Data and BMI Calculation -->
        <div>
            <h4>Detail Kesehatan</h4>
            <?php
            $bmi = isset($weight) && isset($height) && $height > 0 ? round($weight / (($height / 100) ** 2), 2) : '';
            $backgroundColor = $bmi > 25 ? 'rgba(255, 99, 132, 0.6)' : 'rgba(75, 192, 192, 0.6)';
            ?>
            <p>Berat Badan: <?php echo isset($weight) ? $weight . ' kg' : ''; ?></p>
            <p>Tinggi Badan: <?php echo isset($height) ? $height . ' cm' : ''; ?></p>
            <p>BMI: <?php echo $bmi; ?></p>
        </div>
        <div class="container">
            <h2 class="mt-5 mb-4">Data Kesehatan Dalam Grafik</h2>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Data Kesehatan</h5>
                    <canvas id="healthChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Display Health Data -->
<div>
    <h4>Detail Kesehatan</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Berat Badan (KG)</th>
                <th>Tinggi Badan (CM)</th>
                <th>Tanggal</th>
                <th>Action</th> <!-- Add this column for action buttons -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($healthData as $health) : ?>
                <tr>
                    <td><?php echo $health['weight']; ?></td>
                    <td><?php echo $health['height']; ?></td>
                    <td><?php echo $health['measurement_date']; ?></td>
                    <td>
                        <!-- Edit button -->
                        <button class="btn btn-sm btn-primary edit-health" data-id="<?= $health['id'] ?>">Edit</button>
                        <!-- Delete button -->
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="display: inline-block;">
                            <input type="hidden" name="delete_health_id" value="<?php echo $health['id']; ?>">
                            <button type="submit" name="delete_health" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this health data?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Health Data Edit Form (Hidden by default) -->
<div id="editHealthForm" style="display: none;">
    <h4>Edit Data Kesehatan</h4>
    <form method="post" id="editHealthForm">
        <input type="hidden" id="editHealthId" name="edit_health_id">
        <div class="mb-3">
            <label for="edit_weight" class="form-label">Berat Badan (KG):</label>
            <input type="number" id="edit_weight" name="edit_weight" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_height" class="form-label">Tinggi Badan (CM):</label>
            <input type="number" id="edit_height" name="edit_height" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_measurement_date" class="form-label">Tanggal:</label>
            <input type="date" id="edit_measurement_date" name="edit_measurement_date" class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" name="submit_edit_health" class="btn btn-primary">Save Changes</button>
            <br>
            <button type="button" class="btn btn-secondary cancel-edit">Cancel</button>
        </div>
    </form>
</div>





        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
        <script>
            // Get health data and chart colors from PHP
            var weights = <?php echo json_encode($weights); ?>;
            var heights = <?php echo json_encode($heights); ?>;
            var dates = <?php echo json_encode($dates); ?>;
            var backgroundColor = <?php echo json_encode($backgroundColor); ?>;

            // Create BMI calculation function
            function calculateBMI(weight, height) {
                return (weight / ((height / 100) ** 2)).toFixed(2);
            }

            // Create health data chart
            var healthCtx = document.getElementById('healthChart').getContext('2d');
            var healthChart = new Chart(healthCtx, {
                type: 'line',
                data: {
                    labels: dates, // Labels for dates
                    datasets: [{
                        label: 'Berat Badan',
                        data: weights,
                        backgroundColor: backgroundColor, // Use dynamic background color
                        borderColor: backgroundColor, // Use dynamic border color
                        borderWidth: 1
                    }, {
                        label: 'Tinggi Badan',
                        data: heights,
                        backgroundColor: backgroundColor, // Use dynamic background color
                        borderColor: backgroundColor, // Use dynamic border color
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
    $(document).ready(function() {
        $('#activityTable').DataTable();

        // Function to populate edit form with data from selected row
        $(".edit-activity").click(function() {
            var activityId = $(this).data("id");
            var activity = <?= json_encode($activityData) ?>.find(item => item.id == activityId);
            $("#editActivityId").val(activity.id);
            $("#edit_activity_name").val(activity.activity_name);
            $("#edit_duration_minutes").val(activity.duration_minutes);
            $("#edit_activity_date").val(activity.activity_date);
            $("#editActivityForm").show();
        });

        // Function to hide edit form when cancel button is clicked
        $(".cancel-edit").click(function() {
            $("#editActivityForm").hide();
        });
    });

        // Function to populate edit form with data from selected food intake row
    $(".edit-food").click(function() {
        var foodId = $(this).data("id");
        var food = <?= json_encode($foodData) ?>.find(item => item.id == foodId);
        $("#editFoodId").val(food.id);
        $("#edit_food_name").val(food.food_name);
        $("#edit_quantity").val(food.quantity);
        $("#edit_intake_date").val(food.intake_date);
        $("#editFoodForm").show();
    });

    // Function to populate edit form with data from selected health data row
    $(".edit-health").click(function() {
        var healthId = $(this).data("id");
        var health = <?= json_encode($healthData) ?>.find(item => item.id == healthId);
        $("#editHealthId").val(health.id);
        $("#edit_weight").val(health.weight);
        $("#edit_height").val(health.height);
        $("#edit_measurement_date").val(health.measurement_date);
        $("#editHealthForm").show();
    });


    </script>
</body>
</html>