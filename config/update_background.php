<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$user_id = (int)$_SESSION['user_id'];
$user_email = $_SESSION['email'] ?? '';

$conn = new mysqli("localhost", "root", "", "demo_auth");
if ($conn->connect_error) {
  die("DB connection failed: " . $conn->connect_error);
}

function post($key) {
  return isset($_POST[$key]) ? trim((string)$_POST[$key]) : null;
}

$headline = post('headline');
$headline_tags = post('headline_tags');
$core_strengths = post('core_strengths');
$core_strengths_tags = post('core_strengths_tags');
$currently_learning = post('currently_learning');
$currently_learning_tags = post('currently_learning_tags');
$contact_email = post('contact_email') ?: $user_email;
$linkedin_url = post('linkedin_url');
$github_url = post('github_url');
$about_me = post('about_me');
$goals = post('goals');

$stmt = $conn->prepare("
  UPDATE user_background
  SET headline=?, headline_tags=?,
      core_strengths=?, core_strengths_tags=?,
      currently_learning=?, currently_learning_tags=?,
      contact_email=?, linkedin_url=?, github_url=?,
      about_me=?, goals=?
  WHERE user_id=?
");
$stmt->bind_param(
  "sssssssssssi",
  $headline, $headline_tags,
  $core_strengths, $core_strengths_tags,
  $currently_learning, $currently_learning_tags,
  $contact_email, $linkedin_url, $github_url,
  $about_me, $goals,
  $user_id
);
$stmt->execute();

/* PRG redirect: dashboard will fetch fresh data after redirect */
header("Location: /demo-auth/dashboard.php#section-background");
exit;
