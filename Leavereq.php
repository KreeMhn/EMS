<?php
include('userclass.php');
include ('./components/navbar.php');
include ('./components/connect.php');
include 'Calendar.php';
$calendar = new Calendar();

// Create an instance of the Employee class
$employee = new Employee($conn);

// Get today's date in the format YYYY-MM-DD
$today = date('Y-m-d');

// Store options in array
$userquery = "SELECT Username FROM userdb";
$result = $conn->query($userquery);

// Initialize an empty array to store the retrieved values
$data = array();

// Check if query was successful
if ($result->num_rows > 0) {
    // Fetch rows from the result set as an associative array
    while ($row = $result->fetch_assoc()) {
        // Store each row in the $data array
        $data[] = $row;
    }
} else {
    // Handle query failure
    echo "No records found.";
}

// Fetch leave types from database
$sql = "SELECT ID, Leavetype FROM leave_type";
$result = $conn->query($sql);

$leaveTypes = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Store leave types in an array (id as key, LeaveType as value)
        $leaveTypes[$row['ID']] = $row['Leavetype'];
    }
}

if (isset($_POST['btn-req'])) {
    $empname = $_POST['Empname'];
    $leave_type = $_POST['Leave_Type'];
    $leave_duration = $_POST['leaveduration'];
    $datefrom = $_POST['From'];
    $dateto = $_POST['To'];
    $reason = $_POST['Reason'];
    
    // Get the maximum allowed duration for the selected leave type from the database
    $sql = "SELECT Days FROM leave_type WHERE Leavetype='$leave_type'";
    // $sql = "SELECT Leaveduration FROM leaverequest WHERE Leavetype='$leave_type' AND Status='Approved'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $max_duration = $row['Days'];
    } else {
        die("Error: Invalid leave type.");
    }

    // Validate leave duration
    if ($leave_duration > $max_duration) {
        echo"<script>alert('Leave duration cannot exceed $max_duration days for $leave_type.')</script>";
        // die("Error: Leave duration cannot exceed $max_duration days for $leave_type.");
    }

    // Use the applyforLeave() method
    $res = $employee->applyforLeave($empname, $leave_type, $datefrom, $dateto, $reason, $leave_duration);
    if (!$res) {
        die("Error: " . $conn->error);
    }
    echo '<script>window.location.href = "Leavereq.php";</script>';
    exit;
}
?>

<div class="main-content">
    <div class="leaveform-container">
        <h1 style="margin-bottom:30px; text-align: center;">Request Leave</h1>
        <form action="" method="post">
            <label for="Empname">
                <h3>Employee Name</h3>
            </label><br>
            <input type="text" name="Empname" class="fa-in" value="<?php echo $_SESSION['username']; ?>" required><br>
            <label for="Leave_Type">
                <h3>Leave Type</h3>
            </label><br>
            <select name="Leave_Type" id="leaveType">
                <option value="">Select Leave Type</option>
                <?php
                // Output options for each leave type
                foreach ($leaveTypes as $id => $leaveType) {
                    echo "<option value='$leaveType'>$leaveType</option>";
                }
                ?>
            </select>
            <label for="Leaveduration">
                <h3>Leave Duration</h3>
            </label><br>
            <input type="number" name="leaveduration" id="leaveduration" class="fa-in" required><br>
            <label for="From">
                <h3>From</h3>
            </label><br>
            <input type="date" name="From" id="startdate" class="fa-in" min="<?php echo $today; ?>" required><br>
            <label for="To">
                <h3>To</h3>
            </label><br>
            <input type="date" name="To" id="enddate" class="fa-in" min="<?php echo $today; ?>" required><br>
            <label for="Reason">
                <h3>Reason</h3>
            </label><br>
            <input type="text" name="Reason" class="fa-in" style="width: 100%; padding: 12px 20px; margin: 8px 0; box-sizing: border-box;" required><br>
            <button type="submit" name="btn-req" class="btn-primary">Submit</button><br>
        </form>
    </div>
    <div class="calendar-display">
        <?php echo $calendar; ?>
    </div>
    <div class="bottom-half">
            <?php
            $sql = "SELECT ID, Leavetype, Days FROM leave_type";
            $result = $conn->query($sql);
            if (!$result) {
                trigger_error('Invalid query: ' . $conn->error);
            } else {
                if ($result->num_rows > 0) {
                    // Output data of each row
                    echo "<table border='1'>";
                    echo "<tr><th>ID</th><th>Leave Type</th><th>Days</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Leavetype"] . "</td><td>" . $row["Days"] . "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
            }
            ?>
        </div>
</div>

<script>
    document.getElementById('startdate').addEventListener('change', calculateEndDate);
    document.getElementById('leaveduration').addEventListener('input', calculateEndDate);

    function calculateEndDate() {
        var startDate = new Date(document.getElementById('startdate').value);
        var duration = parseInt(document.getElementById('leaveduration').value);

        if (!isNaN(startDate.getTime()) && !isNaN(duration)) {
            startDate.setDate(startDate.getDate() + duration);
            var endDate = startDate.toISOString().split('T')[0];
            document.getElementById('enddate').value = endDate;
        }
    }
</script>

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

    .leaveform-container {
        padding: 1rem;
        margin: 1rem;
        border: 1px solid black;
        color: black;
        height: auto;
        width: 40%;
        font-family: Arial, Helvetica, sans-serif;
        float: left;
    }

    .fa-in {
        padding: 1rem;
    }

    input {
        border-radius: 20px;
        margin-bottom: 20px;
        width: 100%;
    }
    select {
        border-radius: 20px;
        margin-bottom: 20px;
        width: 100%;
        height: 8vh;
    }

    .btn-primary {
        border: none;
        margin: 15px;
        background: green;
        color: wheat;
        padding: 15px 32px;
        text-align: center;
        border-radius: 30px;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: rgb(37, 150, 190);
        color: white;
    }

    .radiocontainer {
        margin-bottom: 20px;
    }

    .radiocontainer form {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    .radiocontainer label {
        display: flex;
        cursor: pointer;
        font-weight: 500;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.375em;
    }

    .radiocontainer label input {
        position: absolute;
        left: -9999px;
    }

    .radiocontainer label input:checked+span {
        background-color: #414181;
        color: white;
    }

    .radiocontainer label input:checked+span:before {
        box-shadow: inset 0 0 0 0.4375em #00005c;
    }

    .radiocontainer label span {
        display: flex;
        align-items: center;
        padding: 0.375em 0.75em 0.375em 0.375em;
        border-radius: 99em;
        transition: 0.25s ease;
        color: #414181;
    }

    .radiocontainer label span:hover {
        background-color: #d6d6e5;
    }

    .radiocontainer label span:before {
        display: flex;
        flex-shrink: 0;
        content: "";
        background-color: #fff;
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        margin-right: 0.375em;
        transition: 0.25s ease;
        box-shadow: inset 0 0 0 0.125em #00005c;
    }
    table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
            padding: 16px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
</style>

