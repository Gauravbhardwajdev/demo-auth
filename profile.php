<?php
session_start();
require 'config/db.php';

// 1. CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// 2. FETCH DATA
// Get Name
$uStmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$uStmt->bind_param("i", $user_id);
$uStmt->execute();
$user = $uStmt->get_result()->fetch_assoc();
$uStmt->close();

// Get Background Details
$bgStmt = $conn->prepare("SELECT * FROM user_background WHERE user_id = ?");
$bgStmt->bind_param("i", $user_id);
$bgStmt->execute();
$bg = $bgStmt->get_result()->fetch_assoc();
$bgStmt->close();

// 3. DEFINE VARIABLES (With Fallback Text if Empty)
// This ensures the page never looks broken, even if you haven't filled out the dashboard yet.
function val($data, $key, $default) {
    return !empty($data[$key]) ? $data[$key] : $default;
}

$full_name = htmlspecialchars($user['full_name']);
$headline = htmlspecialchars(val($bg, 'headline', 'QA Professional'));
$headline_tags = val($bg, 'headline_tags', 'QA, Automation, 4+ Years');
$about_me = nl2br(htmlspecialchars(val($bg, 'about_me', 'Welcome to my portfolio.')));
$core_strengths = nl2br(htmlspecialchars(val($bg, 'core_strengths', 'Experienced in functional testing and automation.')));
$learning = nl2br(htmlspecialchars(val($bg, 'currently_learning', 'AWS, Docker, CI/CD')));
$goals = nl2br(htmlspecialchars(val($bg, 'goals', 'To become a Solution Architect.')));
$skills_tags = val($bg, 'core_strengths_tags', 'Java, Selenium, SQL, Maven');
$contact_email = htmlspecialchars(val($bg, 'contact_email', $user['email']));
$linkedin_url = htmlspecialchars(val($bg, 'linkedin_url', '#'));
$github_url = htmlspecialchars(val($bg, 'github_url', '#'));

