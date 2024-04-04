<?php
// Fungsi untuk koneksi ke database
function connectDB() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "online_health";

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