<?php
$host = "localhost";
$db = "zeebag";
$user = "root";
$pass = "";
// Koneksi 
$conn = new mysqli($host, $user, $pass, $db);
// Cek koneksi 
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start();