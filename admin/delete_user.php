<?php
include '../includes/auth.php';
if (!isAdmin()) exit();

$id = (int)$_GET['id'];

$conn->query("DELETE FROM transactions WHERE user_id = $id");
$conn->query("DELETE FROM bills WHERE user_id = $id");
$conn->query("DELETE FROM payment_schedules WHERE user_id = $id");
$conn->query("DELETE FROM users WHERE id = $id");

header("Location: index.php");
?>