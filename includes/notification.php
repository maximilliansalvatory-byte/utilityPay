<?php
function sendPaymentNotification($user_id, $bill, $reference, $transaction_id) {
    global $conn;
    
    $user = $conn->query("SELECT name, email, phone FROM users WHERE id = $user_id")->fetch_assoc();
    $amount = number_format($bill['amount'], 2);

    // Email Notification
    $to = $user['email'];
    $subject = "UtilityPay - Payment Receipt #" . $reference;
    $message = "
        Dear {$user['name']},\n\n
        Your payment of TZS $amount for {$bill['utility_type']} has been received successfully.\n\n
        Reference: $reference\n
        Date: " . date('d M Y H:i') . "\n\n
        Thank you for using UtilityPay!\n
        Download your receipt: http://localhost/utilitypay/receipt.php?transaction_id=$transaction_id
    ";
    
    $headers = "From: no-reply@utilitypay.co.tz";
    mail($to, $subject, $message, $headers);

    // SMS Simulation (Log)
    $sms_message = "UtilityPay: Payment of TZS $amount successful. Ref: $reference";
    file_put_contents('sms_logs.txt', date('Y-m-d H:i:s') . " - " . $user['phone'] . " : " . $sms_message . "\n", FILE_APPEND);
}
?>