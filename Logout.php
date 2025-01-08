<?php
// logout.php

include('./components/connect.php');
session_start();

// Check if the session variable 'username' is set
if (isset($_SESSION['username'])) {
    $username = $conn->real_escape_string($_SESSION['username']);

    // Query to fetch user ID
    $userquery = "SELECT UserId FROM userdb WHERE username = '$username'";
    $result = $conn->query($userquery);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = intval($row['UserId']); // Ensure user_id is an integer

        // Update the attendance table
        $updatequery = "UPDATE attendance SET check_out = NOW() WHERE user_id = $user_id AND check_out IS NULL";
        $conn->query($updatequery);

        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page or any other desired location
        header("Location: index.php"); // Assuming your login page is named index.php
        exit();
    } else {
        echo "User not found.";
    }
} else {
    echo "User is not logged in.";
}


?>
