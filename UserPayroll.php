<?php

include ('./components/usernavbar.php');
include ("./components/connect.php");

function isLoggedIn()
{
    return isset($_SESSION['username']);
}

if (!isLoggedIn()) {
    echo "<script>
    window.location.href = 'index.php';
</script>";
    exit;
}

if (isset($_POST['advance_salary'])) {
    $reqType = 'Advance Salary';
    $empname = $_SESSION['username'];
    $eid;

    // Fetch the UserId for the given Username
    $sql = "SELECT UserId FROM userdb WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $empname);
    $stmt->execute();
    $stmt->bind_result($eid);
    $stmt->fetch();
    $stmt->close();

    // Insert the request into the request table
    $stmt = $conn->prepare("INSERT INTO request (ReqType, Empname, eid) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $reqType, $empname, $eid);

    if ($stmt->execute()) {
        echo '<script>window.location.href = "UserPayroll.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

if (isset($_POST['raise_salary'])) {    
    $reqType = 'Raise Salary';
    $empname = $_SESSION['username'];
    $eid;

    // Fetch the UserId for the given Username
    $sql = "SELECT UserId FROM userdb WHERE Username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $empname);
    $stmt->execute();
    $stmt->bind_result($eid);
    $stmt->fetch();
    $stmt->close();

    // Insert the request into the request table
    $stmt = $conn->prepare("INSERT INTO request (ReqType, Empname, eid) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $reqType, $empname, $eid);

    if ($stmt->execute()) {
        echo '<script>window.location.href = "UserPayroll.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .main-content {
            margin-left: 30px;
            padding: 2rem;
            transition: margin-left 0.5s;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 4rem);
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        button {
            border: none;
            padding: 15px 32px;
            margin: 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }

        button[name="advance_salary"] {
            background-color: #4CAF50;
            color: white;
        }

        button[name="advance_salary"]:hover {
            background-color: #45a049;
        }

        button[name="raise_salary"] {
            background-color: #008CBA;
            color: white;
        }

        button[name="raise_salary"]:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <form method="post" action="">
                <button type="submit" name="advance_salary">Request Advance Payment</button>
                <button type="submit" name="raise_salary">Request Raise</button>
            </form>
        </div>
    </div>
</body>
</html>
