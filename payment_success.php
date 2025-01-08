<?php
include('./components/connect.php');


if (isset($_GET['pidx']) && isset($_GET['amount']) && isset($_GET['status'])) {
    // Assign the query parameters to variables
    $pidx = $_GET['pidx'];
    $amount = $_GET['amount'];
    $status = $_GET['status'];
    $transactionId = isset($_GET['tidx']) ? $_GET['tidx'] : '';
    $purchaseOrderId = isset($_GET['purchase_order_id']) ? $_GET['purchase_order_id'] : '';
    $purchaseOrderName = isset($_GET['purchase_order_name']) ? $_GET['purchase_order_name'] : '';
    $mobile = isset($_GET['mobile']) ? $_GET['mobile'] : '';

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO payments (pidx, amount, status, transaction_id, purchase_order_id, purchase_order_name, mobile) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssss", $pidx, $amount, $status, $transactionId, $purchaseOrderId, $purchaseOrderName, $mobile);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect to a different success page to avoid an infinite loop
        header("Location: Payroll.php");
        exit(); // Ensure the script exits after the redirection
    } else {
        // Output error message
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // Output error message if necessary parameters are missing
    echo "Error: Missing necessary query parameters.";
}
?>
