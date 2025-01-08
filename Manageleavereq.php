<?php
include('userclass.php');
include('./components/navbar.php');
include('./components/connect.php');

// Instantiate the Leave class and calculate leave durations
$leave = new Leave($conn);
$leaveDurations = $leave->calcDuration();

// Instantiate the Admin class for updating leave status
$admin = new Admin($conn);
?>

<style>
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

    .action-button {
        padding: 8px 16px;
        margin: 4px;
        border: none;
        color: white;
        cursor: pointer;
        font-size: 14px;
        border-radius: 4px;
    }

    .approve-button {
        background-color: #4CAF50; /* Green */
    }

    .approve-button:hover {
        background-color: #45a049;
    }

    .decline-button {
        background-color: #f44336; /* Red */
    }

    .decline-button:hover {
        background-color: #da190b;
    }
</style>

<div class="main-content">
    <div class="leave-req-view">
        <table>
            <tr>
                <th>Leave ID</th>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Leave Duration</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            if ($leaveDurations !== false && count($leaveDurations) > 0) {
                foreach ($leaveDurations as $leave) {
                    echo "<tr>";
                    echo "<td>" . $leave["LID"] . "</td>";
                    echo "<td>" . $leave["Empname"] . "</td>";
                    echo "<td>" . $leave["Leavetype"] . "</td>";
                    echo "<td>" . $leave["Leaveduration"] . "</td>";
                    echo "<td>" . $leave["Startdate"] . "</td>";
                    echo "<td>" . $leave["Enddate"] . "</td>";
                    echo "<td>" . $leave["Reason"] . "</td>";
                    echo "<td>" . $leave["Status"] . "</td>";
                    echo "<td>
                            <button class='action-button approve-button' onclick='approveLeave(" . $leave["LID"] . ", \"" . $leave["Empname"] . "\")'>Approve</button>
                            <button class='action-button decline-button' onclick='declineLeave(" . $leave["LID"] . ", \"" . $leave["Empname"] . "\")'>Decline</button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No leave requests found</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
function approveLeave(lid, uname) {
    if (confirm("Are you sure you want to approve this leave?")) {
        updateLeaveStatus(lid, 'Approved', uname);
    }
}

function declineLeave(lid, uname) {
    if (confirm("Are you sure you want to decline this leave?")) {
        updateLeaveStatus(lid, 'Declined', uname);
    }
}

function updateLeaveStatus(lid, status, uname) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Reload the page after updating
            location.reload();
        }
    };
    xhr.send("lid=" + encodeURIComponent(lid) + "&status=" + encodeURIComponent(status) + "&uname=" + encodeURIComponent(uname));
}
</script>
