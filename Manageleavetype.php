<?php
include ('./components/navbar.php');
include ('./components/connect.php');

if (isset($_POST['btn-add'])) {
    if (empty($_POST['Id']) || empty($_POST['LeaveType']) || empty($_POST['Days'])) {
        echo '<script>alert("Please fill in all the fields")</script>';
    } else {
        $id = $_POST['Id'];
        $leave_type = $_POST['LeaveType'];
        $days = $_POST['Days'];

        $insertsql = "INSERT INTO `leave_type`(`ID`, `Leavetype`, `Days`) VALUES ('$id', '$leave_type', '$days');";
        $result = mysqli_query($conn, $insertsql);
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        // Redirect to prevent form resubmission
        echo '<script>window.location.href = "Manageleavetype.php";</script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Types</title>
    <style>
        body {
            background-color: white;
        }
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
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
            padding: 16px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-del {
            background-color: red;
            height: 23px;
            width: 80px;
            color: #fff;
        }
        .add-container {
            margin: 20px;
            padding: 10px;
            border: 1px solid black;
            width: 40%;
        }
        input {
            border-radius: 20px;
            margin-bottom: 20px;
            height: 30px;
            width: 100%;
        }
        .btn-primary {
            border: none;
            margin: 15px;
            background-color: green;
            color: white;
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
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Add/Remove Leave Type</h1>
        <div class="top-half">
            <div class="add-container">
                <form action="" method="post">
                    <h2 style="margin-bottom: 20px;">Add Leave Type</h2>
                    <label for="Id">ID</label><br>
                    <input type="text" name="Id" required><br>
                    <label for="LeaveType">Leave Type</label><br>
                    <input type="text" name="LeaveType" required><br>
                    <label for="Days">Days</label><br>
                    <input type="number" name="Days" required><br>
                    <button type="submit" class="btn-primary" name="btn-add">Add</button>
                </form>
            </div>
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
                    echo "<tr><th>ID</th><th>Leave Type</th><th>Days</th><th>Action</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Leavetype"] . "</td><td>" . $row["Days"] . "</td>" .
                            "<td><button class='btn-del' onclick='deleteLeave(" . $row["ID"] . ")'>Delete</button></td></tr>";
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
        function deleteLeave(ID) {
            if (confirm("Are you sure you want to delete this type?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Deleteleavetype.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Reload the page after deletion
                        location.reload();
                    }
                };
                xhr.send("ID=" + ID);
            }
        }
    </script>
</body>
</html>
