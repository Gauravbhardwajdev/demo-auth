<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Nebula Portal – Forgot Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- IMPORTANT: reuse the login CSS file -->
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <div class="stars"></div>
  <div class="nebula"></div>

  <main class="scene">
    <section class="card">
      <header class="card-header">
        <h1>Test Forgot password</h1>
        <p>Enter your email to receive a reset link</p>
      </header>

      <form class="card-form">
        <div class="field">
          <label for="email">Email</label>
          <div class="field-inner">
            <span class="field-orbit"></span>
            <input
              id="email"
              type="email"
              placeholder="you@example.com"
              required
            />
          </div>
        </div>

        <button type="submit" class="btn-glow">
          <span class="btn-glow-orbit"></span>
          <span class="btn-glow-text">Send reset link</span>
        </button>
      </form>

      <footer class="card-footer">
        <a href="login.php">Back to login</a>
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
