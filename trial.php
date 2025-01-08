<?php

include ('./components/connect.php');
include ('./components/navbar.php');

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

// Get the selected date or use the current date as default
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

?>
<!DOCTYPE html>
<html>

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

<body>

    <div class="main-content">

        <?php
        // Query to get the attendance count per employee per month
        $sql = "SELECT attendance.user_id, userdb.Username,
               DATE_FORMAT(attendance.check_in, '%Y-%m') AS month, 
               COUNT(DISTINCT attendance.check_in) AS days_attended 
        FROM attendance JOIN userdb ON attendance.user_id = userdb.UserId 
        GROUP BY user_id, month 
        ORDER BY user_id, month";

        $result = $conn->query($sql);

        // Output data in a table format
        echo "<table border='1'>
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Month</th>
            <th>Days Attended</th>
        </tr>";

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>" . $row["user_id"] . "</td>
                <td>" . $row["Username"] . "</td>
                <td>" . $row["month"] . "</td>
                <td>" . $row["days_attended"] . "</td>
              </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>0 results</td></tr>";
        }

        echo "</table>";

        ?>
    </div>
</body>

</html>