<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_POST['entry_id'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$entry_id = $_POST['entry_id'];

// Ensure the entry belongs to the user
$sql = "DELETE FROM learning_data WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $entry_id, $user_id);
$stmt->execute();

header("Location: dashboard.php");
exit();
?>
