<?php
session_start();
include('config.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$learning_data = [];
$progress = 0;

// Fetch learning data
$sql = "SELECT * FROM learning_data WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $learning_data[] = $row;
}

// Calculate total hours and progress
$total_hours = 0;
foreach ($learning_data as $data) {
    $total_hours += $data['hours'];
}

if ($total_hours > 0) {
    $progress = ($total_hours / 50) * 100;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Daily Learning Tracker</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    .logout-btn, .profile-btn {
      position: absolute;
      top: 20px;
      font-size: 16px;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }
    .logout-btn {
      right: 20px;
      background-color: #f44336;
      color: white;
    }
    .logout-btn:hover {
      background-color: #d32f2f;
    }
    .profile-btn {
      right: 120px;
      background-color: #2196F3;
      color: white;
    }
    .profile-btn:hover {
      background-color: #1976D2;
    }
    .learning-box {
      background: #fff;
      padding: 25px;
      max-width: 600px;
      margin: 30px auto;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .learning-box h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .learning-box input,
    .learning-box textarea,
    .learning-box button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 2px solid #ddd;
      border-radius: 10px;
      font-size: 16px;
    }
    .learning-box button {
      background: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }
    .learning-box button:hover {
      background: #45a049;
    }
    table {
      width: 95%;
      margin: 30px auto;
      border-collapse: collapse;
      background: #fff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      text-align: center;
    }
    th {
      background: #f4f4f4;
    }
    canvas {
      max-width: 600px;
      margin: 20px auto;
      display: block;
    }
    .action-btn {
      background-color: #ff9800;
      color: white;
      border: none;
      padding: 5px 10px;
      margin: 3px;
      border-radius: 5px;
      cursor: pointer;
    }
    .delete-btn {
      background-color: #f44336;
    }
  </style>
</head>
<body>

<header>
  <h1>Welcome to Your Learning Dashboard 🎓</h1>
  <a href="profile.php" class="profile-btn">Profile</a>
  <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="learning-box">
  <h2>Add Today's Learning</h2>
  <form id="learningForm" action="save_learning.php" method="POST">
    <input type="text" name="subject" id="subject" placeholder="Enter subject or topic" required>
    <textarea name="notes" id="notes" rows="4" placeholder="Describe what you learned today..." required></textarea>
    <input type="number" name="hours" id="hours" placeholder="Hours spent" min="1" required>
    <input type="date" name="date" id="date" required>
    <button type="submit">Save Learning</button>
  </form>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').value = today;
  });
</script>

<div class="container">
  <h2 style="text-align:center;">Learning Progress</h2>
  <p style="text-align:center;">Total Hours: <?= $total_hours ?> hrs | Progress: <?= round($progress, 2) ?>%</p>
  <canvas id="progressChart"></canvas>
</div>

<script>
  var ctx = document.getElementById('progressChart').getContext('2d');
  var chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Learning Goal (50 hrs)'],
      datasets: [{
        label: 'Progress %',
        data: [<?= $progress ?>],
        backgroundColor: ['rgba(75, 192, 192, 0.6)'],
        borderColor: ['rgba(75, 192, 192, 1)'],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: { beginAtZero: true, max: 100 }
      }
    }
  });
</script>

<h2 style="text-align:center;">Your Learning History 📚</h2>
<table>
  <tr>
    <th>Date</th>
    <th>Subject</th>
    <th>Hours</th>
    <th>Notes</th>
    <th>Actions</th>
  </tr>
  <?php foreach ($learning_data as $data) : ?>
    <tr>
      <td><?= $data['date'] ?></td>
      <td><?= $data['subject'] ?></td>
      <td><?= $data['hours'] ?></td>
      <td><?= $data['notes'] ?></td>
      <td>
        <form action="delete_learning.php" method="POST" style="display:inline;" onsubmit="return confirm('Delete this entry?');">
          <input type="hidden" name="entry_id" value="<?= $data['id'] ?>">
          <button type="submit" class="action-btn delete-btn">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
