<?php include 'includes/header.php'; 
$user_id = $_SESSION['user_id'];

// FIXED: Use table aliases to avoid ambiguous 'amount' column
$spending = [];
$labels = ['Water', 'Electricity', 'Internet'];
$types = ['water', 'electricity', 'internet'];

foreach ($types as $index => $type) {
    $sql = "SELECT COALESCE(SUM(t.amount), 0) as total 
            FROM transactions t 
            JOIN bills b ON t.bill_id = b.id 
            WHERE b.utility_type = '$type' 
            AND t.user_id = $user_id";
    
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $spending[] = $row['total'];
}
?>

<div class="container py-5">
    <h2 class="mb-4">Expenditure Analytics</h2>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <canvas id="spendingChart" style="height: 400px;"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5>Total Amount Spent</h5>
                    <h2 class="text-success">TZS <?= number_format(array_sum($spending), 2) ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('spendingChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Amount Spent (TZS)',
                data: <?= json_encode($spending) ?>,
                backgroundColor: ['#00c853', '#ffb300', '#1e88e5'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>