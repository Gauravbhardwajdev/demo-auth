<?php
$host = getenv('DB_HOST') ?: 'db';   // <-- IMPORTANT
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'rootpass123';
$name = getenv('DB_NAME') ?: 'demo_auth';

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
  die("DB connection failed: " . $conn->connect_error);
}
?>
