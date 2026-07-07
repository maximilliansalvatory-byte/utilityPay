<?php 
include '../includes/header.php'; 
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-money-bill-wave"></i> All Transactions</h2>
        <a href="index.php" class="btn btn-secondary">← Back to Admin</a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Date</th>
                <th>User</th>
                <th>Utility</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Method</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("
                SELECT t.*, u.name as user_name, b.utility_type 
                FROM transactions t 
                JOIN users u ON t.user_id = u.id 
                JOIN bills b ON t.bill_id = b.id 
                ORDER BY t.payment_date DESC
            ");
            while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= date('d M Y H:i', strtotime($row['payment_date'])) ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= ucfirst($row['utility_type']) ?></td>
                <td>TZS <?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['reference'] ?></td>
                <td><?= $row['payment_method'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>