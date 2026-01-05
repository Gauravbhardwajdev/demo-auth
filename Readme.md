✨ Testing Website

Authentication	UI/UX	Security
✅ Full Login/Register	✨ Nebula animations	🔒 Prepared Statements
✅ Session Management	📱 Fully Responsive	✅ SQL Injection Proof
✅ Protected Routes	⏱️ Countdown UX	✅ Input Validation
✅ Forgot Password	🎨 Dark Theme	✅ XSS Protection

🛠️ Tech Stack
Frontend: HTML5 • CSS3 Grid • Vanilla JS
Backend: PHP 8.2 • Sessions
Database: MySQL 8.0
Deployment: XAMPP → AWS (Terraform)
DevOps: GitHub Actions • Docker
🚀 Quick Start (Local)

# 1. Clone repo
git clone https://github.com/YOUR_USERNAME/qa-dashboard.git
cd qa-dashboard

# 2. XAMPP Setup
# - Start Apache + MySQL
# - Import demo_auth.sql (phpMyAdmin)

# 3. Config DB
cp config/db.example.php config/db.php
# Edit: hostname, username, password, database

# 4. Visit
http://localhost/qa-dashboard/
Test Credentials:

Connect with XAMPP and Create User

🧪 User Flows
1. /index.php → Demo login ✨
   ↓ Login (test@example.com)
2. /dashboard.php → QA Dashboard
   ↓ Navbar: user@email.com [Logout]
3. /register.php → Signup + Validation
   ↓ Success → 3s Countdown → Login
4. /forgot-password.php → Email Reset Flow
5. Logout → Clean Session → Login

🔒 Security Features
✅ Prepared Statements (SQL Injection)
✅ `htmlspecialchars()` (XSS)
✅ Session Validation (Protected Routes)
✅ Input Sanitization (`trim()`, `filter_var()`)
✅ CSRF Protection Ready (Next)
✅ Rate Limiting Ready (Next)

📊 Database Schema
sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(255),
  email VARCHAR(255) UNIQUE,
  password_hash VARCHAR(255)
);

🎨 UI Components
Component	Tech	Features
Nebula Background	CSS Animations	Stars + Orbiting
Error Toasts	CSS Transitions	Auto-hide 5s
Countdown UX	Vanilla JS	Live Timer
Responsive Grid	CSS Grid	Mobile-First
Protected Navbar	PHP Sessions	Dynamic User

👨‍💻 Development
# Install dependencies (future)
composer install

# Lint PHP
php -l *.php

# Start dev server
php -S localhost:8000

🌐 Deployment
Local (XAMPP)

htdocs/demo-auth/ → http://localhost/demo-auth/
Production (AWS)

1. Dockerize → ECR
2. ECS Fargate → RDS MySQL
3. ALB + Route53 → HTTPS
4. CloudWatch → Alerts



🤝 Contributing
Fork repo

Create feature branch (git checkout -b feature/amazing-idea)

Commit changes (git commit -m 'Add some feature')

Push (git push origin feature/amazing-idea)

Open Pull Request

💼 About Me
Gaurav Bhardwaj - QA Automation → Full-Stack → DevOps Engineer

4+ years QA Experience
Selenium • TestNG • Java • Python
AWS Certified (Associate)
MCA • Google IT Automation Cert
Open to opportunities - Delhi/Noida Remote

⭐ Star this repo if helpful!