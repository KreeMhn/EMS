<?php
include ('./components/usernavbar.php');
include ("./components/connect.php");

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

$username = $_SESSION['username'];

?>
<style>
    .bottom-half-content {
        position: relative;
        min-height: 100vh;
        top: 0;
        left: 80px;
        transition: all 0.5s ease;
        width: calc(100% - 80px);
        padding: 2rem;
        margin-left: 1rem;

    }

    .task-view {
        padding: 1rem;
        margin: 1rem;
        /* border: 1px solid black; */
        color: black;
        height: auto;
        width: 70%;
        font-family: Arial, Helvetica, sans-serif;
        float: left;
        /* text-align: center; */
    }

    table {
        border-color: black;
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        padding: 20px;
        margin: 5px;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .update-form {
        display: inline;
    }

    .update-btn {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 8px 12px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 14px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }
</style>
<div class="bottom-half-content">
    <div class="task-view">
        <h1 style="text-align: center; margin-bottom: 10px;">TASK IN PROGRESS</h1>
        <?php
        $sql = "SELECT SN, Details, Deadline, Assigned, Status FROM task WHERE Assigned='$username'";
        $result = $conn->query($sql);
        if (!$result) {
            echo "Task not assigned to you.";
        } else {
            if ($result->num_rows > 0) {
                echo "<table border='3'>";
                echo "<tr><th>S.N</th><th>Details</th><th>Deadline</th><th>Assigned to:</th><th>Status</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<form class='update-form' method='post'>";
                    echo "<input type='hidden' name='task_id' value='" . $row["SN"] . "'>";
                    echo "<tr><td>" . $row["SN"] . "</td><td>" . $row["Details"] . "</td>
                                <td>" . $row["Deadline"] . "</td><td>" . $row["Assigned"] . "</td><td>" . $row["Status"] . "</td>";
                    echo "<td><button type='submit' class='update-btn' name='update_status'>Update Status</button></td></tr>";
                    echo "</form>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
        }

        // Process update status action
        if (isset($_POST['update_status'])) {
            $task_id = $_POST['task_id'];
            // Update task status logic goes here
            // Example: Update the status in the database based on $task_id
            // Replace the below example logic with your actual update logic
            $update_sql = "UPDATE task SET Status='Completed' WHERE SN='$task_id'";
            if ($conn->query($update_sql) === TRUE) {
                echo "<script>alert('Task status updated successfully');
                window.location.href = 'UserTaskView.php';</script>";
            } else {
                echo "Error updating task status: " . $conn->error;
            }
        }
        ?>
    </div>
</div>
