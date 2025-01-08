<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/indexstyle.css">
    <title>EMS Login Page </title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post">
                <h1>Create Account</h1>
                <input type="text" placeholder="Username" name="username" required>
                <input type="email" placeholder="Email" name="email" required>
                <input type="password" placeholder="Password" name="password" required>
                <button name="signup">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post">
                <h1>Sign In</h1>
                <input type="text" placeholder="Username" name="username">
                <input type="password" placeholder="Password" name="password">
                <a href="#">Forget Your Password?</a>
                <button name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <script src="js/indexscript.js"></script>
</body>

</html>
<?php
include ('userclass.php');
include ('./components/connect.php');

// Function to validate username
function validateUsername($username) {
    return preg_match('/^[a-zA-Z]+$/', $username);
}

if (isset($_POST['signup'])) {
    // Get the data from the signup form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    // Server-side validation
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!validateUsername($username)) {
        $error = "Username must contain only letters.";
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        $sql = "INSERT INTO `userdb`(`Username`, `Email`, `Password`)
         VALUES ('$username', '$email', '$password');";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Error: " . mysqli_error($conn));
        } else {
            echo "User registered successfully";
            $userId = mysqli_insert_id($conn);
            $sql = "INSERT INTO `personalinfo`( `Userid`) VALUES ( '$userId');";
            $result = mysqli_query($conn, $sql);
        }
    }
}


$attendance = new Attendance($conn);

if (isset($_POST['login'])) {
    // Process login form
    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    // Validate user credentials
    $sql = "SELECT * FROM `userdb` WHERE `Username` = '$username' AND `Password` = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Check if a matching user is found
        if (mysqli_num_rows($result) > 0) {
            // Start session and store user data
            session_start();
            $userData = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $userData['Username'];
            $_SESSION['role'] = $userData['Role'];

            // Fetch user ID from database
            $userquery = "SELECT UserId FROM userdb WHERE username = '" . $_SESSION['username'] . "'";
            $result = $conn->query($userquery);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['UserId'];
            } else {
                echo "User not found.";
                exit;
            }

            // Mark attendance for the logged-in user
            $attendance->markAttendance($user_id);

            // Redirect based on user role
            if ($userData['Role'] == "admin") {
                header("Location: Dashboard.php");
                exit();
            } else {
                header("Location: UserDashboard.php");
                exit();
            }
        } else {
            echo "Invalid username or password";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>