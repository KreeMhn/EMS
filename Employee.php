<?php
include ('./components/navbar.php');
include ('./components/connect.php');

function isLoggedIn()
{
    return isset($_SESSION['username']);
}

if (!isLoggedIn()) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
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
        th, td {
            text-align: left;
            padding: 16px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn-del, .btn-admin {
            background-color: red;
            height: 23px;
            width: 80px;
            color: #fff;
            margin-right: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        .btn-admin {
            background-color: blue;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="container">
            <h1>Employee</h1>
        </div>
        <?php
        $sql = "SELECT UserId, Username, Email, Role FROM userdb";
        $result = $conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $conn->error);
        } else {
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>UserId</th><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["UserId"] . "</td>
                            <td>" . $row["Username"] . "</td>
                            <td>" . $row["Email"] . "</td>
                            <td>" . $row["Role"] . "</td>
                            <td>
                            <button class='btn-admin' onclick='makeAdmin(" . $row["UserId"] . ")'>Make Admin</button>
                            <button class='btn-del' onclick='deleteUser(" . $row["UserId"] . ")'>Delete User</button>
                            </td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
        }
        ?>
    </div>
    <script>
        function deleteUser(UserId) {
            if (confirm("Are you sure you want to delete this user?")) {
                console.log("Deleting user with ID:", UserId); // Debug log
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Deleteuser.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            console.log("Response from server:", xhr.responseText); // Debug log
                            location.reload();
                        } else {
                            console.log("Error: " + xhr.status); // Debug log
                        }
                    }
                };
                xhr.send("UserId=" + UserId);
            }
        }

        function makeAdmin(UserId) {
            if (confirm("Are you sure you want to make this user an admin?")) {
                console.log("Making user admin with ID:", UserId); // Debug log
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Changerole.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            console.log("Response from server:", xhr.responseText); // Debug log
                            location.reload();
                        } else {
                            console.log("Error: " + xhr.status); // Debug log
                        }
                    }
                };
                xhr.send("UserId=" + UserId);
            }
        }
    </script>
</body>
</html>
