<?php 
include '../includes/header.php'; 
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-invoice"></i> Manage All Bills</h2>
        <a href="index.php" class="btn btn-secondary">← Back to Admin</a>
    </div>

    <?php
    // Debug Query
    $result = $conn->query("
        SELECT b.*, u.name as user_name 
        FROM bills b 
        JOIN users u ON b.user_id = u.id 
        ORDER BY b.due_date DESC
    ");

    echo "<p><strong>Total Bills Found:</strong> " . $result->num_rows . "</p>";
    ?>

    <div class="card">
        <div class="card-body">
            <?php if ($result->num_rows == 0): ?>
                <div class="alert alert-info text-center py-5">
                    <h5>No bills found in the system.</h5>
                    <p>Go to <a href="../bills.php">Bills Page</a> and add some bills first.</p>
                </div>
            <?php else: ?>
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>User</th>
                            <th>Utility</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Provider</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= ucfirst($row['utility_type']) ?></td>
                            <td><strong>TZS <?= number_format($row['amount'], 2) ?></strong></td>
                            <td><?= $row['due_date'] ?></td>
                            <td><?= htmlspecialchars($row['provider']) ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status']=='paid' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>