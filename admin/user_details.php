<?php 
include '../includes/header.php'; 
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}

$user_id = (int)$_GET['id'];

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

if (!$user) {
    echo "<div class='alert alert-danger'>User not found!</div>";
    exit();
}

// Get Transactions
$transactions = $conn->query("
    SELECT t.*, b.utility_type, b.provider 
    FROM transactions t 
    JOIN bills b ON t.bill_id = b.id 
    WHERE t.user_id = $user_id 
    ORDER BY t.payment_date DESC
");

// Get Bills
$bills = $conn->query("SELECT * FROM bills WHERE user_id = $user_id ORDER BY due_date DESC");
?>

<div class="container py-5">
    <h2><i class="fas fa-user"></i> <?= htmlspecialchars($user['name']) ?> - Details</h2>
    <a href="index.php" class="btn btn-secondary mb-4">← Back to All Users</a>

    <div class="row">
        <!-- User Info -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Personal Information</h5>
                    <hr>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not Provided') ?></p>
                    <p><strong>Joined:</strong> <?= $user['created_at'] ?></p>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="col-md-8">
            <h5 class="mb-3">📊 Transaction History (<?= $transactions->num_rows ?>)</h5>
            
            <?php if ($transactions->num_rows == 0): ?>
                <div class="alert alert-info">This user has no transactions yet.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Utility</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($trans = $transactions->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d M Y H:i', strtotime($trans['payment_date'])) ?></td>
                                <td><?= ucfirst($trans['utility_type']) ?></td>
                                <td><strong>TZS <?= number_format($trans['amount'], 2) ?></strong></td>
                                <td><?= $trans['reference'] ?></td>
                                <td><?= $trans['payment_method'] ?></td>
                                <td>
                                    <a href="../receipt.php?transaction_id=<?= $trans['id'] ?>" 
                                       class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-download"></i> Receipt
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>