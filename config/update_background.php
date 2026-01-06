<?php
session_start();
require 'config/db.php';

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User Basic Info (Name)
$uStmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$uStmt->bind_param("i", $user_id);
$uStmt->execute();
$user = $uStmt->get_result()->fetch_assoc();

// 3. Fetch Background Info (Headline, About, etc)
$bgStmt = $conn->prepare("SELECT * FROM user_background WHERE user_id = ?");
$bgStmt->bind_param("i", $user_id);
$bgStmt->execute();
$bg = $bgStmt->get_result()->fetch_assoc();

// Helper to prevent crashes if data is empty
$headline = $bg['headline'] ?? 'QA Professional';
$headline_tags = $bg['headline_tags'] ?? 'QA, Automation';
$about = $bg['about_me'] ?? 'Welcome to my portfolio. I am a passionate QA engineer.';
$goals = $bg['goals'] ?? 'Seeking QA Automation roles.';
$contact_email = $bg['contact_email'] ?? $user['email'];
$skills_tags = $bg['core_strengths_tags'] ?? 'Java, Selenium, MySQL';
$github = $bg['github_url'] ?? '#';
$linkedin = $bg['linkedin_url'] ?? '#';

// Helper function to make tags into an array
function makeTags($str) {
    if (!$str) return [];
    return array_map('trim', explode(',', $str));
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Full Profile - <?= htmlspecialchars($user['full_name']) ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
/* --- YOUR EXISTING CSS --- */
:root{
--bg:#0b1020;
--bg2:#0e1630;
--card: rgba(21, 26, 44, .72);
--text:#f5f5f7;
--muted:#a0aec0;
--border: rgba(255,255,255,.08);
--accent:#ff7a45;
--good:#22c55e;
--warn:#f59e0b;
--shadow: 0 18px 60px rgba(0,0,0,.55);
--radius: 16px;
}
*{ box-sizing:border-box; margin:0; padding:0; }
body{
font-family: system-ui,-apple-system,sans-serif;
background:
radial-gradient(900px 500px at 15% 10%, rgba(255,122,69,.18), transparent 55%),
linear-gradient(180deg, var(--bg), var(--bg2));
color: var(--text);
line-height:1.6;
min-height: 100vh;
}
a{ color:inherit; text-decoration:none; }
.container{ max-width:1120px; margin:0 auto; padding: 1.25rem; }

/* Top nav */
.topbar{ position: sticky; top:0; z-index: 10; backdrop-filter: blur(10px); background: rgba(11,16,32,.62); border-bottom: 1px solid var(--border); }
.topbar-inner{ max-width: 1120px; margin:0 auto; padding: .9rem 1.25rem; display:flex; align-items:center; justify-content:space-between; }
.brand{ display:flex; align-items:center; gap:.7rem; font-weight:600; }
.dot{ width:34px; height:34px; border-radius:50%; background: radial-gradient(circle at 30% 30%, #ffb347, #ff7a45); }
.btn{ border-radius:999px; padding:.55rem 1rem; border:1px solid var(--border); background: rgba(255,255,255,.02); color: var(--text); display:inline-flex; align-items:center; gap:.45rem; }
.btn-primary{ background: var(--accent); border-color: var(--accent); color: #0b1020; }

/* Hero */
.hero{ padding: 2.2rem 0 1.2rem; display:grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, .9fr); gap: 1.2rem; }
.glass{ background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)); border: 1px solid var(--border); border-radius: var(--radius); backdrop-filter: blur(10px); }
.hero-left{ padding: 1.4rem; position: relative; overflow: hidden; }
.profile-row{ display:flex; gap:1rem; align-items:center; }
.avatar{ width: 84px; height: 84px; border-radius: 24px; background: linear-gradient(135deg, rgba(255,122,69,.9), rgba(99,102,241,.75)); }
h1{ font-size: clamp(1.6rem, 2vw + 1rem, 2.4rem); line-height:1.1; margin-bottom:.4rem; }
.headline{ color: var(--muted); font-size: .95rem; margin-bottom: .85rem; }
.badges{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.2rem; }
.badge{ padding:.35rem .75rem; border-radius:999px; border: 1px solid var(--border); background: rgba(21,26,44,.65); font-size:.78rem; color: var(--muted); }
.badge.good{ border-color: rgba(34,197,94,.28); color: rgba(211,255,227,.9); }

/* Sections */
.section{ padding: 1.1rem 0; }
.section-title h2{ font-size: 1.05rem; font-weight:700; }
.grid{ display:grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
.col-6{ grid-column: span 6; } .col-4{ grid-column: span 4; } .col-8{ grid-column: span 8; } .col-12{ grid-column: span 12; }
.card{ padding: 1.1rem; }
.muted{ color: var(--muted); font-size:.88rem; }
.skill-wrap{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.75rem; }
.skill{ border:1px solid var(--border); background: rgba(15, 23, 42, .45); padding:.35rem .65rem; border-radius: 999px; font-size:.78rem; }

@media (max-width: 980px){ .hero{ grid-template-columns: 1fr; } .col-6,.col-4,.col-8{ grid-column: span 12; } }
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-inner">
    <div class="brand">
      <div class="dot"></div>
      <span>Nebula Portal</span>
    </div>
    <div class="actions">
      <a class="btn" href="dashboard.php">Edit Profile</a>
      <a class="btn btn-primary" href="mailto:<?= htmlspecialchars($contact_email) ?>">Hire Me</a>
    </div>
  </div>
</div>

<div class="container">
  <!-- HERO -->
  <section class="hero">
    <div class="glass hero-left">
      <div class="profile-row">
        <div class="avatar"></div>
        <div>
          <!-- DYNAMIC NAME -->
          <h1><?= htmlspecialchars($user['full_name']) ?></h1>
          
          <!-- DYNAMIC HEADLINE -->
          <div class="headline"><?= htmlspecialchars($headline) ?></div>
          
          <div class="badges">
            <!-- DYNAMIC HEADLINE TAGS -->
            <?php foreach(makeTags($headline_tags) as $tag): ?>
                <span class="badge good"><?= htmlspecialchars($tag) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      
      <!-- DYNAMIC GOALS -->
      <div style="margin-top:1rem;" class="muted">
        <?= nl2br(htmlspecialchars($goals)) ?>
      </div>

      <div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
        <a class="btn btn-primary" href="#">Download Resume</a>
        <a class="btn" href="login.php?action=logout">Logout</a>
      </div>
    </div>

    <div class="glass hero-right">
       <div class="card">
         <h3 class="muted">Quick Links</h3>
         <div style="margin-top:10px; display:flex; gap:10px;">
            <?php if($github): ?><a class="btn" href="<?= htmlspecialchars($github) ?>" target="_blank">GitHub</a><?php endif; ?>
            <?php if($linkedin): ?><a class="btn" href="<?= htmlspecialchars($linkedin) ?>" target="_blank">LinkedIn</a><?php endif; ?>
         </div>
       </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section id="about" class="section">
    <div class="grid">
      <div class="glass card col-8">
        <h3>About Me</h3>
        <!-- DYNAMIC ABOUT ME -->
        <div class="muted">
          <?= nl2br(htmlspecialchars($about)) ?>
        </div>
      </div>
      <div class="glass card col-4">
        <h3>Contact</h3>
        <div class="muted">
            Email: <span style="color:var(--accent);"><?= htmlspecialchars($contact_email) ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- SKILLS -->
  <section id="skills" class="section">
    <div class="grid">
      <div class="glass card col-12">
        <h3>Core Strengths & Skills</h3>
        <div class="skill-wrap">
          <!-- DYNAMIC SKILLS FROM TAGS -->
          <?php foreach(makeTags($skills_tags) as $skill): ?>
             <span class="skill"><?= htmlspecialchars($skill) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <footer>
    © <?= date('Y') ?> Nebula Portal — Full Profile page
  </footer>
</div>
</body>
</html>
