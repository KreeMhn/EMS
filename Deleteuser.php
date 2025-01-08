<?php
include ('components/connect.php');

if (isset($_POST['UserId'])) {
    $userId = intval($_POST['UserId']);
    
    // Start a transaction
    $conn->begin_transaction();

    try {

        // Delete related entries database
        $sql = "DELETE FROM attendance WHERE user_id = $userId";
        if (!$conn->query($sql)) {
            throw new Exception("Error deleting attendance records: " . $conn->error);
        }
        $sql = "DELETE FROM personalinfo WHERE Userid = $userId";
        if (!$conn->query($sql)) {
            throw new Exception("Error deleting attendance records: " . $conn->error);
        }
        $sql = "DELETE FROM tbl_salary WHERE UserId = $userId";
        if (!$conn->query($sql)) {
            throw new Exception("Error deleting attendance records: " . $conn->error);
        }

        // Delete the user from the userdb table
        $sql = "DELETE FROM userdb WHERE UserId = $userId";
        if (!$conn->query($sql)) {
            throw new Exception("Error deleting user: " . $conn->error);
        }

        // Commit the transaction
        $conn->commit();
        echo "User and related records deleted successfully";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log($e->getMessage()); // Log error
        echo $e->getMessage();
    }
} else {
    error_log("User ID not provided"); // Log error
    echo "User ID not provided";
}


?>
