<?php
include ('./components/connect.php');
session_start(); // Ensure session is started

if (isset($_SESSION['invoice_data'])) {
    $invoice_data = $_SESSION['invoice_data'];

    // Extract data
    $name = $invoice_data['name'];
    $gross_pay = $invoice_data['gross_pay'];
    $fines = $invoice_data['fines'];
    $total = $invoice_data['total'];

    // Replace with your actual Khalti secret key
    $khalti_secret_key = 'live_secret_key_68791341fdd94846a146f0457ff7b455';

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://a.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(array(
            "return_url" => "http://localhost/Summer%20Project/EMS/payment_success.php",
            "website_url" => "https://index.php/",
            "amount" => $total*100, // Pass $total directly as an integer
            "purchase_order_id" => "Order01",
            "purchase_order_name" => "test",
            "customer_info" => array(
                "name" => "$name", // Replace with actual customer name
                "email" => "test@khalti.com", // Replace with actual customer email
                "phone" => "9800000001" // Replace with actual customer phone number
            )
        )),
        CURLOPT_HTTPHEADER => array(
            'Authorization: key ' . $khalti_secret_key,
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);

    if ($response === false) {
        echo "Error: " . curl_error($curl);
        // Handle cURL error
    } else {
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status !== 200) {
            echo "Error: Unexpected HTTP status code: " . $http_status;
            // Handle unexpected HTTP status
        } else {
            // Decode the JSON response
            $responseData = json_decode($response, true);
            var_dump($responseData); // Check the structure of $responseData

            if (isset($responseData['payment_url'])) {
                // Redirect the user to the payment URL
                header("Location: " . $responseData['payment_url']);
                exit; // Make sure to exit to prevent further execution
            } else {
                echo "Error: Payment URL not found in the response.";
                // Handle this error scenario, log or display an appropriate message
            }
        }
    }

    curl_close($curl);
}
?>
