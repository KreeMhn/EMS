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
    $total=intval($gross_pay)- intval($fines);
    // Server-side validation (if needed)
    if (empty($name) || empty($gross_pay)) {
        $error = "Employee name and salary are required fields.";
    } else {
        // Example: Update database or any other necessary actions

        // Generate invoice
        generateInvoice($name, $gross_pay, $fines,$total);
    }
}

// Function to generate an example invoice (you can customize this further)
function generateInvoice($name, $gross_pay, $fines,$total)
{
    // Output invoice details
    echo "<div class='main-content'>
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
          </div>";

    // Additional actions can be performed here, such as saving the invoice to a file or database
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

    .invoice-details {
        margin-top: 20px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .invoice-details h2 {
        color: #4caf50;
        margin-bottom: 10px;
    }

    .invoice-details p {
        margin-bottom: 5px;
    }
</style>






<!-- dixica.stha1141@gmail.com -->

<?php
include('userclass.php');
include ('./components/navbar.php');
include ('./components/connect.php');
include 'Calendar.php';
$calendar = new Calendar();

//store options in array

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
    $leave_duration = $_POST['radio'];
    $datefrom = $_POST['From'];
    $dateto = $_POST['To'];
    $reason = $_POST['Reason'];

    $insertsql = "INSERT INTO `leaverequest`(`Empname`, `Leavetype`, `Leaveduration`, `Startdate`, `Enddate`, `Reason`)
     VALUES ('$empname','$leave_type','$leave_duration','$datefrom','$dateto','$reason')";
    $res = mysqli_query($conn, $insertsql);
    if (!$res) {
        die("Error: " . mysqli_error($conn));
    }
    echo '<script>window.location.href = "Leavereq.php";</script>';
    exit;

}
?>



?>
<div class="main-content">
    <div class="leaveform-container">
        <h1 style="margin-bottom:30px; text-align: center; ">Request Leave</h1>
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
            <!-- <select name="Leave_Type" class="fa-in" required>
                <option value="Choose a option">Choose a option</option>
                <option value="Paid_Leave">Paid Leave</option>
                <option value="Sick_Leave">Sick Leave</option>
                <option value="Leave_without_pay">Leave without pay</option>
                <option value="Earned_Leave">Earned Leave</option>
                <option value="Casual_Leave">Casual Leave</option>
                <option value="Maternity_Leave">Maternity Leave</option>
                <option value="Paternity_Leave">Paternity Leave</option>
            </select><br> -->
            <div class="radiocontainer">
                <label for="Leave_Duration">
                    <h3>Leave Duration</h3>
                </label><br>
                <label>
                    <input type="radio" name="radio" id="fullday" value="Full Day" checked>
                    <span>Full Day</span>
                </label>
                <label>
                    <input type="radio" name="radio" id="halfday" value="Half Day">
                    <span>Half Day</span>
                </label>
          
            </div>
            <label for="From">
                <h3>From</h3>
            </label><br>
            <input type="date" name="From" class="fa-in" required><br>
            <label for="To">
                <h3>To</h3>
            </label><br>
            <input type="date" name="To" class="fa-in" required><br>
            <label for="Reason">
                <h3>Reason</h3>
            </label><br>
            <input type="text" name="Reason" class="fa-in" style="width: 100%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            box-sizing: border-box;" required><br>
            <button type="submit" name="btn-req" class="btn-primary">Submit</button><br>

        </form>
    </div>
    <div class="calendar-display">
        <?php echo $calendar; ?>
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

    .leaveform-container {
        padding: 1rem;
        margin: 1rem;
        border: 1px solid black;
        color: black;
        height: auto;
        width: 40%;
        font-family: Arial, Helvetica, sans-serif;

        float: left;
        /* text-align: center; */
    }

    .fa-in {
        padding: 1rem;
        /* margin-top: 1rem; */
    }

    input,
    select {
        border-radius: 20px;
        margin-bottom: 20px;
        width: 100%;
    }

    .btn-primary {

        /* Green */
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

    /* css for radio start */
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

    /* css for radio end */
</style>



<!-- Personal -->
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

$username = $_SESSION['username'];
// Escape the username to avoid SQL injection
$username_escaped = $conn->real_escape_string($username);

$sql = "SELECT UserId FROM userdb WHERE Username = '$username_escaped'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $userId = $row['UserId'];
} else {
    echo "User not found";
    exit;
}
$sql = "SELECT Firstname, Lastname, Userid, Bloodgroup, Gender, Tempadd,
 Tempcity, Tempcnty, Peradd, Percity, Percnty, Degree, Institutename,
  Grade, Passedyr, Expdesc, Bankname, Accname, Accnum, Pannum FROM personalinfo WHERE Userid = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found";
    exit;
}
if (isset($_POST['personal'])) {

        $sql = "UPDATE `personalinfo` SET 
        `Firstname`='{$_POST['fname']}',
        `Lastname`='{$_POST['lname']}',
        `Bloodgroup`='{$_POST['bldgrp']}',
        `Gender`='{$_POST['gender']}'

        WHERE Userid = '$userId'";

    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    } else {
        echo "Updated successfully";
    }
}
if (isset($_POST['address'])) {
    $username = $_POST['Empname'];
    $sql = "UPDATE `personalinfo` SET 

    `Tempadd`='{$_POST['taddress']}',
    `Tempcity`='{$_POST['tcity']}',
    `Tempcnty`='{$_POST['tcountry']}',
    `Peradd`='{$_POST['paddress']}',
    `Percity`='{$_POST['pcity']}',
    `Percnty`='{$_POST['pcountry']}'
    WHERE Userid = '$userId'";

    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    } else {
        echo "Updated successfully";
    }
}
if (isset($_POST['education'])) {
    $username = $_POST['Empname'];
    $sql = "UPDATE `personalinfo` SET 
    
    `Degree`='{$_POST['degreename']}',
    `Institutename`='{$_POST['institutename']}',
    `Grade`='{$_POST['result']}',
    `Passedyr`='{$_POST['passingyear']}'
    WHERE Userid = '$userId'";

    $result = $conn->query($sql);
   
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    } else {
        echo "Updated successfully";
    }
}
if (isset($_POST['experience'])) {
    $username = $_POST['Empname'];
    $sql = "UPDATE `personalinfo` SET 
  
    `Expdesc`='{$_POST['Experience']}'
  
    WHERE Userid = '$userId'";

    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    } else {
        echo "Updated successfully";
    }

}
if (isset($_POST['bank'])) {
    $username = $_POST['Empname'];
    $sql = "UPDATE `personalinfo` SET 
  
    `Bankname`='{$_POST['Bankname']}',
    `Accname`='{$_POST['Accountname']}',
    `Accnum`='{$_POST['Accountnumber']}',
    `Pannum`='{$_POST['Pannumber']}' 
    WHERE Userid = '$userId'";

    $result = $conn->query($sql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    } else {
        echo "Updated successfully";
    }
    
}


