<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST["full_name"] ?? "");
    $email   = trim($_POST["email"] ?? "");
    $pass    = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $pass === "" || $confirm === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($pass !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // NO hashing: store plain text password in password_hash column
        $stmt = $conn->prepare(
            "INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)"
        );
        if (!$stmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $name, $email, $pass);

            if ($stmt->execute()) {
                echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Signup</title></head><body>';
                echo '<script>
                        alert("Account created successfully! Please log in.");
                        window.location.href = "DemoLogin.html";
                      </script>';
                echo '</body></html>';
                exit;
            } else {
                if ($conn->errno == 1062) {
                    $error = "Email already exists. Try another email.";
                } else {
                    $error = "Database error: ' . $conn->error . '";
                }
            }
        }
    }

    // show error popup and go back to signup
    if (!empty($error)) {
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Signup</title></head><body>';
        echo '<script>
                alert(' . json_encode($error) . ');
                window.location.href = "DemoSignup.html";
              </script>';
        echo '</body></html>';
        exit;
    }
} else {
    header("Location: DemoSignup.html");
    exit;
}
