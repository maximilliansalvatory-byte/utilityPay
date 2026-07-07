<?php 
include 'includes/header.php'; 
$user_id = $_SESSION['user_id'];

if (isset($_POST['add_bill'])) {
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $due = $_POST['due_date'];
    $provider = $_POST['provider'];
    
    $stmt = $conn->prepare("INSERT INTO bills (user_id, utility_type, amount, due_date, provider, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("isdss", $user_id, $type, $amount, $due, $provider);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Bill added successfully!</div>";
    }
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Utility Bills</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBillModal">
            <i class="fas fa-plus"></i> Add New Bill
        </button>
    </div>

    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM bills WHERE user_id = $user_id ORDER BY due_date ASC");
        if ($result->num_rows == 0): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <h4>No bills found</h4>
                    <p>Please add your first utility bill</p>
                </div>
            </div>
        <?php else: 
            while($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 utility-card">
                    <div class="card-body text-white">
                        <h4><?= ucfirst($row['utility_type']) ?></h4>
                        <h2>TZS <?= number_format($row['amount'], 2) ?></h2>
                        <p>Due: <?= $row['due_date'] ?></p>
                        <p><strong><?= $row['provider'] ?></strong></p>
                        
                        <?php if($row['status'] == 'pending'): ?>
                            <a href="payments.php?bill_id=<?= $row['id'] ?>" 
                               class="btn btn-light w-100 mt-3">
                                <i class="fas fa-credit-card"></i> Pay Now
                            </a>
                        <?php else: ?>
                            <span class="badge bg-success fs-5 w-100 py-2">✓ Already Paid</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div>

<!-- Add Bill Modal -->
<div class="modal fade" id="addBillModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5>Add New Bill</h5>
                </div>
                <div class="modal-body">
                    <select name="type" class="form-select mb-3" required>
                        <option value="water">Water</option>
                        <option value="electricity">Electricity</option>
                        <option value="internet">Internet</option>
                    </select>
                    <input type="number" name="amount" class="form-control mb-3" placeholder="Amount (TZS)" required step="0.01">
                    <input type="date" name="due_date" class="form-control mb-3" required>
                    <input type="text" name="provider" class="form-control" placeholder="Provider (e.g. TANESCO, DAWASA)" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="add_bill" class="btn btn-success">Add Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>