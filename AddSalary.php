<?php
include ('./components/connect.php');
include ('./components/navbar.php');
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
if (isset($_POST['Add_salary'])) {
    $username = $_POST['Empname'];
    
    // Query to retrieve the UserId based on the provided username
    $sql_user = "SELECT `UserId` FROM `userdb` WHERE Username='$username'";
    $result_user = $conn->query($sql_user);
    
    if ($result_user->num_rows > 0) {
        // Fetch the UserId from the result set
        $row = $result_user->fetch_assoc();
        $id = $row['UserId'];
        
        $salary = $_POST['Salary'];
        
        // Calculate Payperday
        $payperday = intval($salary) / 30; 
        
        // Insert the salary details into tbl_salary
        $sql_insert = "INSERT INTO `tbl_salary`(`UserId`, `Name`, `Salary`, `Payperday`)
                       VALUES ('$id', '$username', '$salary', '$payperday')";
        $result_insert = mysqli_query($conn, $sql_insert);
        
        if (!$result_insert) {
            die("Error: " . mysqli_error($conn));
        } else {
            echo "Salary added successfully";
        }
    } else {
        echo "User not found.";
    }
}

?>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .main-content {
            background-color: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        
        .main-content form {
            display: flex;
            flex-direction: column;
        }
        
        .main-content label {
            margin-bottom: 5px;
        }
        
        .main-content select,
        .main-content input[type="number"],
        .main-content button {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
        }
        
        .main-content select {
            width: 100%;
        }
        
        .main-content button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .main-content button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <form action="" method="post">
            <label for="Empname">Employee Name:</label>
            <select name="Empname">
                <?php
                // Check if there are any options in the array
                if (!empty($data)) {
                    // Loop through the array to generate options
                    foreach ($data as $row) {
                        // Output an option for each row in the array
                        echo "<option value=\"{$row['Username']}\">{$row['Username']}</option>";
                    }
                } else {
                    // If no options are found, display a default option
                    echo "<option value=\"\">No options available</option>";
                }
                ?>
            </select>

            <label for="Salary">Salary</label>
            <input type="number" name="Salary">

            <button type="submit" name="Add_salary">Add Salary</button>
        </form>
    </div>
</body>
</html>
