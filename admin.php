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
    
    // If role is not 'Admin', redirect to login page
    if ($role !== 'Admin') {
        header("Location: login_register.php");
        exit();
    }
} else {
    // If user not found, redirect to login page
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

function displayUserData($conn) {
    $sql = "SELECT * FROM users WHERE role = 'User'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Data Pengguna</h2>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        echo "<thead class='table-dark'>";
        echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Nama</th><th>Umur</th><th>Gender</th><th>No. Telpon</th><th>Email</th><th>Action</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['username']."</td>";
            echo "<td>".$row['password']."</td>";
            echo "<td>".$row['Nama']."</td>";
            echo "<td>".$row['umur']."</td>";
            echo "<td>".$row['gender']."</td>";
            echo "<td>".$row['no_telpon']."</td>";
            echo "<td>".$row['email']."</td>";
            echo "<td><button class='btn btn-sm btn-primary edit-user' data-id='".$row['id']."' onclick='editUser(".$row['id'].")'>Edit</button> <button class='btn btn-sm btn-danger' onclick='deleteUser(".$row['id'].")'>Hapus</button></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "Tidak ada data pengguna.";
    }
}


function displayAddUserForm() {
    echo "<div style='margin: 0 auto; max-width: 500px;'>"; // Maksimum lebar form 500px dan posisi tengah
    echo "<div style='border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;'>"; // Kotak dengan garis pinggir, padding, dan latar belakang abu-abu muda
    echo "<h2 style='text-align: center;'>Tambah Pengguna</h2>"; // Judul di tengah

    echo "<form method='post'>";
    echo "<label for='username'>Username:</label><br>";
    echo "<input type='text' id='username' name='username' class='form-control' style='width: 100%;'><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<label for='password'>Password:</label><br>";
    echo "<input type='password' id='password' name='password' class='form-control' style='width: 100%;'><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<label for='nama'>Nama:</label><br>";
    echo "<input type='text' id='nama' name='nama' class='form-control' style='width: 100%;'><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<label for='umur'>Umur:</label><br>";
    echo "<input type='text' id='umur' name='umur' class='form-control' style='width: 100%;'><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<label for='gender'>Gender:</label><br>";
    echo "<input type='radio' id='gender_laki' name='gender' value='Laki-laki'> Laki-laki";
    echo "<input type='radio' id='gender_perempuan' name='gender' value='Perempuan'> Perempuan<br>";
    echo "<label for='no_telpon'>No. Telpon:</label><br>";
    echo "<input type='text' id='no_telpon' name='no_telpon' class='form-control' style='width: 100%;'><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<label for='email'>Email:</label><br>";
    echo "<input type='text' id='email' name='email' class='form-control' style='width: 100%;'><br><br>"; // Lebar 100% agar menyesuaikan kotak
    echo "<div style='display: flex; justify-content: space-between;'>"; // Gunakan flexbox untuk mengatur jarak antara tombol "Tambah" dan "Batal"
    echo "<input type='submit' name='add_user_submit' value='Tambah' class='btn btn-success'>"; // Tambahkan kelas 'btn' dan 'btn-success' untuk gaya Bootstrap
    echo "<button type='button' onclick='hideAddUserForm()' class='btn btn-secondary'>Batal</button>"; // Tambahkan kelas 'btn' dan 'btn-secondary' untuk gaya Bootstrap
    echo "</div>"; // Tutup flexbox
    echo "</form>";

    echo "</div>"; // Tutup kotak
    echo "</div>"; // Tutup div pusat
}






