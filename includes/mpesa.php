<?php
// includes/mpesa.php
class Mpesa {
    private $apiKey = 'YOUR_SANDBOX_API_KEY';           // ← Change this
    private $publicKey = 'YOUR_SANDBOX_PUBLIC_KEY';     // ← Change this
    private $baseUrl = "https://openapi.m-pesa.com/sandbox"; // Sandbox

    public function __construct() {
        // You can make this dynamic later
    }

    private function getAccessToken() {
        $url = $this->baseUrl . "/oauth/token";
        $headers = [
            "Authorization: Basic " . base64_encode($this->apiKey . ":" . $this->publicKey),
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    public function initiatePayment($amount, $phone, $billId, $reference) {
        $token = $this->getAccessToken();
        if (!$token) {
            return ["status" => "error", "message" => "Failed to authenticate"];
        }

        $url = $this->baseUrl . "/transactions/c2b";

        $data = [
            "serviceCode" => "000000",
            "amount" => (float)$amount,
            "currency" => "TZS",
            "customerMSISDN" => $phone,
            "transactionReference" => $reference,
            "callbackURL" => "http://localhost/utilitypay/mpesa_callback.php",
            "description" => "UtilityPay Bill #" . $billId
        ];

        $headers = [
            "Authorization: Bearer " . $token,
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
?>