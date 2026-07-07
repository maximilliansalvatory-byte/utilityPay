<?php 
require 'auth.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UtilityPay - <?php echo ucfirst(basename($_SERVER['PHP_SELF'], '.php')); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo (isAdmin() && strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? 'admin/index.php' : 'dashboard.php'; ?>">
            UtilityPay
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <?php if (isAdmin() && strpos($_SERVER['PHP_SELF'], '/admin/') !== false): ?>
                    <!-- ADMIN NAVIGATION - NO LOGOUT HERE -->
                    <li class="nav-item"><a class="nav-link" href="index.php">Admin Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="bills.php">Manage Bills</a></li>
                    <li class="nav-item"><a class="nav-link" href="transactions.php">All Transactions</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="../dashboard.php">← Back to Main Site</a></li>
                
                <?php else: ?>
                    <!-- NORMAL USER NAVIGATION -->
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="bills.php">Bills</a></li>
                    <li class="nav-item"><a class="nav-link" href="payments.php">Payments</a></li>
                    <li class="nav-item"><a class="nav-link" href="analytics.php">Analytics</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    
                    <?php if(isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="admin/index.php">
                            <i class="fas fa-user-shield"></i> Admin
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Logout - Only for Normal Users -->
                <?php if (! (isAdmin() && strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ): ?>
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>