// Helper to split comma-separated tags
function makeTags($str) {
    if (!$str) return [];
    return array_map('trim', explode(',', $str));
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Full Profile - <?= $full_name ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<style>
/* --- YOUR ORIGINAL CSS --- */
:root{
--bg:#0b1020;
--bg2:#0e1630;
--card: rgba(21, 26, 44, .72);
--card2: rgba(17, 23, 41, .72);
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
font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
background:
radial-gradient(900px 500px at 15% 10%, rgba(255,122,69,.18), transparent 55%),
radial-gradient(700px 520px at 85% 15%, rgba(99,102,241,.16), transparent 60%),
radial-gradient(900px 720px at 50% 90%, rgba(34,197,94,.10), transparent 55%),
linear-gradient(180deg, var(--bg), var(--bg2));
color: var(--text);
line-height:1.6;
}
a{ color:inherit; text-decoration:none; }
.container{ max-width:1120px; margin:0 auto; padding: 1.25rem; }
/* Top nav */
.topbar{ position: sticky; top:0; z-index: 10; backdrop-filter: blur(10px); background: rgba(11,16,32,.62); border-bottom: 1px solid var(--border); }
.topbar-inner{ max-width: 1120px; margin:0 auto; padding: .9rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; }
.brand{ display:flex; align-items:center; gap:.7rem; font-weight:600; letter-spacing:.03em; }
.dot{ width:34px; height:34px; border-radius:50%; background: radial-gradient(circle at 30% 30%, #ffb347, #ff7a45); box-shadow: 0 10px 35px rgba(255,122,69,.25); }
.navlinks{ display:flex; gap:1rem; color: var(--muted); font-size:.92rem; }
.navlinks a:hover{ color: var(--text); }
.actions{ display:flex; align-items:center; gap:.7rem; }
.pill{ border:1px solid var(--border); background: rgba(15, 23, 42, .55); padding:.35rem .7rem; border-radius:999px; font-size:.78rem; color: var(--muted); white-space: nowrap; }
.btn{ border-radius:999px; padding:.55rem 1rem; border:1px solid var(--border); background: rgba(255,255,255,.02); color: var(--text); cursor:pointer; font-size:.9rem; display:inline-flex; align-items:center; gap:.45rem; }
.btn-primary{ background: var(--accent); border-color: var(--accent); color: #0b1020; box-shadow: 0 14px 40px rgba(255,122,69,.22); }
/* Hero profile header */
.hero{ padding: 2.2rem 0 1.2rem; display:grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, .9fr); gap: 1.2rem; align-items: stretch; }
.glass{ background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); backdrop-filter: blur(10px); }
.hero-left{ padding: 1.4rem; position: relative; overflow: hidden; }
.hero-left:before{ content:""; position:absolute; inset:-40px -80px auto auto; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle at 30% 30%, rgba(255,122,69,.35), rgba(255,122,69,.0) 60%); filter: blur(2px); }
.profile-row{ display:flex; gap:1rem; align-items:center; }
.avatar{ width: 84px; height: 84px; border-radius: 24px; background: radial-gradient(circle at 20% 20%, rgba(255,255,255,.25), rgba(255,255,255,0) 55%), linear-gradient(135deg, rgba(255,122,69,.9), rgba(99,102,241,.75)); border: 1px solid var(--border); box-shadow: 0 18px 45px rgba(0,0,0,.45); position: relative; }
.avatar:after{ content:""; position:absolute; inset:10px; border-radius: 18px; border: 1px dashed rgba(255,255,255,.18); opacity:.7; }
h1{ font-size: clamp(1.6rem, 2vw + 1rem, 2.4rem); line-height:1.1; margin-bottom:.4rem; }
.headline{ color: var(--muted); font-size: .95rem; margin-bottom: .85rem; max-width: 52ch; }
.badges{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.2rem; }
.badge{ padding:.35rem .75rem; border-radius:999px; border: 1px solid var(--border); background: rgba(21,26,44,.65); font-size:.78rem; color: var(--muted); }
.badge.good{ border-color: rgba(34,197,94,.28); color: rgba(211,255,227,.9); }
.badge.warn{ border-color: rgba(245,158,11,.28); color: rgba(255,237,197,.92); }
.hero-right{ padding: 1.2rem; display:grid; gap: .9rem; }
.stat{ padding: .95rem; border-radius: 14px; border: 1px solid var(--border); background: rgba(11,16,32,.25); }
.stat .kpi{ font-size: 1.25rem; font-weight: 800; }
.stat .label{ font-size: .8rem; color: var(--muted); }
.mini-links{ display:flex; flex-wrap:wrap; gap:.5rem; }
.mini-links a{ border:1px solid var(--border); background: rgba(255,255,255,.02); padding:.45rem .7rem; border-radius: 999px; font-size:.82rem; color: var(--text); }
.mini-links a:hover{ border-color: rgba(255,122,69,.45); }
/* Sections */
.section{ padding: 1.1rem 0; }
.section-title{ display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom: .8rem; }
.section-title h2{ font-size: 1.05rem; font-weight:700; }
.section-title p{ color: var(--muted); font-size:.85rem; max-width: 60ch; }
.grid{ display:grid; grid-template-columns: repeat(12, 1fr); gap: 1rem; }
.col-6{ grid-column: span 6; } .col-4{ grid-column: span 4; } .col-8{ grid-column: span 8; } .col-12{ grid-column: span 12; }
.card{ padding: 1.1rem; }
.card h3{ font-size: .95rem; margin-bottom: .35rem; }
.muted{ color: var(--muted); font-size:.88rem; }
.skill-wrap{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.75rem; }
.skill{ border:1px solid var(--border); background: rgba(15, 23, 42, .45); padding:.35rem .65rem; border-radius: 999px; font-size:.78rem; }
.project{ display:flex; flex-direction:column; gap:.55rem; position: relative; overflow:hidden; }
.project:before{ content:""; position:absolute; inset:auto -80px -120px auto; width:260px; height:260px; border-radius:50%; background: radial-gradient(circle at 30% 30%, rgba(99,102,241,.22), rgba(99,102,241,0) 60%); filter: blur(1px); }
.project-top{ display:flex; align-items:center; justify-content:space-between; gap:.75rem; }
.tag{ font-size:.75rem; padding:.25rem .6rem; border-radius: 999px; border: 1px solid var(--border); background: rgba(255,255,255,.03); color: var(--muted); z-index: 1; }
.tag.good{ border-color: rgba(34,197,94,.30); }
.tag.warn{ border-color: rgba(245,158,11,.30); }
.project-links{ display:flex; flex-wrap:wrap; gap:.6rem; z-index: 1; }
.project-links a{ color: var(--accent); font-size:.88rem; }
.project-links a:hover{ text-decoration: underline; }
.timeline{ display:flex; flex-direction:column; gap:.7rem; margin-top:.4rem; }
.titem{ border: 1px solid var(--border); background: rgba(11,16,32,.18); border-radius: 14px; padding: .85rem; }
.titem strong{ display:block; font-size:.9rem; margin-bottom:.15rem; }
.titem span{ color: var(--muted); font-size:.82rem; }
footer{ padding: 1.5rem 0 2.25rem; color: var(--muted); font-size: .85rem; text-align:center; opacity: .95; }
@media (max-width: 980px){ .hero{ grid-template-columns: 1fr; } .col-6,.col-4,.col-8{ grid-column: span 12; } .navlinks{ display:none; } }
</style>
</head>
<body>
<div class="topbar">
<div class="topbar-inner">
<div class="brand">
<div class="dot"></div>
<span>Nebula Portal</span>
</div>
<div class="navlinks">
<a href="#about">About</a>
<a href="#skills">Skills</a>
<a href="#projects">Projects</a>
<a href="#contact">Contact</a>
</div>
<div class="actions">
<span class="pill"><?= $contact_email ?></span>
<a class="btn" href="dashboard.php">Dashboard</a>
<a class="btn btn-primary" href="#contact">Hire / Contact</a>
</div>
</div>
</div>
<div class="container">
<!-- HERO -->
<section class="hero">
<div class="glass hero-left">
<div class="profile-row">
<div class="avatar" aria-hidden="true"></div>
<div>
<!-- DYNAMIC NAME -->
<h1><?= $full_name ?></h1>
<!-- DYNAMIC HEADLINE -->
<div class="headline"><?= $headline ?></div>
<!-- DYNAMIC TAGS (Split by comma) -->
<div class="badges">
  <?php foreach(makeTags($headline_tags) as $tag): ?>
    <span class="badge good"><?= htmlspecialchars($tag) ?></span>
  <?php endforeach; ?>
</div>
</div>
</div>
<div style="margin-top:1rem;" class="muted">
<!-- DYNAMIC ABOUT ME -->
<?= $about_me ?>
</div>
<div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
<a class="btn btn-primary" href="#">Download Resume</a>
<a class="btn" href="#projects">View Projects</a>
<a class="btn" href="login.php?action=logout">Logout</a>
</div>
</div>
<div class="glass hero-right">
<div class="stat">
<div class="label">Profile completeness</div>
<div class="kpi">82%</div>
<div class="label">Aim for 90%+ for best impact.</div>
</div>
<div class="stat">
<div class="label">Projects</div>
<div class="kpi">3</div>
<div class="label">With GitHub + demo links.</div>
</div>
<div class="stat">
<div class="label">Quick links</div>
<div class="mini-links">
<?php if($github_url != '#'): ?><a href="<?= $github_url ?>" target="_blank">GitHub</a><?php endif; ?>
<?php if($linkedin_url != '#'): ?><a href="<?= $linkedin_url ?>" target="_blank">LinkedIn</a><?php endif; ?>
</div>
</div>
</div>
</section>

<!-- ABOUT / EXPERIENCE -->
<section id="about" class="section">
<div class="section-title">
<h2>About & background</h2>
<p>Use small, scannable blocks so recruiters can quickly understand your story and strengths.</p>
</div>
<div class="grid">
<div class="glass card col-8">
<h3>Professional summary</h3>
<div class="muted">
<!-- DYNAMIC CORE STRENGTHS -->
<?= $core_strengths ?>
</div>
<div style="margin-top:.85rem;" class="timeline">
<div class="titem">
<strong>Current Learning Focus</strong>
<!-- DYNAMIC LEARNING -->
<span><?= $learning ?></span>
</div>
<div class="titem">
<strong>Automation Track</strong>
<span>Selenium + POM, test suite organization, reporting, and CI-ready mindset for scalable execution.</span>
</div>
</div>
</div>
<div class="glass card col-4">
<h3>Highlights</h3>
<div class="muted">A quick “at a glance” panel.</div>
<div class="timeline">
<div class="titem">
<strong>Primary Goals</strong>
<!-- DYNAMIC GOALS -->
<span><?= $goals ?></span>
</div>
<div class="titem">
<strong>Domains</strong>
<span>Web apps • Dashboards • Data validation</span>
</div>
</div>
</div>
</div>
</section>

<!-- SKILLS -->
<section id="skills" class="section">
<div class="section-title">
<h2>Skills</h2>
<p>Keep skills grouped and easy to scan for faster evaluation.</p>
</div>
<div class="grid">
<div class="glass card col-12">
<h3>Technical skills</h3>
<div class="muted">Dynamically loaded from your dashboard.</div>
<div class="skill-wrap">
<!-- DYNAMIC SKILLS -->
<?php foreach(makeTags($skills_tags) as $skill): ?>
    <span class="skill"><?= htmlspecialchars($skill) ?></span>
<?php endforeach; ?>
</div>
</div>
</div>
</section>

<!-- PROJECTS (These are still manual/static for now) -->
<section id="projects" class="section">
<div class="section-title">
<h2>Projects</h2>
<p>Show problem → approach → stack → outcome.</p>
</div>
<div class="grid">
<article class="glass card col-6 project">
<div class="project-top"><h3>Selenium POM Framework</h3><span class="tag good">Done</span></div>
<div class="muted">Reusable Page Object Model framework with utilities.</div>
<div class="skill-wrap" style="margin-top:.5rem;"><span class="skill">Java</span><span class="skill">Selenium</span></div>
<div class="project-links"><a href="<?= $github_url ?>" target="_blank">GitHub</a></div>
</article>

<article class="glass card col-6 project">
<div class="project-top"><h3>Nebula Portfolio</h3><span class="tag good">Live</span></div>
<div class="muted">Authenticated dashboard-style portfolio system.</div>
<div class="skill-wrap" style="margin-top:.5rem;"><span class="skill">PHP</span><span class="skill">MySQL</span></div>
<div class="project-links"><a href="<?= $github_url ?>" target="_blank">GitHub</a></div>
</article>
</div>
</section>

<!-- CONTACT -->
<section id="contact" class="section">
<div class="section-title">
<h2>Contact & downloads</h2>
</div>
<div class="grid">
<div class="glass card col-6">
<h3>Contact</h3>
<div class="muted">
<!-- DYNAMIC CONTACT -->
Email: <span style="color:var(--accent);"><?= $contact_email ?></span><br/>
Location: Chandigarh, IN<br/>
Preferred roles: QA Automation Engineer / SDET </div>
<div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
<a class="btn btn-primary" href="mailto:<?= $contact_email ?>">Email now</a>
</div>
</div>
<div class="glass card col-6">
<h3>Downloads</h3>
<div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
<a class="btn btn-primary" href="#">Download resume</a>
<a class="btn" href="dashboard.php">Edit Profile</a>
</div>
</div>
</div>
</section>
<footer>
© 2026 ShowCaseFolio — Profile page
</footer>
</div>
</body>
</html>