// Proses penambahan pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user_submit'])) {
    $add_username = $_POST['username'];
    $add_password = $_POST['password'];
    $add_nama = $_POST['nama'];
    $add_umur = $_POST['umur'];
    $add_gender = $_POST['gender'];
    $add_no_telpon = $_POST['no_telpon'];
    $add_email = $_POST['email'];

    // Inisialisasi array untuk menyimpan pesan kesalahan
    $errors = [];

    // Validasi input
    if (empty($add_username)) {
        $errors[] = "Username harus diisi";
    }

    // Validasi password
    if (empty($add_password)) {
        $errors[] = "Password harus diisi";
    }

    if (empty($add_nama)) {
        $errors[] = "Nama harus diisi";
    }

    if (empty($add_umur) || !is_numeric($add_umur) || $add_umur <= 0) {
        $errors[] = "Umur harus diisi dengan angka positif";
    }

    if (empty($add_gender)) {
        $errors[] = "Gender harus dipilih";
    }

    if (empty($add_no_telpon)) {
        $errors[] = "Nomor telepon harus diisi";
    }

    if (empty($add_email) || !filter_var($add_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email harus diisi dengan format yang benar";
    }

    // Jika tidak ada kesalahan, tambahkan data ke database
    if (empty($errors)) {
        // Lakukan penanganan data, misalnya penyimpanan ke database
        $sql_add_user = "INSERT INTO users (username, password, Nama, umur, gender, no_telpon, email) VALUES ('$add_username', '$add_password', '$add_nama', '$add_umur', '$add_gender', '$add_no_telpon', '$add_email')";

        if ($conn->query($sql_add_user) === TRUE) {
            $_SESSION['success_message'] = "Pengguna berhasil ditambahkan.";
            // Redirect untuk mencegah form resubmission saat menyegarkan halaman
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error: " . $sql_add_user . "<br>" . $conn->error;
        }
    } else {
        // Menampilkan pesan kesalahan jika ada
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}


// Fungsi untuk menampilkan tabel data dokter
function displayDoctorData($conn) {
    $sql = "SELECT u.Nama AS Nama_Dokter, d.*
            FROM users u
            JOIN doctors d ON u.id = d.id_dokter";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Data Dokter</h2>";
        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered'>";
        echo "<thead class='table-dark'>";
        echo "<tr><th>ID</th><th>ID Dokter</th><th>Nama Dokter</th><th>Spesialisasi</th><th>No. Lisensi Praktik</th><th>Pendidikan</th><th>Pengalaman Kerja</th><th>Tanggal Registrasi</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td>".$row['id_dokter']."</td>";
            echo "<td>".$row['Nama_Dokter']."</td>";
            echo "<td>".$row['specialization']."</td>";
            echo "<td>".$row['practice_license_number']."</td>";
            echo "<td>".$row['education_history']."</td>";
            echo "<td>".$row['work_experience']."</td>";
            echo "<td>".$row['registration_date']."</td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "Tidak ada data dokter.";
    }
}

// Ambil data pengguna yang akan diedit
$userData = [];
if (isset($_GET['id'])) {
    $edit_user_id = $_GET['id'];
    $sql_get_user = "SELECT * FROM users WHERE id='$edit_user_id'";
    $result_get_user = $conn->query($sql_get_user);
    if ($result_get_user->num_rows > 0) {
        $userData = $result_get_user->fetch_assoc();
    }
}

/// Proses penyimpanan perubahan data pengguna

