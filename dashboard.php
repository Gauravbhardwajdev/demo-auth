<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$user_id = (int)$_SESSION['user_id'];
$user_email = $_SESSION['email'] ?? 'User';

$conn = new mysqli("localhost", "root", "", "demo_auth");
if ($conn->connect_error) {
  die("DB connection failed: " . $conn->connect_error);
}

function hasValue($v) { return isset($v) && trim((string)$v) !== ''; }
function safe($v) { return htmlspecialchars((string)$v); }

$stmt = $conn->prepare("INSERT IGNORE INTO user_background (user_id, contact_email) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, $user_email);
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM user_background WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bg = $stmt->get_result()->fetch_assoc();
if (!$bg) $bg = [];

$bg_has_any =
  hasValue($bg['headline'] ?? null) ||
  hasValue($bg['headline_tags'] ?? null) ||
  hasValue($bg['core_strengths'] ?? null) ||
  hasValue($bg['core_strengths_tags'] ?? null) ||
  hasValue($bg['currently_learning'] ?? null) ||
  hasValue($bg['currently_learning_tags'] ?? null) ||
  hasValue($bg['linkedin_url'] ?? null) ||
  hasValue($bg['github_url'] ?? null) ||
  hasValue($bg['about_me'] ?? null) ||
  hasValue($bg['goals'] ?? null);
?>
<!DOCTYPE html>
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
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      background: var(--bg);
      color: var(--text);
      line-height: 1.6;
    }
    a { color: inherit; text-decoration: none; }
    .page { min-height: 100vh; display: flex; flex-direction: column; }

    header { border-bottom: 1px solid var(--border); background: linear-gradient(90deg, #0b1020, #141b30); }
    .nav {
      max-width: 1120px; margin: 0 auto; padding: 1rem 1.5rem;
      display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .nav-left { display: flex; align-items: center; gap: 0.75rem; }
    .logo { width: 32px; height: 32px; border-radius: 50%; background: radial-gradient(circle at 30% 30%, #ffb347, #ff7a45); }
    .brand { font-weight: 600; letter-spacing: 0.03em; font-size: 0.95rem; }
    .nav-right { display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem; }

    .btn {
      border-radius: 999px;
      padding: 0.5rem 1rem;
      border: 1px solid transparent;
      cursor: pointer;
      font-size: 0.9rem;
      background: none;
      color: var(--text);
      display: inline-flex;
      align-items: center;
      gap: .5rem;
    }
    .btn-outline { border-color: var(--border); }
    .btn-primary { background: var(--accent); color: #0b1020; border-color: var(--accent); }

    main { flex: 1; }
    .section { padding: 2.5rem 1.5rem; }
    .section-inner { max-width: 1120px; margin: 0 auto; }
    .section-heading { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.35rem; }
    .section-subtitle { font-size: 0.9rem; color: var(--muted); max-width: 44rem; margin-bottom: 1.25rem; }

    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
    .card {
      background: var(--card-bg);
      border-radius: var(--radius);
      padding: 1rem;
      border: 1px solid var(--border);
      min-height: 120px;
    }
    .card-title {
      font-size: 0.95rem;
      font-weight: 650;
      margin-bottom: 0.35rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .5rem;
    }
    .card-text { font-size: 0.85rem; color: var(--muted); }
    .card-meta { margin-top: .75rem; display: flex; flex-wrap: wrap; gap: .5rem; }
    .pill {
      padding: 0.25rem 0.7rem;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: rgba(15, 23, 42, 0.85);
      font-size: 0.75rem;
      color: var(--muted);
    }
    .link { color: var(--accent); }
    .link:hover { text-decoration: underline; }

    .divider { height: 1px; background: var(--border); margin: 1rem 0; opacity: .8; }

    .break-url { overflow-wrap: break-word; word-wrap: break-word; }

    .form { margin-top: 1rem; display: grid; gap: .8rem; }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    label { font-size: .8rem; color: var(--muted); display: block; margin-bottom: .25rem; }
    input, textarea {
      width: 100%;
      padding: .7rem .8rem;
      border-radius: 10px;
      border: 1px solid var(--border);
      background: rgba(10, 14, 28, 0.6);
      color: var(--text);
      outline: none;
    }
    textarea { min-height: 92px; resize: vertical; }
    .help { font-size: .78rem; color: var(--muted); margin-top: .25rem; }

    footer {
      border-top: 1px solid var(--border);
      padding: 1.5rem;
      font-size: 0.8rem;
      color: var(--muted);
      text-align: center;
      background: var(--bg-alt);
    }

    @media (max-width: 900px) { .row { grid-template-columns: 1fr; } }
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

        <div class="nav-right">
          <span style="font-size: 0.9rem; color: var(--accent); margin-right: 0.25rem;">
            <?= safe($user_email) ?>
          </span>
          <a href="profile.php" class="btn btn-primary">View Profile</a>
          <a href="login.php?action=logout" class="btn btn-outline">Logout</a>
        </div>
      </div>
    </header>

    <main>
      <section id="section-background" class="section" style="background: var(--bg-alt);">
        <div class="section-inner">

          <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-bottom:.25rem;">
            <h2 class="section-heading" style="margin-bottom:0;">My background</h2>
            <button class="btn btn-outline" type="button" onclick="toggleBgEdit()">Edit fields</button>
          </div>

          <p class="section-subtitle">
            A recruiter-friendly summary: who you are, what you do, and what you want next.
          </p>

          <?php if (!$bg_has_any): ?>
            <article class="card" style="margin-bottom: 1rem;">
              <div class="card-title">No background details yet</div>
              <div class="card-text">
                Nothing is shown until you add details. Click <span style="color:var(--accent);">Edit fields</span> to start.
              </div>
            </article>
          <?php endif; ?>

          <div class="grid">
            <article class="card">
              <div class="card-title">Headline</div>
              <div class="card-text"><?= hasValue($bg['headline'] ?? null) ? safe($bg['headline']) : '' ?></div>
              <?php if (hasValue($bg['headline_tags'] ?? null)): ?>
                <div class="card-meta">
                  <?php foreach (explode(',', $bg['headline_tags']) as $t): $t=trim($t); if ($t==='') continue; ?>
                    <span class="pill"><?= safe($t) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>

            <article class="card">
              <div class="card-title">Core strengths</div>
              <div class="card-text"><?= hasValue($bg['core_strengths'] ?? null) ? safe($bg['core_strengths']) : '' ?></div>
              <?php if (hasValue($bg['core_strengths_tags'] ?? null)): ?>
                <div class="card-meta">
                  <?php foreach (explode(',', $bg['core_strengths_tags']) as $t): $t=trim($t); if ($t==='') continue; ?>
                    <span class="pill"><?= safe($t) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>

            <article class="card">
              <div class="card-title">Currently learning</div>
              <div class="card-text"><?= hasValue($bg['currently_learning'] ?? null) ? safe($bg['currently_learning']) : '' ?></div>
              <?php if (hasValue($bg['currently_learning_tags'] ?? null)): ?>
                <div class="card-meta">
                  <?php foreach (explode(',', $bg['currently_learning_tags']) as $t): $t=trim($t); if ($t==='') continue; ?>
                    <span class="pill"><?= safe($t) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </article>

            <article class="card">
              <div class="card-title">Contact</div>
              <div class="card-text">
                <?php if (hasValue($bg['contact_email'] ?? null)): ?>
                  Email: <a class="link break-url" href="mailto:<?= safe($bg['contact_email']) ?>"> <?= safe($bg['contact_email']) ?></a><br />
                  <?php endif; ?>

                <?php if (hasValue($bg['linkedin_url'] ?? null)): ?>
                  LinkedIn:  <a class="link break-url" href="<?= safe($bg['linkedin_url']) ?>" target="_blank" rel="noopener"> <?= safe($bg['linkedin_url']) ?> </a><br />
                  <?php endif; ?>

                <?php if (hasValue($bg['github_url'] ?? null)): ?>
                  GitHub:    <a class="link break-url" href="<?= safe($bg['github_url']) ?>" target="_blank" rel="noopener"> <?= safe($bg['github_url']) ?> </a>
                  <?php endif; ?>
              </div>
            </article>
          </div>

          <div class="divider"></div>

          <div class="grid">
            <article class="card">
              <div class="card-title">About me</div>
              <div class="card-text"><?= hasValue($bg['about_me'] ?? null) ? safe($bg['about_me']) : '' ?></div>
            </article>

            <article class="card">
              <div class="card-title">Goals</div>
              <div class="card-text"><?= hasValue($bg['goals'] ?? null) ? safe($bg['goals']) : '' ?></div>
            </article>
          </div>

          <div id="bgEditBox" style="display:none; margin-top: 1.25rem;">
            <article class="card">
              <div class="card-title">Edit background details</div>
              <div class="card-text">Fill only what you want to show on your profile.</div>

              <!-- IMPORTANT: absolute action -->
              <form class="form" method="post" action="/demo-auth/config/update_background.php">
                <div class="row">
                  <div>
                    <label>Headline</label>
                    <input name="headline" value="<?= safe($bg['headline'] ?? '') ?>" placeholder="Your headline..." />
                    <div class="help">Example: QA → QA Automation / DevOps</div>
                  </div>
                  <div>
                    <label>Headline tags (comma separated)</label>
                    <input name="headline_tags" value="<?= safe($bg['headline_tags'] ?? '') ?>" placeholder="4+ yrs QA, Automation, Cloud basics" />
                  </div>
                </div>

                <div class="row">
                  <div>
                    <label>Core strengths</label>
                    <textarea name="core_strengths" placeholder="Your strengths..."><?= safe($bg['core_strengths'] ?? '') ?></textarea>
                  </div>
                  <div>
                    <label>Core strength tags</label>
                    <input name="core_strengths_tags" value="<?= safe($bg['core_strengths_tags'] ?? '') ?>" placeholder="POM, Test design, Defect lifecycle" />
                  </div>
                </div>

                <div class="row">
                  <div>
                    <label>Currently learning</label>
                    <textarea name="currently_learning" placeholder="What you are learning..."><?= safe($bg['currently_learning'] ?? '') ?></textarea>
                  </div>
                  <div>
                    <label>Learning tags</label>
                    <input name="currently_learning_tags" value="<?= safe($bg['currently_learning_tags'] ?? '') ?>" placeholder="AWS, Python, CI/CD" />
                  </div>
                </div>

                <div class="row">
                  <div>
                    <label>Contact email</label>
                    <input name="contact_email" type="email" value="<?= safe($bg['contact_email'] ?? $user_email) ?>" />
                  </div>
                  <div>
                    <label>LinkedIn URL</label>
                    <input name="linkedin_url" type="url" value="<?= safe($bg['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/..." />
                  </div>
                </div>

                <div class="row">
                  <div>
                    <label>GitHub URL</label>
                    <input name="github_url" type="url" value="<?= safe($bg['github_url'] ?? '') ?>" placeholder="https://github.com/..." />
                  </div>
                  <div></div>
                </div>

                <div class="row">
                  <div>
                    <label>About me</label>
                    <textarea name="about_me" placeholder="Short professional summary..."><?= safe($bg['about_me'] ?? '') ?></textarea>
                  </div>
                  <div>
                    <label>Goals</label>
                    <textarea name="goals" placeholder="Role goals..."><?= safe($bg['goals'] ?? '') ?></textarea>
                  </div>
                </div>

                <div style="display:flex; gap:.75rem; flex-wrap:wrap;">
                  <button class="btn btn-primary" type="submit">Save</button>
                  <button class="btn btn-outline" type="button" onclick="toggleBgEdit()">Cancel</button>
                </div>
              </form>
            </article>
          </div>

        </div>
      </section>
    </main>

    <footer>
      © <?= date('Y') ?> Nebula Portal
    </footer>
  </div>

  <script>
    function toggleBgEdit() {
      const box = document.getElementById('bgEditBox');
      if (!box) return;
      box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
      if (box.style.display === 'block') box.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  </script>
</body>
</html>
