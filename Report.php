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

    .btn-primary {
        background-color: white;
        color: #2193b0;
        border: none;
        margin-top: 10px;
        padding: 10px 20px;
        text-align: center;
        border-radius: 30px;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-primary:hover {
        background-color: #2193b0;
        color: white;
    }

    .btn-primary h3 {
        margin: 0;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            padding: 1rem;
        }

        .container {
            padding: 1rem;
        }

        .btn-primary {
            padding: 8px 16px;
            font-size: 14px;
        }
    }

    form {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    label {
        font-weight: bold;
        margin-right: 0.5rem;
    }

    input[type="date"],
    input[type="submit"] {
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        border: none;
        margin-left: 0.5rem;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>

<body>

    <div class="main-content">
        <div class="attendance-count">

            <button class="btn-primary" onclick="location.href='AttendanceCount.php'">
                <h3>Attendance Count</h3>
            </button>
        </div>
        <br><br>
    
        <form method="GET" action="">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $selected_date; ?>">
            <input type="submit" value="Filter">
        </form>


        <?php
        $result = $conn->query("SELECT userdb.UserId,userdb.Username, attendance.check_in, attendance.check_out 
                            FROM attendance 
                            JOIN userdb ON attendance.user_id = userdb.UserId 
                            WHERE DATE(attendance.check_in) = '$selected_date'");

        echo "<table border='1'>";
        echo "<tr><th>UserId</th><th>Username</th><th>Check In</th><th>Check Out</th><th>Total Hours</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $check_in = new DateTime($row['check_in']);
            $check_out = new DateTime($row['check_out']);
            $interval = $check_in->diff($check_out);
            $total_hours = $interval->format('%h hours %i minutes');

            echo "<tr><td>{$row['UserId']}</td><td>{$row['Username']}</td><td>{$row['check_in']}</td><td>{$row['check_out']}</td><td>{$total_hours}</td></tr>";
        }

        echo "</table>";
        ?>
    </div>
</body>

</html>