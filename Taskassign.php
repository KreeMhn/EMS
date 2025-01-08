<?php

include ('./components/navbar.php');
include ("./components/connect.php");

function isLoggedIn()
{

    // Check if the 'username' session variable exists
    return isset($_SESSION['username']);
}

// If the user is not logged in, redirect them to the login page
if (!isLoggedIn()) {
    echo "<script>
    // Redirect to index.php after the page has loaded
    window.location.href = 'index.php';
</script>";
    exit;
}
$today = date("Y-m-d");
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

//task assigned sent to database
if (isset($_POST['assign'])) {
    //process for login
    //1. get the data from login form

    $desc = $_POST['desc'];
    $ddl = $_POST['ddl'];
    $assignto = $_POST['assignto'];

    $insertsql = "INSERT INTO `task`(`Details`, `Deadline`, `Assigned`) VALUES ('$desc', '$ddl', '$assignto');";
    $result = mysqli_query($conn, $insertsql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


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

    .task-assign {
        padding: 1rem;
        margin: 1rem;
        border: 1px solid black;
        color: black;
        height: auto;
        width: 45%;
        font-family: Arial, Helvetica, sans-serif;
        float: left;
        /* text-align: center; */
    }

    input,
    select {
        border-radius: 20px;
    }

    .btn-primary {
        border: none;
        margin: 15px;
        color: blueviolet;
        padding: 15px 32px;
        text-align: center;
        border-radius: 30px;
        text-decoration: none;
        font-size: 16px;
    }

    .btn-primary:hover {
        background-color: rgb(37, 150, 190);
        color: white;
    }

    .fa-in {
        padding: 1rem;
        /* margin-top: 1rem; */
    }
</style>
<div class="bottom-half-content">
    <div class="task-assign">
        <form action="" method="post">
            <h2 style="text-align: center;">ASSIGN TASK</h2>
            <label for="desc">
                <h3>Description</h3>
            </label><br>
            <input type="text" name="desc" class="fa-in2" style="width: 100%;
                            padding: 12px 20px;
                            margin: 8px 0;
                            box-sizing: border-box;"><br>
            <br>
            <label for="ddl">
                <h3>Deadline date</h3>
            </label><br>
            <input type="date" class="fa-in" name="ddl" min="<?php echo $today; ?>" required /><br>
            <br>
            <label for="assignto">
                <h3>Assign To</h3>
            </label><br>
            <select class="fa-in" name="assignto">
                <option selected="selected">Choose one</option>
                <?php
                // Iterating through the product array
                foreach ($data as $item) {
                    // Accessing the 'Username' key from each row of the array
                    echo "<option value='" . strtolower($item['Username']) . "'>" . $item['Username'] . "</option>";
                }
                ?>
            </select><br>
            <div class="btn-container">
                <button class="btn-primary" name="assign">
                    <h3>Assign</h3>
                </button>
            </div>
        </form>
    </div>
</div>