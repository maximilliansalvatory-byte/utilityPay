<?php 
include 'includes/header.php'; 
$user_id = $_SESSION['user_id'];

// Update totals safely
if (file_exists('includes/functions.php')) {
    include 'includes/functions.php';
    updateUserTotals($user_id);
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Fetch ALL bills (no limit)
$all_bills = $conn->query("SELECT * FROM bills 
                          WHERE user_id = $user_id 
                          ORDER BY due_date ASC");
?>

<div class="container py-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?> 👋</h2>

    <!-- Summary Cards -->
    <div class="row mt-4 g-3">
        <div class="col-md-4">
            <div class="card text-white utility-card h-100">
                <div class="card-body">
                    <h5>Pending Bills</h5>
                    <h3>TZS <?= number_format($user['total_pending'] ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <h5>Total Paid</h5>
                    <h3>TZS <?= number_format($user['total_paid'] ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <h5>Active Schedules</h5>
                    <h3>3</h3>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5 mb-3">All My Bills (<?= $all_bills->num_rows ?>)</h4>
    
    <!-- Scrollable Bills Section -->
    <div style="max-height: 650px; overflow-y: auto; padding-right: 10px;" class="row">
        <?php if ($all_bills->num_rows == 0): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    No bills found. Please add bills from the <a href="bills.php">Bills</a> page.
                </div>
            </div>
        <?php else: ?>
            <?php while($bill = $all_bills->fetch_assoc()): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5><?= ucfirst($bill['utility_type']) ?></h5>
                        <h3 class="text-success">TZS <?= number_format($bill['amount'], 2) ?></h3>
                        <p class="text-muted mb-1">Due: <?= $bill['due_date'] ?></p>
                        <p><strong><?= htmlspecialchars($bill['provider']) ?></strong></p>

                        <?php if($bill['status'] == 'pending'): ?>
                            <a href="payments.php?bill_id=<?= $bill['id'] ?>" 
                               class="btn btn-success w-100 mt-3">
                                <i class="fas fa-credit-card"></i> PAY NOW
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary w-100 py-2 mt-3 d-block">
                                ✓ Already Paid
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>