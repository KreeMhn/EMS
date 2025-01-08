<?php
include ('components/connect.php');

// Check if the necessary data is provided
if (isset($_POST['UserId'])) {
    $userId = intval($_POST['UserId']); // Sanitize the user input by converting it to an integer

    // Prepare and execute the SQL query to make admin
    $sql = "UPDATE userdb SET Role='admin' WHERE UserId=$userId";
    if ($conn->query($sql) === TRUE) {
        echo "Role updated successfully!";
    } else {
        echo "Error updating role: " . $conn->error;
    }
} else {
    echo "User ID not provided";
}

$conn->close();
?>