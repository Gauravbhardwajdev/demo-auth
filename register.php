<?php
session_start();
require 'config/db.php';

$error = '';
$success = '';
$redirectTimer = 3; // Countdown seconds

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $pass = $_POST["password"] ?? "";
    $confirm = $_POST["confirm_password"] ?? "";

    if (empty($name) || empty($email) || empty($pass) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($pass !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($pass) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // TASK 2: Check duplicate email FIRST
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $pass);
            
            if ($stmt->execute()) {
                $success = "Account created successfully!";
                $redirectTimer = 3; // Start countdown
            } else {
                $error = "Registration failed. Try again.";
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create Account - Nebula Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/login.css" />
  <style>
    .error-toast {
      position: fixed; top: 20px; right: 20px; 
      background: #ef4444; color: white; 
      padding: 1rem 1.5rem; border-radius: 12px; 
      box-shadow: 0 10px 30px rgba(239,68,68,0.4);
      z-index: 1000; font-size: 0.9rem;
      transform: translateX(400px); transition: all 0.3s ease;
    }
    .error-toast.show { transform: translateX(0); }
    
    .success-toast {
      position: fixed; top: 20px; right: 20px; 
      background: #10b981; color: white; 
      padding: 1rem 1.5rem; border-radius: 12px; 
      box-shadow: 0 10px 30px rgba(16,185,129,0.4);
      z-index: 1000; font-size: 0.9rem;
      transform: translateX(400px); transition: all 0.3s ease;
    }
    .success-toast.show { transform: translateX(0); }
    
    .countdown {
      color: #10b981;
      font-weight: 600;
      font-size: 1.1rem;
    }
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
        <h1>Create Account</h1>
        <p>Join Nebula Portal</p>
      </header>

      <?php if ($success): ?>
        <!-- TASK 1: COUNTDOWN SUCCESS MESSAGE -->
        <div style="text-align: center; padding: 2rem; background: rgba(16,185,129,0.1); border-radius: 12px; border: 1px solid rgba(16,185,129,0.3);">
          <h2 style="color: #10b981; margin-bottom: 1rem;">✅ <?= htmlspecialchars($success) ?></h2>
          <p style="color: var(--muted); margin-bottom: 1rem;">Redirecting to login page in <span class="countdown" id="countdown"><?= $redirectTimer ?></span> seconds...</p>
          <a href="index.php" class="btn" style="background: #10b981; color: white; padding: 0.75rem 2rem; border-radius: 999px; font-weight: 600;">Go to Login Now</a>
        </div>
      <?php else: ?>
        <!-- NORMAL FORM -->
        <form class="card-form" method="POST" action="register.php">
          <div class="field">
            <label for="name">Full Name</label>
            <div class="field-inner">
              <span class="field-orbit"></span>
              <input id="name" name="full_name" type="text" 
                     placeholder="John Doe" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>" required />
            </div>
          </div>

          <div class="field">
            <label for="email">Email</label>
            <div class="field-inner">
              <span class="field-orbit"></span>
              <input id="email" name="email" type="email" 
                     placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required />
            </div>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <div class="field-inner">
              <span class="field-orbit"></span>
              <input id="password" name="password" type="password" 
                     minlength="8" placeholder="At least 8 characters" required />
            </div>
          </div>

          <div class="field">
            <label for="confirm">Confirm Password</label>
            <div class="field-inner">
              <span class="field-orbit"></span>
              <input id="confirm" name="confirm_password" type="password" 
                     minlength="8" placeholder="Re-enter password" required />
            </div>
          </div>

          <button type="submit" class="btn-glow">
            <span class="btn-glow-orbit"></span>
            <span class="btn-glow-text">Create Account</span>
          </button>
        </form>
      <?php endif; ?>

      <footer class="card-footer">
        <a href="index.php">Back to login</a>
      </footer>
    </section>
  </main>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.body.classList.remove("is-fading");
      
      // Hide toasts after 5s
      setTimeout(() => {
        const toast = document.querySelector('.error-toast, .success-toast');
        if (toast) toast.classList.remove('show');
      }, 5000);
      
      //COUNTDOWN + AUTO REDIRECT
      const countdownEl = document.getElementById('countdown');
      if (countdownEl) {
        let timeLeft = parseInt(countdownEl.textContent);
        const timer = setInterval(() => {
          timeLeft--;
          countdownEl.textContent = timeLeft;
          
          if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = 'index.php';
          }
        }, 1000);
      }
    });
  </script>
</body>
</html>