<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Nebula Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/login.css" />
</head>
<body>
  <div class="stars"></div>
  <div class="nebula"></div>

  <main class="scene">
    <section class="card">
      <header class="card-header">
        <h1>Test Demo Website</h1>
        <p>Sign in to enter the system</p>
      </header>

<form class="card-form" method="POST" action="login.php">
  <div class="field">
    <label for="email">Email</label>
    <div class="field-inner">
      <span class="field-orbit"></span>
      <input
        id="email"
        name="email"
        type="email"
        placeholder="you@example.com"
        required
      />
    </div>
  </div>

  <div class="field">
    <label for="password">Password</label>
    <div class="field-inner">
      <span class="field-orbit"></span>
      <input
        id="password"
        name="password"
        type="password"
        placeholder="Enter your password"
        required
      />
    </div>
  </div>

  <button type="submit" class="btn-glow">
    <span class="btn-glow-orbit"></span>
    <span class="btn-glow-text">Launch</span>
  </button>
</form>

      <footer class="card-footer">
        <a href="forgot-password.php">Forgot password?</a>
        <a href="register.php">Create account</a>
      </footer>
    </section>
  </main>
  <script>
  // Fade in on load
  document.addEventListener("DOMContentLoaded", () => {
    document.body.classList.remove("is-fading");
  });

  // Intercept clicks on internal links for fade-out then navigate
  document.addEventListener("click", (event) => {
    const link = event.target.closest("a");
    if (!link) return;

    const url = link.getAttribute("href");
    if (!url || url.startsWith("http") || url.startsWith("#")) {
      return; // skip external links/anchors
    }

    event.preventDefault();
    document.body.classList.add("is-fading");

    setTimeout(() => {
      window.location.href = url;
    }, 300); // must match CSS transition time
  });
</script>

</body>
</html>
