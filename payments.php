<?php 
include 'includes/header.php'; 

$user_id = $_SESSION['user_id'];

$bill = null;
$message = "";

// Get bill details
if (isset($_GET['bill_id'])) {
    $bill_id = (int)$_GET['bill_id'];
    $bill_query = $conn->query("SELECT * FROM bills WHERE id = $bill_id AND user_id = $user_id");
    $bill = $bill_query->fetch_assoc();
}

// Process Payment (Simulated + M-Pesa fallback)
if (isset($_POST['pay_mpesa']) && $bill) {
    $phone = trim($_POST['phone']);
    $reference = "UPAY" . time() . rand(10000, 99999);

    // Simulate successful payment for now (since real M-Pesa needs credentials)
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, bill_id, amount, payment_method, reference, status) 
                           VALUES (?, ?, ?, 'M-Pesa', ?, 'success')");
    $stmt->bind_param("iids", $user_id, $bill['id'], $bill['amount'], $reference);
    $stmt->execute();

    // Mark bill as paid
    $conn->query("UPDATE bills SET status = 'paid' WHERE id = {$bill['id']}");

    $message = "<div class='alert alert-success'>
                    <strong>✅ Payment Successful!</strong><br>
                    Reference: <b>$reference</b><br>
                    Amount: TZS " . number_format($bill['amount'], 2) . "
                </div>";
}
?>

<div class="container py-5">
    <h2 class="mb-4">Make Payment</h2>

    <?= $message ?>

    <?php if ($bill): ?>
        <div class="card shadow">
            <div class="card-body p-5">
                <h4><?= ucfirst($bill['utility_type']) ?> Bill</h4>
                <h2 class="text-success">TZS <?= number_format($bill['amount'], 2) ?></h2>
                <p><strong>Due Date:</strong> <?= $bill['due_date'] ?></p>
                <p><strong>Provider:</strong> <?= $bill['provider'] ?></p>

                <hr>

                <form method="POST">
                    <input type="hidden" name="bill_id" value="<?= $bill['id'] ?>">

                    <div class="mb-4">
                        <label class="form-label fw-bold">M-Pesa Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="255<?= substr($_SESSION['phone'] ?? '712345678', -9) ?>" required>
                    </div>

                    <button type="submit" name="pay_mpesa" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-mobile-alt"></i> PAY VIA M-PESA
                    </button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">Please select a bill from <a href="bills.php">My Bills</a></div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>