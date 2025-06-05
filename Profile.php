<?php
session_start();
include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_sql = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('ssssi', $name, $email, $phone, $address, $user_id);
    $update_stmt->execute();

    // Refresh the user data after update
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - Daily Learning Tracker</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 20px;
    }
    header {
      text-align: center;
      padding: 20px 0;
      position: relative;
    }
    .profile-box {
      background: #fff;
      padding: 25px;
      max-width: 600px;
      margin: 30px auto;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .profile-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .profile-box input,
    .profile-box textarea,
    .profile-box button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 2px solid #ddd;
      border-radius: 10px;
      font-size: 16px;
    }
    .profile-box button {
      background: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }
    .profile-box button:hover {
      background: #45a049;
    }
    .logout-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background-color: #f44336;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 16px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #d32f2f;
    }
  </style>
</head>
<body>

<header>
  <h1>Welcome to Your Profile</h1>
  <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="profile-box">
  <h2>Edit Your Profile</h2>
  <form method="POST">
    <input type="text" name="name" id="name" value="<?= $user['username'] ?>" placeholder="Enter your username" required>
    <input type="email" name="email" id="email" value="<?= $user['email'] ?>" placeholder="Enter your email" required>
    <input type="password" name="password" id="password" value="<?= $user['password'] ?>" placeholder="Enter your password" required>
    <button type="submit">Save Changes</button>
  </form>
</div>

</body>
</html>