// Proses edit pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_submit'])) {
    $edit_user_id = $_POST['edit_user_id'];
    $edit_username = $_POST['edit_username'];
    $edit_nama = $_POST['edit_nama'];
    $edit_umur = $_POST['edit_umur'];
    $edit_gender = $_POST['edit_gender'];
    $edit_no_telpon = $_POST['edit_no_telpon'];
    $edit_email = $_POST['edit_email'];

    // Inisialisasi array untuk menyimpan pesan kesalahan
    $errors = [];

    // Validasi input
    if (empty($edit_username)) {
        $errors[] = "Username harus diisi";
    }

    if (empty($edit_nama)) {
        $errors[] = "Nama harus diisi";
    }

    if (empty($edit_umur) || !is_numeric($edit_umur) || $edit_umur <= 0) {
        $errors[] = "Umur harus diisi dengan angka positif";
    }

    if (empty($edit_gender)) {
        $errors[] = "Gender harus dipilih";
    }

    if (empty($edit_no_telpon)) {
        $errors[] = "Nomor telepon harus diisi";
    }

    if (empty($edit_email) || !filter_var($edit_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email harus diisi dengan format yang benar";
    }

    // Jika tidak ada kesalahan, update data di database
    if (empty($errors)) {
        // Lakukan penanganan data, misalnya penyimpanan ke database
        $sql_update_user = "UPDATE users SET username='$edit_username', Nama='$edit_nama', umur='$edit_umur', gender='$edit_gender', no_telpon='$edit_no_telpon', email='$edit_email' WHERE id='$edit_user_id'";

        if ($conn->query($sql_update_user) === TRUE) {
            $_SESSION['success_message'] = "Perubahan berhasil disimpan.";
            // Redirect untuk mencegah form resubmission saat menyegarkan halaman
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error: " . $sql_update_user . "<br>" . $conn->error;
        }
    } else {
        // Menampilkan pesan kesalahan jika ada
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    form {
        display: inline-block; /* Agar formulir tidak memenuhi lebar penuh */
        margin-right: 20px; /* Beri jarak antara formulir dan tombol */
    }
    </style>

    <style>
        body {
            background-color: #add8e6; /* Warna background biru */
        }
        /* Navbar gradient merah permata */
        .navbar {
            background: linear-gradient(to right, #ff007f, #800080) !important;
        }
        /* Ganti warna latar belakang tabel menjadi putih */
        table {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark bg-gradient">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="Img/health2.png" alt="Health Tracker Logo"></a>
            <a class="navbar-brand text-white" href="#"><b>Health Tracker Online (Admin)</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Tambahkan item navbar untuk admin -->
                    <li class="nav-item">
                        <a class="nav-link text-white"  href="#user_data">Lihat Data Pengguna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white"  href="#doctor_data">Lihat Data Dokter</a>
                    </li>
                </ul>
                <form class="d-flex" method="post">
                    <button class="btn btn-outline-success" type="submit" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Content goes here -->
    <div class="container mt-4" id="doctor_data">
        <?php 
        displayDoctorData($conn);
        ?>
    </div>
    <div class="container mt-4" id="user_data">
        <?php 
        displayUserData($conn);
        ?>
    </div>

    <!-- Tombol untuk menampilkan form tambah pengguna -->
    <div class="mt-4">
        <button class="btn btn-primary" onclick="showAddUserForm()">Tambah Pengguna</button>
    </div>

    <!-- Form untuk menambah pengguna -->
    <div id="addUserForm" style="display: none;">
        <?php displayAddUserForm(); ?>
        <div class="d-grid gap-2">
        </div>
    </div>

<!-- User Edit Form (Hidden by default) -->
<div id="editUserForm" style="display: none;">
    <h4>Edit Pengguna</h4>
    <form method="post" id="editUserForm">
        <input type="hidden" id="editUserId" name="edit_user_id">
        <div class="mb-3">
            <label for="edit_username" class="form-label">Username:</label>
            <input type="text" id="edit_username" name="edit_username" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_nama" class="form-label">Nama:</label>
            <input type="text" id="edit_nama" name="edit_nama" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_umur" class="form-label">Umur:</label>
            <input type="number" id="edit_umur" name="edit_umur" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_gender" class="form-label">Gender:</label>
            <select id="edit_gender" name="edit_gender" class="form-select">
                <option value="male">Laki-laki</option>
                <option value="female">Perempuan</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="edit_no_telpon" class="form-label">Nomor Telepon:</label>
            <input type="tel" id="edit_no_telpon" name="edit_no_telpon" class="form-control">
        </div>
        <div class="mb-3">
            <label for="edit_email" class="form-label">Email:</label>
            <input type="email" id="edit_email" name="edit_email" class="form-control">
        </div>
        <div class="d-grid gap-2">
            <button type="submit" name="edit_user_submit" class="btn btn-primary">Simpan Perubahan</button>
            <button type="button" class="btn btn-secondary cancel-edit">Batal</button>
        </div>
    </form>
</div>





    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
  // Tombol edit pengguna diklik
$(".edit-user").click(function() {
    var userId = $(this).data("id");
    $.ajax({
        url: "fetch_user.php", // Ganti dengan alamat file yang sesuai
        type: "POST",
        data: { id: userId },
        success: function(response) {
            var userData = JSON.parse(response);
            $("#editUserId").val(userData.id);
            $("#edit_username").val(userData.username);
            $("#edit_nama").val(userData.nama);
            $("#edit_umur").val(userData.umur);
            $("#edit_gender").val(userData.gender);
            $("#edit_no_telpon").val(userData.no_telpon);
            $("#edit_email").val(userData.email);
            $("#editUserForm").show();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

// Tombol pembatalan edit diklik
$(".cancel-edit").click(function() {
    $("#editUserForm").hide();
});



    </script>
    <script>
    $(document).ready(function() {
        <?php if(isset($_SESSION['success_message'])): ?>
            alert("<?php echo $_SESSION['success_message']; ?>");
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        });
    </script>
<script>
    function deleteUser(id) {
        if (confirm("Anda yakin ingin menghapus pengguna ini?")) {
            window.location.href = "delete_user.php?id=" + id;
        }
    }

    function showAddUserForm() {
        document.getElementById("addUserForm").style.display = "block";
    }

    function hideAddUserForm() {
        document.getElementById("addUserForm").style.display = "none";
    }
</script>
</body>
</html>