?>
<!-- Manageleave.php -->
 <?php
include ('./components/navbar.php');
include ('./components/connect.php');
?>

<style>
    /* body {
        background-color: #bbe4e9;
    } */

    .main-content {
        position: relative;


        min-height: 100vh;
        top: 0;
        left: 80px;
        transition: all 0.5s ease;
        width: calc(100% - 80px);
        padding: 2rem;
        margin-left: 1rem;




    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 2px solid black;
    }

    th {
        text-align: left;
        padding: 16px;
        background-color: black;
        color: white;
    }

    td {
        text-align: left;
        padding: 16px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>


<div class="main-content">
    <div class="leave-req-view">
        <?php

        $sql = "SELECT LID,Empname,Leavetype,Leaveduration,Startdate, Enddate,Reason, Status FROM leaverequest where Status='pending' ";
        $result = $conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $conn->error);
        } else {
            if ($result->num_rows > 0) {
                // Output data of each row
                echo "<table border='3'>";
                echo "<tr><th>Leave ID</th><th>Employee Name</th><th>Leave Type</th><th>Leave Duration</th><th>Startdate</th>
                <th>Enddate</th><th>Reason</th><th>Status</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["LID"] . "</td><td>" . $row["Empname"] . "</td><td>" . $row["Leavetype"] . "</td>
                <td>" . $row["Leaveduration"] . "</td><td>" . $row["Startdate"] . "</td><td>" . $row["Enddate"] . "</td>
                <td>" . $row["Reason"] . "</td><td>" . $row["Status"] . "</td>
                <td><button class='btn-app' onclick='approveLeave(" . $row["LID"] . ")'>Approve</button>
                 &nbsp; <button class='btn-del' onclick='declineLeave(" . $row["LID"] . ")'>Decline</button> </td></tr>";
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
    function approveLeave(lid) {
        if (confirm("Are you sure you want to approve this leave?")) {
            updateLeaveStatus(lid, 'Approved');
        }
    }

    function declineLeave(lid) {
        if (confirm("Are you sure you want to decline this leave?")) {
            updateLeaveStatus(lid, 'Declined');
        }
    }

    function updateLeaveStatus(lid, status) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Reload the page after updating
                location.reload();
            }
        };
        xhr.send("lid=" + lid + "&status=" + status);
 
    }
</script>
<!-- then goes to update_status.php -->



