<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$user_email = $_SESSION['email'] ?? 'User';
?>
<!DOCTYPE html>  <!-- ✅ ONLY ONE DOCTYPE -->
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Nebula Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

<style>
    :root {
      --bg: #0b1020;
      --bg-alt: #111729;
      --accent: #ff7a45;
      --text: #f5f5f7;
      --muted: #a0aec0;
      --card-bg: #151a2c;
      --border: #232b3d;
      --radius: 10px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI",
        sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
    }

    a {
      color: inherit;
      text-decoration: none;
    }

    .page {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    header {
      border-bottom: 1px solid var(--border);
      background: linear-gradient(90deg, #0b1020, #141b30);
    }

    .nav {
      max-width: 1120px;
      margin: 0 auto;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .nav-left {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .logo {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: radial-gradient(circle at 30% 30%, #ffb347, #ff7a45);
    }

    .brand {
      font-weight: 600;
      letter-spacing: 0.03em;
      font-size: 0.95rem;
    }

    .nav-links {
      display: flex;
      gap: 1.25rem;
      font-size: 0.9rem;
      color: var(--muted);
    }

    .nav-links a:hover {
      color: var(--text);
    }

    .nav-right {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.9rem;
    }

    .btn {
      border-radius: 999px;
      padding: 0.5rem 1rem;
      border: 1px solid transparent;
      cursor: pointer;
      font-size: 0.9rem;
      background: none;
      color: var(--text);
    }

    .btn-outline {
      border-color: var(--border);
    }

    .btn-primary {
      background: var(--accent);
      color: #0b1020;
      border-color: var(--accent);
    }

    main {
      flex: 1;
    }

    .section {
      padding: 3rem 1.5rem;
    }

    .section-inner {
      max-width: 1120px;
      margin: 0 auto;
    }

    .hero {
      padding-top: 4rem;
      padding-bottom: 4rem;
      display: grid;
      grid-template-columns: minmax(0, 1.5fr) minmax(0, 1fr);
      gap: 2.5rem;
      align-items: center;
    }

    .eyebrow {
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.14em;
      color: var(--accent);
      margin-bottom: 0.75rem;
    }

    .hero-title {
      font-size: clamp(2rem, 3vw + 1rem, 3rem);
      line-height: 1.1;
      margin-bottom: 1rem;
    }

    .hero-subtitle {
      font-size: 0.95rem;
      color: var(--muted);
      max-width: 32rem;
      margin-bottom: 1.75rem;
    }

    .hero-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    .hero-meta {
      font-size: 0.8rem;
      color: var(--muted);
    }

    .hero-card {
      background: radial-gradient(circle at top left, #252f45, #15192b);
      border-radius: 18px;
      border: 1px solid var(--border);
      padding: 1.5rem;
      color: var(--text);
      box-shadow: 0 18px 45px rgba(0, 0, 0, 0.5);
    }

    .hero-card-title {
      font-size: 0.9rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .hero-card-body {
      font-size: 0.8rem;
      color: var(--muted);
      margin-bottom: 1rem;
    }

    .pill-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.35rem;
      font-size: 0.75rem;
    }

    .pill {
      padding: 0.25rem 0.7rem;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: rgba(15, 23, 42, 0.85);
    }

    .section-heading {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    .section-subtitle {
      font-size: 0.9rem;
      color: var(--muted);
      max-width: 30rem;
      margin-bottom: 1.5rem;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
    }

    .card {
      background: var(--card-bg);
      border-radius: var(--radius);
      padding: 1rem;
      border: 1px solid var(--border);
      min-height: 120px;
    }

    .card-title {
      font-size: 0.95rem;
      font-weight: 600;
      margin-bottom: 0.35rem;
    }

    .card-text {
      font-size: 0.85rem;
      color: var(--muted);
    }

    footer {
      border-top: 1px solid var(--border);
      padding: 1.5rem;
      font-size: 0.8rem;
      color: var(--muted);
      text-align: center;
      background: var(--bg-alt);
    }

    @media (max-width: 768px) {
      .hero {
        grid-template-columns: 1fr;
      }

      .nav-links {
        display: none;
      }

      .nav-right {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="page">
    <header>
      <div class="nav">
       <div class="nav-left">
    <div class="logo"></div>
    <div class="brand">Nebula Portal</div>
  </div>
  <nav class="nav-links">
    <a href="#section-uses">Projects</a>
    <a href="#section-features">Skills</a>
    <a href="#section-showcase">Analytics</a>
  </nav>
  <div class="nav-right">
    <!-- ✅ USER EMAIL + LOGOUT BUTTON -->
    <span style="font-size: 0.9rem; color: var(--accent); margin-right: 1rem;"><?= htmlspecialchars($user_email) ?></span>
          <a href="login.php?action=logout" class="btn btn-outline">Logout</a>
        </div>
      </div>

    </header>

    <main>
      <!-- HERO -->
      <section class="section">
        <div class="section-inner hero">
          <div>
            <div class="eyebrow">Tagline</div>
            <h1 class="hero-title">Main headline goes here.</h1>
            <p class="hero-subtitle">
              Short description for your product, portfolio, or service.
              Replace this text with your own copy.
            </p>
            <div class="hero-actions">
              <button class="btn btn-primary">Primary action</button>
              <button class="btn btn-outline">Secondary action</button>
            </div>
            <div class="hero-meta">
              Add any small note here, such as social proof, a metric, or a short
              statement.
            </div>
          </div>

          <aside class="hero-card">
            <div class="hero-card-title">Summary / Highlight Area</div>
            <div class="hero-card-body">
              Use this space for a quick summary, value prop, or any key info
              you want to highlight.
            </div>
            <div class="pill-row">
              <span class="pill">Placeholder 1</span>
              <span class="pill">Placeholder 2</span>
              <span class="pill">Placeholder 3</span>
            </div>
          </aside>
        </div>
      </section>

      <!-- SECTION 1 -->
      <section id="section-uses" class="section" style="background: var(--bg-alt);">
        <div class="section-inner">
          <h2 class="section-heading">Section One Title</h2>
          <p class="section-subtitle">
            Use this area to describe a group of use-cases, services, or
            features. All text here is placeholder.
          </p>
          <div class="grid">
            <article class="card">
              <div class="card-title">Item Title 1</div>
              <div class="card-text">
                Replace this placeholder text with your own description.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Item Title 2</div>
              <div class="card-text">
                Replace this placeholder text with your own description.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Item Title 3</div>
              <div class="card-text">
                Replace this placeholder text with your own description.
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- SECTION 2 -->
      <section id="section-features" class="section">
        <div class="section-inner">
          <h2 class="section-heading">Section Two Title</h2>
          <p class="section-subtitle">
            Another area for content such as features, benefits, or product
            modules.
          </p>
          <div class="grid">
            <article class="card">
              <div class="card-title">Feature A</div>
              <div class="card-text">
                Short placeholder description of a feature or capability.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Feature B</div>
              <div class="card-text">
                Short placeholder description of a feature or capability.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Feature C</div>
              <div class="card-text">
                Short placeholder description of a feature or capability.
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- SECTION 3 -->
      <section id="section-showcase" class="section" style="background: var(--bg-alt);">
        <div class="section-inner">
          <h2 class="section-heading">Section Three Title</h2>
          <p class="section-subtitle">
            Use this section for testimonials, portfolio items, stats,
            or anything you want.
          </p>
          <div class="grid">
            <article class="card">
              <div class="card-title">Block 1</div>
              <div class="card-text">
                Placeholder content. Customize as needed.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Block 2</div>
              <div class="card-text">
                Placeholder content. Customize as needed.
              </div>
            </article>
            <article class="card">
              <div class="card-title">Block 3</div>
              <div class="card-text">
                Placeholder content. Customize as needed.
              </div>
            </article>
          </div>
        </div>
      </section>
    </main>

    <footer>
      © Your Name – Replace this footer text.
    </footer>
  </div>
</body>
</html>
