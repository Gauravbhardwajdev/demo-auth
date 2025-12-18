<?php
$host = "localhost";
$db   = "demo_auth";   // database name
$user = "root";        // XAMPP default user
$pass = "";            // XAMPP default password is empty

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