<!-- NOTICE VIEW -->
 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $delete_sql = "DELETE FROM notice WHERE id = $delete_id";
    $conn->query($delete_sql);
}
?>

<style>
    .btn-open-popup {
        padding: 12px 24px;
        font-size: 18px;
        background-color: green;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

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

    .btn-delete {
        padding: 8px 16px;
        font-size: 14px;
        background-color: red;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
</style>

<div class="main-content">
    <?php
    $sql = "SELECT NID, Issuedate, Details FROM notice ORDER BY NID DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<table border='1'>";
        echo "<tr><th>Issue Date</th><th>Details</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Issuedate"] . "</td>";
            echo "<td>" . $row["Details"] . "</td>";
            echo "<td>
                    <form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row["NID"] . "'>
                        <button type='submit' class='btn-delete'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    ?>
</div>

<!-- ISSUE NOTICE -->
 
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

if (isset($_POST['submit'])) {
    // Process for login
    // 1. Get the data from login form
    $date = $_POST['date'];
    $details = $_POST['details'];

    // Server-side validation
    if (empty($date) || empty($details)) {
        $error = "All fields are required.";
    } else {
        $date = mysqli_real_escape_string($conn, $date);
        $details = mysqli_real_escape_string($conn, $details);

        $insertsql = "INSERT INTO `notice`(`Issuedate`, `Details`) VALUES ('$date', '$details')";
        $result = mysqli_query($conn, $insertsql);

        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<style>
    .form-container {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 10px;
        font-size: 16px;
        color: #444;
        text-align: left;
    }

    .form-input {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-submit {
        background-color: green;
        color: #fff;
    }


    .btn-submit:hover {
        background-color: #4caf50;
    }


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
</style>
<<div class="main-content">
    <div class="noticeform">
        <div class="popup-box">
            <h2 style="color: green;">Notice</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="form-container" method="post" onsubmit="return validateForm()">
                <label class="form-label" for="date">Issue Date </label>
                <input class="form-input" type="date" id="date" name="date" required>

                <label class="form-label" for="details">Details</label>
                <textarea name="details" id="details" rows="10" required></textarea><br>

                <button class="btn-submit" type="submit" name="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var date = document.getElementById("date").value;
    var details = document.getElementById("details").value;

    if (date === "" || details === "") {
        alert("All fields must be filled out");
        return false;
    }
    return true;
}
</script>


<!-- Manage leave req -->
 
<?php
include('userclass.php');
include ('./components/navbar.php');
include ('./components/connect.php');
// Instantiate the Employee class and calculate leave durations
$leave = new Leave($conn);
$leaveDurations = $leave->calcDuration();
?>

<style>
    /* body {
        background-color: #bbe4e9;
    } */

    .main-content {
        position: relative;
        min-height: 100vh;
        top: 0;
        left: 80px;
        transition: all 0.5s ease;
        width: calc(100% - 80px);
        padding: 2rem;
        margin-left: 1rem;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 2px solid black;
    }

    th {
        text-align: left;
        padding: 16px;
        background-color: black;
        color: white;
    }

    td {
        text-align: left;
        padding: 16px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>


<div class="main-content">
<div class="leave-req-view">
    <table>
        <tr>
            <th>Leave ID</th>
            <th>Employee</th>
            <th>Leave Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        // Example leave requests
        $leaveRequests = $conn->query("SELECT LID, EmployeeName, LeaveDate, Status FROM leave_requests");

        if ($leaveRequests->num_rows > 0) {
            while ($row = $leaveRequests->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["LID"] . "</td>";
                echo "<td>" . $row["EmployeeName"] . "</td>";
                echo "<td>" . $row["LeaveDate"] . "</td>";
                echo "<td>" . $row["Status"] . "</td>";
                echo "<td>
                        <button onclick='approveLeave(" . $row["LID"] . ")'>Approve</button>
                        <button onclick='declineLeave(" . $row["LID"] . ")'>Decline</button>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No leave requests found</td></tr>";
        }
        ?>
    </table>
    </div>
</div>

<div class="main-content">


<script>
function approveLeave(lid) {
    if (confirm("Are you sure you want to approve this leave?")) {
        updateLeaveStatus(lid, 'Approved');
    }
}

function declineLeave(lid) {
    if (confirm("Are you sure you want to decline this leave?")) {
        updateLeaveStatus(lid, 'Declined');
    }
}

function updateLeaveStatus(lid, status) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Reload the page after updating
            location.reload();
        }
    };
    xhr.send("lid=" + lid + "&status=" + status);
}
</script>

<!-- then goes to update_status.php -->






<!-- 2nd try -->


<div class="leave-req-view">
            <?php
            if ($leaveDurations !== false) {
                if (count($leaveDurations) > 0) {
                    // Output data of each row
                    echo "<table border='3'>";
                    echo "<tr><th>Leave ID</th><th>Employee Name</th><th>Leave Type</th><th>Leave Duration</th><th>Startdate</th>
                    <th>Enddate</th><th>Reason</th><th>Status</th><th>Action</th></tr>";
                    foreach ($leaveDurations as $row) {
                        echo "<tr><td>" . $row["LID"] . "</td><td>" . $row["Empname"] . "</td><td>" . $row["Leavetype"] . "</td>
                        <td>" . $row["Leaveduration"] . "</td><td>" . $row["Startdate"] . "</td><td>" . $row["Enddate"] . "</td>
                        <td>" . $row["Reason"] . "</td><td>" . $row["Status"] . "</td>
                        <td><button class='btn-app' onclick='approveLeave(" . $row["LID"] . ")'>Approve</button>
                         &nbsp; <button class='btn-del' onclick='declineLeave(" . $row["LID"] . ")'>Decline</button> </td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 results";
                }
            } else {
                echo "Failed to retrieve leave durations.";
            }
            ?>
        </div>
</div>
 
<script>
    function approveLeave(lid) {
        if (confirm("Are you sure you want to approve this leave?")) {
            updateLeaveStatus(lid, 'Approved');
        }
    }

    function declineLeave(lid) {
        if (confirm("Are you sure you want to decline this leave?")) {
            updateLeaveStatus(lid, 'Declined');
        }
    }

    function updateLeaveStatus(lid, status) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Reload the page after updating
                location.reload();
            }
        };
        xhr.send("lid=" + lid + "&status=" + status);
 
    }
