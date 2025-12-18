<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    if ($email === "" || $pass === "") {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare(
            "SELECT password_hash FROM users WHERE email = ? LIMIT 1"
        );
        if (!$stmt) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Plain text comparison (because we stored plain text)
                if ($row["password_hash"] === $pass) {
                    // Correct password
                    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Login</title></head><body>';
                    echo '<script>
                            alert("Login successful! Welcome back.");
                            window.location.href = "DemoLogin.html"; // or a dashboard page
                          </script>';
                    echo '</body></html>';
                    exit;
                } else {
                    // Wrong password
                    $error = "Incorrect password. Please try again.";
                }
            } else {
                $error = "No account found with that email.";
            }
        }
    }

    // If we reach here, there is some error
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Login</title></head><body>';
    echo '<script>
            alert(' . json_encode($error) . ');
            window.location.href = "DemoLogin.html";
          </script>';
    echo '</body></html>';
    exit;
} else {
    header("Location: DemoLogin.html");
    exit;
}
