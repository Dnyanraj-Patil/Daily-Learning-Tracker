<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $hours = $_POST['hours'];
    $notes = $_POST['notes'];
    $date = date('Y-m-d');

    $sql = "INSERT INTO learning_data (user_id, date, subject, hours, notes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issis', $user_id, $date, $subject, $hours, $notes);
    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error saving learning data!";
    }
}
?>