</script>

<!-- Attendance Count -->
<?php

include ('./components/connect.php');
include ('./components/navbar.php');

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
        // Query to get the attendance count per employee per month
        $sql = "SELECT 
                    attendance.user_id, 
                    userdb.Username,
                    DATE_FORMAT(attendance.check_in, '%Y-%m') AS month, 
                    COUNT(DISTINCT DATE(attendance.check_in)) AS days_attended 
                FROM 
                    attendance 
                JOIN 
                    userdb 
                ON 
                    attendance.user_id = userdb.UserId 
                GROUP BY 
                    attendance.user_id, month 
                ORDER BY 
                    attendance.user_id, month";

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
            echo "<tr><td colspan='4'>No results found</td></tr>";
        }

        echo "</table>";

        ?>
    </div>
</body>

</html>


<!-- Index.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/indexstyle.css">
    <title>EMS Login Page </title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post">
                <h1>Create Account</h1>
                <input type="text" placeholder="Username" name="username" required>
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post">
                <h1>Sign In</h1>
                <input type="text" placeholder="Username" name="username">
                <input type="password" placeholder="Password" name="password">
                <a href="#">Forget Your Password?</a>
                <button name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/indexscript.js"></script>
</body>

</html>
<?php
include ('./components/connect.php');

// Function to validate username
function validateUsername($username) {
    return preg_match('/^[a-zA-Z]+$/', $username);
}

if (isset($_POST['signup'])) {
    // Get the data from the signup form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    // Server-side validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!validateUsername($username)) {
        $error = "Username must contain only letters.";
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "INSERT INTO `userdb`(`Username`, `Email`, `Password`) VALUES ('$username', '$email', '$password');";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        } else {
            echo "User registered successfully";
        }
    }
}


if (isset($_POST['login'])) {
    //process for login
    //1. get the data from login form

    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    // Check the user credentials in the database
    $sql = "SELECT * FROM `userdb` WHERE `Username` = '$username' AND `Password` = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Check if a matching user is found
        if (mysqli_num_rows($result) > 0) {
            // Start a session
            session_start();

            // Fetch user data from the database
            $userData = mysqli_fetch_assoc($result);

            // Store user data in the session

            $_SESSION['username'] = $userData['Username'];
            $_SESSION['role'] = $userData['Role'];

            //check-in
            $userquery = "SELECT UserId FROM userdb WHERE username = '" . $_SESSION['username'] . "'";
            $result = $conn->query($userquery);
            
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['UserId'];
            } else {
                echo "User not found.";
                exit;
            }
            
            $user_id = intval($user_id); // Ensure user_id is an integer
            $sql = "INSERT INTO attendance (user_id, check_in) VALUES ($user_id, NOW())";
            $conn->query($sql);

            // Redirect to a dashboard or home page after successful login  
            if ($userData['Role'] == "admin") {
                //checking role of the user
                header("Location: Dashboard.php");
                exit();
            } else {
                header("Location: UserDashboard.php"); //check garna lai employee rakheko
                exit();
            }
        } else {
            echo "Invalid email or password";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

}
?>