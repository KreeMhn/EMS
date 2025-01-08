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
    <h1>Payroll</h1>
    <?php
    // Fetch data from the database
    $sql = "SELECT * FROM tbl_salary";
    $result = $conn->query($sql);

    // Check if there are any records
    if ($result->num_rows > 0) {
        // Array to store payroll data
        $payrollReport = array();
    
        // Fetch each row from the result set
        while ($row = $result->fetch_assoc()) {
            // Calculate gross pay based on monthly salary
            $grossPay = $row['Salary'];
            // Add payroll data to the array
            $payrollReport[] = array(
                'id' => $row['UserId'],
                'name' => $row['Name'],
                'gross_pay' => $grossPay,
            );
        }
    
        // Output payroll report
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>Name</th><th>Gross Pay ($)</th><th>Action</th></tr>";
    
        foreach ($payrollReport as $entry) {
            echo "<tr>";
            echo "<td>{$entry['id']}</td>";
            echo "<td>{$entry['name']}</td>";
            echo "<td>{$entry['gross_pay']}</td>";
            echo "<td><a class='button' href=\"payslip.php?id={$entry['id']}&name={$entry['name']}&gross_pay={$entry['gross_pay']}\">Generate Payslip</a></td>";
            echo "</tr>";
        }
    
        echo "</table>";
    } else {
        echo "No records found in the database.";
    }
    
    // Close database connection
    $conn->close();
    ?>
    <br>
    <button class="allot-button" onclick="window.location.href='AddSalary.php';">Allot Salaries</button>
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

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th,
    td {
        text-align: left;
        padding: 16px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .button {
        display: inline-block;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #45a049;
    }

    .allot-button {
        display: inline-block;
        background-color: #008CBA;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .allot-button:hover {
        background-color: #006D9C;
    }
</style>
