<?php
// includes/functions.php

function updateUserTotals($user_id) {
    global $conn;
    
    // Total Pending
    $pending_result = $conn->query("SELECT COALESCE(SUM(amount), 0) as total 
                                   FROM bills 
                                   WHERE user_id = $user_id AND status = 'pending'");
    $pending = $pending_result->fetch_assoc()['total'] ?? 0;

    // Total Paid
    $paid_result = $conn->query("SELECT COALESCE(SUM(amount), 0) as total 
                                FROM transactions 
                                WHERE user_id = $user_id");
    $paid = $paid_result->fetch_assoc()['total'] ?? 0;

    // Update users table
    $stmt = $conn->prepare("UPDATE users SET total_pending = ?, total_paid = ? WHERE id = ?");
    $stmt->bind_param("ddi", $pending, $paid, $user_id);
    $stmt->execute();
}
?>