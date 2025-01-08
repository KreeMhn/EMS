<?php

include ('./components/connect.php');
include ('./components/navbar.php');
include ('userclass.php'); // Include the Attendance class file

function isLoggedIn()
{
    return isset($_SESSION['username']);
}

// If the user is not logged in, redirect them to the login page
if (!isLoggedIn()) {
    echo "<script>
    window.location.href = 'index.php';
</script>";
    exit;
}

// Get the selected date or use the current date as default
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Create an instance of the Attendance class
$attendance = new Attendance($conn);

// Get the attendance data
$result = $attendance->getAttendance();

?>
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            color: #333;
        }

        .main-content {
            margin-left: 80px;
            padding: 2rem;
            transition: margin-left 0.5s;
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
    </style>
</head>

<body>
    <div class="main-content">
        <?php
        // Output data in a table format
        echo "<table border='1'>
                <tr>
                    <th>Month</th>
                    <th>Employee ID</th>
                    <th>Employee Name</th>
                    <th>Days Attended</th>
                </tr>";

        if ($result->num_rows > 0) {
            $current_month = '';
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                if ($row["month"] != $current_month) {
                    $current_month = $row["month"];
                    echo "<tr><td colspan='4' style='background-color: #ddd; font-weight: bold;'>" . $current_month . "</td></tr>";
                }
                echo "<tr>
                        <td></td>
                        <td>" . $row["user_id"] . "</td>
                        <td>" . $row["Username"] . "</td>
                        <td>" . $row["days_attended"] . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No results found</td></tr>";
        }

        echo "</table>";

        ?>
    </div>
</body>

</html>
