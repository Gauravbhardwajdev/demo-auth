<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}
$user_email = $_SESSION['email'] ?? 'User';

/* Later you can replace these arrays with MySQL data */
$profile = [
  "name" => "Your Name",
  "headline" => "QA Analyst → QA Automation / DevOps (Portfolio)",
  "location" => "Chandigarh, IN",
  "bio" => "Detail-oriented QA professional focused on building maintainable automation frameworks, showcasing real projects with measurable outcomes, and growing cloud/DevOps fundamentals.",
  "open_to" => "QA Automation Engineer / SDET",
  "experience_years" => "4+ years",
  "status" => "Open to opportunities",
];

$links = [
  "github" => "#",
  "linkedin" => "#",
  "portfolio" => "#",
  "resume" => "#"
];

$skills = [
  "Java", "Selenium", "TestNG/JUnit", "Maven", "SQL (MySQL)",
  "API Testing", "Postman", "Git/GitHub", "HTML/CSS",
  "Bug Reporting", "Regression Testing", "POM", "CI/CD Basics", "AWS Basics"
];

$projects = [
  [
    "title" => "Selenium POM Framework",
    "tag" => "Done",
    "desc" => "Reusable Page Object Model framework with utilities and stable locators to reduce test maintenance.",
    "stack" => ["Java", "Selenium", "Maven", "TestNG/JUnit"],
    "github" => "#",
    "demo" => "#"
  ],
  [
    "title" => "API Test Collection",
    "tag" => "In Progress",
    "desc" => "API suite with environments, negative cases, and schema checks for consistent verification.",
    "stack" => ["Postman", "REST", "JSON"],
    "github" => "#",
    "demo" => "#"
  ],
  [
    "title" => "Nebula Portfolio Dashboard",
    "tag" => "Live",
    "desc" => "Authenticated dashboard-style portfolio with projects + settings to feel like a real product.",
    "stack" => ["PHP", "MySQL", "HTML/CSS"],
    "github" => "#",
    "demo" => "#"
  ],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Full Profile - Nebula Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <style>
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
    .topbar{
      position: sticky; top:0; z-index: 10;
      backdrop-filter: blur(10px);
      background: rgba(11,16,32,.62);
      border-bottom: 1px solid var(--border);
    }
    .topbar-inner{
      max-width: 1120px; margin:0 auto;
      padding: .9rem 1.25rem;
      display:flex; align-items:center; justify-content:space-between; gap:1rem;
    }
    .brand{ display:flex; align-items:center; gap:.7rem; font-weight:600; letter-spacing:.03em; }
    .dot{
      width:34px; height:34px; border-radius:50%;
      background: radial-gradient(circle at 30% 30%, #ffb347, #ff7a45);
      box-shadow: 0 10px 35px rgba(255,122,69,.25);
    }
    .navlinks{ display:flex; gap:1rem; color: var(--muted); font-size:.92rem; }
    .navlinks a:hover{ color: var(--text); }
    .actions{ display:flex; align-items:center; gap:.7rem; }
    .pill{
      border:1px solid var(--border);
      background: rgba(15, 23, 42, .55);
      padding:.35rem .7rem;
      border-radius:999px;
      font-size:.78rem;
      color: var(--muted);
      white-space: nowrap;
    }
    .btn{
      border-radius:999px;
      padding:.55rem 1rem;
      border:1px solid var(--border);
      background: rgba(255,255,255,.02);
      color: var(--text);
      cursor:pointer;
      font-size:.9rem;
      display:inline-flex; align-items:center; gap:.45rem;
    }
    .btn-primary{
      background: var(--accent);
      border-color: var(--accent);
      color: #0b1020;
      box-shadow: 0 14px 40px rgba(255,122,69,.22);
    }

    /* Hero profile header */
    .hero{
      padding: 2.2rem 0 1.2rem;
      display:grid;
      grid-template-columns: minmax(0, 1.4fr) minmax(0, .9fr);
      gap: 1.2rem;
      align-items: stretch;
    }
    .glass{
      background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      backdrop-filter: blur(10px);
    }
    .hero-left{ padding: 1.4rem; position: relative; overflow: hidden; }
    .hero-left:before{
      content:"";
      position:absolute; inset:-40px -80px auto auto;
      width: 260px; height: 260px; border-radius: 50%;
      background: radial-gradient(circle at 30% 30%, rgba(255,122,69,.35), rgba(255,122,69,.0) 60%);
      filter: blur(2px);
    }

    .profile-row{ display:flex; gap:1rem; align-items:center; }
    .avatar{
      width: 84px; height: 84px; border-radius: 24px;
      background:
        radial-gradient(circle at 20% 20%, rgba(255,255,255,.25), rgba(255,255,255,0) 55%),
        linear-gradient(135deg, rgba(255,122,69,.9), rgba(99,102,241,.75));
      border: 1px solid var(--border);
      box-shadow: 0 18px 45px rgba(0,0,0,.45);
      position: relative;
    }
    .avatar:after{
      content:"";
      position:absolute; inset:10px;
      border-radius: 18px;
      border: 1px dashed rgba(255,255,255,.18);
      opacity:.7;
    }

    h1{
      font-size: clamp(1.6rem, 2vw + 1rem, 2.4rem);
      line-height:1.1;
      margin-bottom:.4rem;
    }
    .headline{ color: var(--muted); font-size: .95rem; margin-bottom: .85rem; max-width: 52ch; }
    .badges{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.2rem; }
    .badge{
      padding:.35rem .75rem;
      border-radius:999px;
      border: 1px solid var(--border);
      background: rgba(21,26,44,.65);
      font-size:.78rem;
      color: var(--muted);
    }
    .badge.good{ border-color: rgba(34,197,94,.28); color: rgba(211,255,227,.9); }
    .badge.warn{ border-color: rgba(245,158,11,.28); color: rgba(255,237,197,.92); }

    .hero-right{ padding: 1.2rem; display:grid; gap: .9rem; }
    .stat{
      padding: .95rem;
      border-radius: 14px;
      border: 1px solid var(--border);
      background: rgba(11,16,32,.25);
    }
    .stat .kpi{ font-size: 1.25rem; font-weight: 800; }
    .stat .label{ font-size: .8rem; color: var(--muted); }
    .mini-links{ display:flex; flex-wrap:wrap; gap:.5rem; }
    .mini-links a{
      border:1px solid var(--border);
      background: rgba(255,255,255,.02);
      padding:.45rem .7rem;
      border-radius: 999px;
      font-size:.82rem;
      color: var(--text);
    }
    .mini-links a:hover{ border-color: rgba(255,122,69,.45); }

    /* Sections */
    .section{ padding: 1.1rem 0; }
    .section-title{
      display:flex; align-items:flex-end; justify-content:space-between; gap:1rem;
      margin-bottom: .8rem;
    }
    .section-title h2{ font-size: 1.05rem; font-weight:700; }
    .section-title p{ color: var(--muted); font-size:.85rem; max-width: 60ch; }

    .grid{
      display:grid;
      grid-template-columns: repeat(12, 1fr);
      gap: 1rem;
    }
    .col-6{ grid-column: span 6; }
    .col-4{ grid-column: span 4; }
    .col-8{ grid-column: span 8; }
    .col-12{ grid-column: span 12; }

    .card{ padding: 1.1rem; }
    .card h3{ font-size: .95rem; margin-bottom: .35rem; }
    .muted{ color: var(--muted); font-size:.88rem; }

    .skill-wrap{ display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.75rem; }
    .skill{
      border:1px solid var(--border);
      background: rgba(15, 23, 42, .45);
      padding:.35rem .65rem;
      border-radius: 999px;
      font-size:.78rem;
    }

    .project{
      display:flex; flex-direction:column; gap:.55rem;
      position: relative;
      overflow:hidden;
    }
    .project:before{
      content:"";
      position:absolute; inset:auto -80px -120px auto;
      width:260px; height:260px; border-radius:50%;
      background: radial-gradient(circle at 30% 30%, rgba(99,102,241,.22), rgba(99,102,241,0) 60%);
      filter: blur(1px);
    }
    .project-top{ display:flex; align-items:center; justify-content:space-between; gap:.75rem; }
    .tag{
      font-size:.75rem;
      padding:.25rem .6rem;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: rgba(255,255,255,.03);
      color: var(--muted);
      z-index: 1;
    }
    .tag.good{ border-color: rgba(34,197,94,.30); }
    .tag.warn{ border-color: rgba(245,158,11,.30); }

    .project-links{ display:flex; flex-wrap:wrap; gap:.6rem; z-index: 1; }
    .project-links a{ color: var(--accent); font-size:.88rem; }
    .project-links a:hover{ text-decoration: underline; }

    .timeline{ display:flex; flex-direction:column; gap:.7rem; margin-top:.4rem; }
    .titem{
      border: 1px solid var(--border);
      background: rgba(11,16,32,.18);
      border-radius: 14px;
      padding: .85rem;
    }
    .titem strong{ display:block; font-size:.9rem; margin-bottom:.15rem; }
    .titem span{ color: var(--muted); font-size:.82rem; }

    footer{
      padding: 1.5rem 0 2.25rem;
      color: var(--muted);
      font-size: .85rem;
      text-align:center;
      opacity: .95;
    }

    @media (max-width: 980px){
      .hero{ grid-template-columns: 1fr; }
      .col-6,.col-4,.col-8{ grid-column: span 12; }
      .navlinks{ display:none; }
    }
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
        <span class="pill"><?= htmlspecialchars($user_email) ?></span>
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
            <h1><?= htmlspecialchars($profile["name"]) ?></h1>
            <div class="headline"><?= htmlspecialchars($profile["headline"]) ?> • <?= htmlspecialchars($profile["location"]) ?></div>
            <div class="badges">
              <span class="badge good"><?= htmlspecialchars($profile["status"]) ?></span>
              <span class="badge warn">Open to: <?= htmlspecialchars($profile["open_to"]) ?></span>
              <span class="badge">Experience: <?= htmlspecialchars($profile["experience_years"]) ?></span>
            </div>
          </div>
        </div>

        <div style="margin-top:1rem;" class="muted">
          <?= htmlspecialchars($profile["bio"]) ?>
        </div>

        <div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
          <a class="btn btn-primary" href="<?= htmlspecialchars($links["resume"]) ?>">Download Resume</a>
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
          <div class="kpi"><?= count($projects) ?></div>
          <div class="label">With GitHub + demo links.</div>
        </div>
        <div class="stat">
          <div class="label">Quick links</div>
          <div class="mini-links">
            <a href="<?= htmlspecialchars($links["github"]) ?>" target="_blank" rel="noopener">GitHub</a>
            <a href="<?= htmlspecialchars($links["linkedin"]) ?>" target="_blank" rel="noopener">LinkedIn</a>
            <a href="<?= htmlspecialchars($links["portfolio"]) ?>" target="_blank" rel="noopener">Website</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ABOUT / EXPERIENCE -->
    <section id="about" class="section">
      <div class="section-title">
        <h2>About & background</h2>
        <p>Use small, scannable blocks so recruiters can quickly understand your story and strengths. [web:1]</p>
      </div>

      <div class="grid">
        <div class="glass card col-8">
          <h3>Professional summary</h3>
          <div class="muted">
            QA professional experienced in functional and regression testing, now focused on automation-first portfolio projects.
            Comfortable with structured test design, defect lifecycle, and building reusable automation components.
          </div>

          <div style="margin-top:.85rem;" class="timeline">
            <div class="titem">
              <strong>Manual QA Experience</strong>
              <span>Functional testing, regression cycles, test case writing, UAT support, and defect reporting.</span>
            </div>
            <div class="titem">
              <strong>Automation Track</strong>
              <span>Selenium + POM, test suite organization, reporting, and CI-ready mindset for scalable execution.</span>
            </div>
            <div class="titem">
              <strong>Cloud/DevOps Basics</strong>
              <span>Learning AWS fundamentals and pipeline concepts to support automated testing workflows.</span>
            </div>
          </div>
        </div>

        <div class="glass card col-4">
          <h3>Highlights</h3>
          <div class="muted">A quick “at a glance” panel.</div>
          <div class="timeline">
            <div class="titem">
              <strong>Primary role</strong>
              <span>QA Automation / SDET (target)</span>
            </div>
            <div class="titem">
              <strong>Domains</strong>
              <span>Web apps • Dashboards • Data validation</span>
            </div>
            <div class="titem">
              <strong>Strength</strong>
              <span>Turning manual flows into stable automation</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SKILLS -->
    <section id="skills" class="section">
      <div class="section-title">
        <h2>Skills</h2>
        <p>Keep skills grouped and easy to scan for faster evaluation. [web:1]</p>
      </div>

      <div class="grid">
        <div class="glass card col-12">
          <h3>Technical skills</h3>
          <div class="muted">Add/remove skills anytime from your dashboard later (MySQL CRUD).</div>
          <div class="skill-wrap">
            <?php foreach ($skills as $s): ?>
              <span class="skill"><?= htmlspecialchars($s) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <!-- PROJECTS -->
    <section id="projects" class="section">
      <div class="section-title">
        <h2>Projects</h2>
        <p>Show problem → approach → stack → outcome, and always include links. [web:3]</p>
      </div>

      <div class="grid">
        <?php foreach ($projects as $p): ?>
          <article class="glass card col-6 project">
            <div class="project-top">
              <h3><?= htmlspecialchars($p["title"]) ?></h3>
              <?php
                $tagClass = "tag";
                if (stripos($p["tag"], "done") !== false || stripos($p["tag"], "live") !== false) $tagClass .= " good";
                if (stripos($p["tag"], "progress") !== false) $tagClass .= " warn";
              ?>
              <span class="<?= $tagClass ?>"><?= htmlspecialchars($p["tag"]) ?></span>
            </div>
            <div class="muted"><?= htmlspecialchars($p["desc"]) ?></div>
            <div class="skill-wrap" style="margin-top:.5rem;">
              <?php foreach ($p["stack"] as $st): ?>
                <span class="skill"><?= htmlspecialchars($st) ?></span>
              <?php endforeach; ?>
            </div>
            <div class="project-links">
              <a href="<?= htmlspecialchars($p["github"]) ?>" target="_blank" rel="noopener">GitHub</a>
              <a href="<?= htmlspecialchars($p["demo"]) ?>" target="_blank" rel="noopener">Live Demo</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- CONTACT / DOWNLOADS -->
    <section id="contact" class="section">
      <div class="section-title">
        <h2>Contact & downloads</h2>
        <p>Clear CTA buttons (“Contact”, “Resume”) should be easy to find near the top and again at the end. [web:57]</p>
      </div>

      <div class="grid">
        <div class="glass card col-6">
          <h3>Contact</h3>
          <div class="muted">
            Email: <span style="color:var(--accent);"><?= htmlspecialchars($user_email) ?></span><br/>
            Location: <?= htmlspecialchars($profile["location"]) ?><br/>
            Preferred roles: <?= htmlspecialchars($profile["open_to"]) ?>
          </div>

          <div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
            <a class="btn btn-primary" href="mailto:<?= htmlspecialchars($user_email) ?>">Email now</a>
            <a class="btn" href="<?= htmlspecialchars($links["linkedin"]) ?>" target="_blank" rel="noopener">LinkedIn</a>
            <a class="btn" href="<?= htmlspecialchars($links["github"]) ?>" target="_blank" rel="noopener">GitHub</a>
          </div>
        </div>

        <div class="glass card col-6">
          <h3>Downloads</h3>
          <div class="muted">Keep these updated from your dashboard for consistency across the site.</div>
          <div style="margin-top:1rem; display:flex; gap:.7rem; flex-wrap:wrap;">
            <a class="btn btn-primary" href="<?= htmlspecialchars($links["resume"]) ?>">Download resume</a>
            <a class="btn" href="<?= htmlspecialchars($links["portfolio"]) ?>" target="_blank" rel="noopener">Portfolio home</a>
            <a class="btn" href="dashboard.php#section-settings">Account settings</a>
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
