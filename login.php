<?php
session_start();
require 'config/db.php';

$action = $_GET['action'] ?? 'login';  // login, logout, or register

// HANDLE LOGOUT
if ($action === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $pass  = $_POST["password"] ?? "";

    if ($email === "" || $pass === "") {
        $error = "Email and password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, email, password_hash FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row && $row["password_hash"] === $pass) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login - Nebula</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/login.css" />
  <style>
    .error-toast { position: fixed; top: 20px; right: 20px; background: #ef4444; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(239,68,68,0.4); z-index: 1000; transform: translateX(400px); transition: all 0.3s ease; }
    .error-toast.show { transform: translateX(0); }
  </style>
</head>
<body class="is-fading">
  <?php if ($error): ?>
    <div class="error-toast show"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="stars"></div>
  <div class="nebula"></div>

  <main class="scene">
    <section class="card">
      <header class="card-header">
        <h1>ShowCaseFolio</h1>
        <p>Sign in to enter the system</p>
      </header>

      <form class="card-form" method="POST" action="login.php">
        <div class="field">
          <label for="email">Email</label>
          <div class="field-inner">
            <span class="field-orbit"></span>
            <input id="email" name="email" type="email" placeholder="you@example.com" value="<?= htmlspecialchars($_POST["email"] ?? '') ?>" required />
          </div>
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="field-inner">
            <span class="field-orbit"></span>
            <input id="password" name="password" type="password" placeholder="Enter your password" required />
          </div>
        </div>

        <button type="submit" class="btn-glow">
          <span class="btn-glow-orbit"></span>
          <span class="btn-glow-text">Login Now</span>
        </button>
      </form>

      <footer class="card-footer">
        <a href="register.php">Create account</a>
        <a href="forgot-password.php">Forgot Password</a>
      </footer>
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.body.classList.remove("is-fading");
    });
    setTimeout(() => {
      const toast = document.querySelector('.error-toast');
      if (toast) toast.classList.remove('show');
    }, 5000);
  </script>
</body>
</html>
