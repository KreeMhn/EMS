<?php
include ('./components/navbar.php');
include ('./components/connect.php');
if (isset($_POST['ID'])) {
    $Id = $_POST['ID'];

    // Prepare and execute the SQL query to delete the leave type
    $sql = "DELETE FROM leave_type WHERE ID = $Id";
    if ($conn->query($sql) === TRUE) {
        echo "Leave type deleted successfully";
    } else {
        echo "Error deleting leave type: " . $conn->error;
    }
} else {
    echo "Leave type ID not provided";
}
?>

// Check if the user ID is provided
if (isset($_POST['ID'])) {
$Id = $_POST['ID'];

// Prepare and execute the SQL query to delete the user
$sql = "DELETE FROM Leave_type WHERE ID = $Id";
if ($conn->query($sql) === TRUE) {
echo "User deleted successfully";
} else {
echo "Error deleting user: " . $conn->error;
}
} else {
echo "User ID not provided";
}

?>