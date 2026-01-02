// after: $user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT IGNORE INTO user_profile (user_id) VALUES (?)");
$stmt->bind_param("i", $user_id);
$stmt->execute();
