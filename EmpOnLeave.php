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

        $sql = "SELECT LID,Empname,Leavetype,Startdate, Enddate,Reason, Status FROM leaverequest where Status='Approved' ";
        $result = $conn->query($sql);
        if (!$result) {
            trigger_error('Invalid query: ' . $conn->error);
        } else {
            if ($result->num_rows > 0) {
                // Output data of each row
                echo "<table border='3'>";
                echo "<tr><th>Leave ID</th><th>Employee Name</th><th>Leave Type</th><th>Startdate</th>
                <th>Enddate</th><th>Reason</th><th>Status</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["LID"] . "</td><td>" . $row["Empname"] . "</td><td>" . $row["Leavetype"] . "</td>
                <td>" . $row["Startdate"] . "</td><td>" . $row["Enddate"] . "</td>
                <td>" . $row["Reason"] . "</td><td>" . $row["Status"] . "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }

        }


        ?>
    </div>
</div>