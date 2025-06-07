<?php
// Uključi autoload ako koristiš Composer
// require 'vendor/autoload.php';

$cardNumber = $_POST['card_number'];
$expMonth = $_POST['exp_month'];
$expYear = $_POST['exp_year'];
$cvv = $_POST['cvv'];
$cardholder = $_POST['cardholder'];

// TODO: Validacija inputa na serveru

// Pripremi podatke za Payment Gateway (prema API dokumentaciji)
$apiUrl = "https://sandbox.payment-gateway-provider.com/api/payment"; // promeni na produkcioni URL za live
$apiKey = "your_test_api_key"; // iz .env ili config fajla

$data = [
    "amount" => 1000, // iznos u centima, npr. 1000 = 10.00 EUR
    "currency" => "EUR",
    "card" => [
        "number" => $cardNumber,
        "exp_month" => $expMonth,
        "exp_year" => $expYear,
        "cvv" => $cvv,
        "holder" => $cardholder,
    ],
    "order_id" => uniqid("order_"),
];

$options = [
    "http" => [
        "header" => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        "method" => "POST",
        "content" => json_encode($data)
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($apiUrl, false, $context);
if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(["message" => "Greška pri komunikaciji sa gateway-om"]);
    exit;
}

// Analiziraj odgovor Payment Gateway-a
$respData = json_decode($response, true);
if ($respData["status"] == "success") {
    echo json_encode(["message" => "Plaćanje uspešno!"]);
} else {
    echo json_encode(["message" => "Plaćanje neuspešno: " . $respData["error"]["message"]]);
}
?>