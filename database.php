<?php
// Fungsi untuk koneksi ke database
function connectDB() {
    $servername = "localhost";
    $username = "id22040888_adit";
    $password = "Onlinehealth1234@";
    $dbname = "id22040888_online_health";

    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Memeriksa koneksi
    if ($conn->connect_error) {
       
        die("Connection failed: " . $conn->connect_error);
    }
    else{
        
    }

    return $conn;
}