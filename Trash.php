<!-- Leavephp -->
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
    echo "<script>
    // Redirect to index.php after the page has loaded
    window.location.href = 'index.php';
</script>";
    exit;
}

?>
<div class="main-content">
    <div class="container">
        <button onclick="window.location.href = 'Leavereq.php';"> Request A Leave</button>
        <button onclick="window.location.href = 'Manageleavereq.php';">Pending Requests</button>
        <button onclick="window.location.href = 'Manageleavetype.php';">Manage Leave Types</button>
        <button onclick="window.location.href = 'EmpOnLeave.php';">Leave History</button>
    </div>
</div>
<style>
    .main-content {
        position: relative;
        background-color: #eee;
        min-height: 100vh;
        top: 0;
        left: 80px;
        transition: all 0.5s ease;
        width: calc(100% - 80px);
        padding: 1rem;
    }

    .container button {
        margin: 20px;
        width: 40%;
        height: 300px;
        background-color: #fff;
        display: flex;
        float: left;
        align-items: center;
        /* Center vertically */
        justify-content: center;
        /* Center horizontally */
        text-align: center;
        box-sizing: border-box;
        border-radius: 20px;
        font-size: 30px;
    }

    button:hover {
        background-color: rgb(37, 150, 190);
        color: white;
    }
</style>

<!-- Payslip -->

<h1>Payroll</h1>
    <?php


    // Fetch data from the database
    $sql = "SELECT * FROM tbl_salary";
    $result = $conn->query($sql);

    // Function to calculate net pay with deductions
    function calculateNetPay($grossPay, $deductionRate)
    {
        return $grossPay - ($grossPay * ($deductionRate / 100));
    }

    // Check if there are any records
    if ($result->num_rows > 0) {
        // Deduction rate (percentage)
        $deductionRate = 10; // 10% deduction rate (adjust as needed)
    
        // Output payslips
        while ($row = $result->fetch_assoc()) {
            // Calculate gross pay based on monthly salary
            $grossPay = $row['Salary'];

            // Calculate net pay with deductions
            $netPay = calculateNetPay($grossPay, $deductionRate);

            // Output individual payslip
            echo "<h2>Payslip for {$row['Name']}</h2>";
            echo "<p><strong>ID:</strong> {$row['UserId']}</p>";
            echo "<p><strong>Monthly Salary:</strong> $ {$grossPay}</p>";
            echo "<p><strong>Deductions ({$deductionRate}%):</strong> $ " . ($grossPay * ($deductionRate / 100)) . "</p>";
            echo "<p><strong>Net Pay:</strong> $ {$netPay}</p>";
            echo "<hr>";
        }
    } else {
        echo "No records found in the database.";
    }


    // Close database connection
    $conn->close();
    ?>
          </div>