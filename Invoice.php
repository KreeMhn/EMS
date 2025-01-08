<?php
session_start(); // Start session to access session variables

// Check if invoice data is set in session
if (isset($_SESSION['invoice_data'])) {
    $invoice_data = $_SESSION['invoice_data'];

    // Extract data
    $name = $invoice_data['name'];
    $gross_pay = $invoice_data['gross_pay'];
    $fines = $invoice_data['fines'];
    $total = $invoice_data['total'];

    // Output invoice details
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Invoice</title>
              <style>
                  /* Styles for invoice */
                  body {
                      font-family: Arial, sans-serif;
                      background-color: #f4f4f4;
                      padding: 20px;
                  }
                  .container {
                      max-width: 600px;
                      margin: 0 auto;
                      background-color: #fff;
                      padding: 20px;
                      border-radius: 10px;
                      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                  }
                  .invoice-details {
                      margin-bottom: 20px;
                  }
                  .invoice-details h2 {
                      color: #4caf50;
                      margin-bottom: 10px;
                  }
                  .invoice-details p {
                      margin-bottom: 5px;
                  }
              </style>
          </head>
          <body>
              <div class='container'>
                  <div class='invoice-details'>
                      <h2>Invoice</h2>
                      <p><strong>Invoice Date:</strong> " . date('Y-m-d') . "</p>
                      <p><strong>Employee Name:</strong> $name</p>
                      <p><strong>Gross Pay:</strong> $gross_pay</p>
                      <p><strong>Fines:</strong> $fines</p>
                      <p><strong>Net Salary:</strong> $total</p>
                  </div>
              </div>
          </body>
          </html>";

    // Clear session data after displaying the invoice
    unset($_SESSION['invoice_data']);
} else {
    // Redirect to index.php if invoice data is not set (to prevent direct access to invoice.php)
    header("Location: index.php");
    exit;
}
?>
