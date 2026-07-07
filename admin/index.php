<?php 
include '../includes/header.php';   // This already includes auth.php

if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-shield"></i> Admin Dashboard</h2>
        <a href="../dashboard.php" class="btn btn-outline-light">← Back to Site</a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5 g-3">
        <div class="col-md-3">
            <div class="card text-white" style="background: linear-gradient(135deg, #e91e63, #c2185b);">
                <div class="card-body text-center">
                    <h5>Total Users</h5>
                    <h3><?= $conn->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetch_row()[0] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h5>Pending Bills</h5>
                    <h3><?= $conn->query("SELECT COUNT(*) FROM bills WHERE status='pending'")->fetch_row()[0] ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h5>Total Revenue</h5>
                    <h3>TZS <?= number_format($conn->query("SELECT COALESCE(SUM(amount),0) FROM transactions")->fetch_row()[0], 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h5>Total Transactions</h5>
                    <h3><?= $conn->query("SELECT COUNT(*) FROM transactions")->fetch_row()[0] ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mb-4">
        <a href="bills.php" class="btn btn-primary me-2">📋 Manage Bills</a>
        <a href="transactions.php" class="btn btn-success">💰 All Transactions</a>
    </div>

    <!-- Users Table -->
    <h4 class="mb-3" style="color: #e91e63;">👥 All Users Activity</h4>
    <!-- (Keep your existing users table code here) -->
    <!-- ... paste your previous users table code ... -->

</div>

<?php include '../includes/footer.php'; ?>