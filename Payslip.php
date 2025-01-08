<?php
include ('./components/navbar.php');
include ('./components/connect.php');

function isLoggedIn()
{
    // Check if the 'username' session variable exists
    return isset($_SESSION['username']);
}

// If the user is not logged in, redirect them to the login page
if (!isLoggedIn()) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

// Handle form submission
if (isset($_POST['update'])) {
    // Retrieve form data
    $name = isset($_POST['Empname']) ? $_POST['Empname'] : '';
    $gross_pay = isset($_POST['Salary']) ? $_POST['Salary'] : '';
    $fines = isset($_POST['Fines']) ? $_POST['Fines'] : '';
    $total = intval($gross_pay) - intval($fines);

    // Store data in session variables
    $_SESSION['invoice_data'] = [
        'name' => $name,
        'gross_pay' => $gross_pay,
        'fines' => $fines,
        'total' => $total,
    ];

    // Redirect to invoice.php to display the invoice
    header("Location: Payment.php");
    exit;
}
?>

<div class="main-content">
    <div class="container">
        <div class="payslip-details">
        <?php
            // Retrieve parameters from the URL
            $id = isset($_GET['id']) ? $_GET['id'] : '';
            $name = isset($_GET['name']) ? $_GET['name'] : '';
            $gross_pay = isset($_GET['gross_pay']) ? $_GET['gross_pay'] : '';
        // You can directly insert PHP variables into HTML without wrapping them in a PHP echo statement
        echo "
        <form method='post'>
            <label for='Empname'>Employee Name:</label><br>
            <input type='text' name='Empname' value='$name'><br>
            <label for='Salary'>Salary</label><br>
            <input type='number' name='Salary' value='$gross_pay'><br>
            <label for='Fines'>Fines</label><br>
            <input type='number' name='Fines'><br><br>
            <button type='submit' name='update'>Generate Payslip</button>
        </form>"
        ;
        ?>
        </div>
    </div>
</div>

<style>
    .main-content {
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

    .payslip-details {
        margin-bottom: 20px;
    }

    .payslip-details label {
        font-weight: bold;
    }

    .payslip-details input[type='text'],
    .payslip-details input[type='number'] {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .payslip-details button {
        background-color: #4caf50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .payslip-details button:hover {
        background-color: #45a049;
    }
</style>
