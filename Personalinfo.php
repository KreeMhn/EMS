<?php
include('userclass.php');
include ('./components/navbar.php');
include ('./components/connect.php');

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

$username = $_SESSION['username'];
// Escape the username to avoid SQL injection
$username_escaped = $conn->real_escape_string($username);

$sql = "SELECT UserId FROM userdb WHERE Username = '$username_escaped'";
$res = $conn->query($sql);

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $userId = $row['UserId'];
} else {
    echo "User not found";
    exit;
}

// Create an instance of the Employee class
$employee = new Employee($conn);

// Call the viewProfile method
$result = $employee->viewProfile($userId);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found";
    exit;
}
// call the Update Profile method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateResponse = $employee->updateProfile($userId);
}


if (isset($_POST['psw'])) {
    $currentPassword = sha1($_POST['Currentpsw']);
    $newPassword = sha1($_POST['Newpsw']);
    $retypePassword = sha1($_POST['Renewpsw']);

    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($retypePassword)) {
        echo "All fields are required.";
    } elseif ($newPassword !== $retypePassword) {
        echo "New passwords do not match.";
    } else {
        // Get the current user's ID (assuming you have stored it in session or retrieved it)
        $username = $_SESSION['username'];

        // Fetch user data to check current password
        $sql = "SELECT * FROM `userdb` WHERE `Username` = '$username' AND `Password` = '$currentPassword'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            // Update password
            $updateSql = "UPDATE `userdb` SET `Password` = '$newPassword' WHERE `Username` = '$username'";
            $updateResult = mysqli_query($conn, $updateSql);

            if ($updateResult) {
                echo "Password updated successfully.";
            } else {
                echo "Error updating password: " . mysqli_error($conn);
            }
        } else {
            echo "Incorrect current password.";
        }
    }
}


?>


<html>

<head>
    <style>
        .main-content {
            position: relative;
            background-color: #fff;
            min-height: 100vh;
            top: 0;
            left: 80px;
            transition: all 0.5s ease;
            width: calc(100% - 80px);
            padding: 1rem;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: blueviolet;
            color: #ccc;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            color: white;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: white;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: white;
            color: black;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        input {
            border-radius: 20px;
            margin-bottom: 20px;
            width: 30%;
            height: 5vh;
            text-align: center;
        }

        /* css for radio start */
        .radiocontainer {
            margin-bottom: 20px;
        }

        .radiocontainer form {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
        }

        .radiocontainer label {
            display: flex;
            cursor: pointer;
            font-weight: 500;
            position: relative;
            overflow: hidden;
            margin-bottom: 0.375em;
        }

        .radiocontainer label input {
            position: absolute;
            left: -9999px;
        }

        .radiocontainer label input:checked+span {
            background-color: #414181;
            color: white;
        }

        .radiocontainer label input:checked+span:before {
            box-shadow: inset 0 0 0 0.4375em #00005c;
        }

        .radiocontainer label span {
            display: flex;
            align-items: center;
            padding: 0.375em 0.75em 0.375em 0.375em;
            border-radius: 99em;
            transition: 0.25s ease;
            color: #414181;
        }

        .radiocontainer label span:hover {
            background-color: #d6d6e5;
        }

        .radiocontainer label span:before {
            display: flex;
            flex-shrink: 0;
            content: "";
            background-color: #fff;
            width: 1.5em;
            height: 1.5em;
            border-radius: 50%;
            margin-right: 0.375em;
            transition: 0.25s ease;
            box-shadow: inset 0 0 0 0.125em #00005c;
        }

        button {
        background-color: #4CAF50; /* Green background */
        color: white; /* White text */
        padding: 10px 20px; /* Padding inside the button */
        border: none; /* No border */
        cursor: pointer; /* Pointer cursor */
        border-radius: 5px; /* Rounded corners */
        font-size: 16px; /* Font size */
        }

        /* Hover effect */
        button:hover {
            background-color: #45a049; /* Darker green */
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="tab">
            <button class="tablinks" onclick="opentab(event, 'Personal')" id="defaultOpen">Personal Info</button>
            <button class="tablinks" onclick="opentab(event, 'Address')">Address</button>
            <button class="tablinks" onclick="opentab(event, 'Education')">Education</button>
            <button class="tablinks" onclick="opentab(event, 'Experience')">Experience</button>
            <button class="tablinks" onclick="opentab(event, 'Bank Account')">Bank Account</button>
            <button class="tablinks" onclick="opentab(event, 'Change Password')">Change Password</button>
        </div>

        <!-- Tab content -->
        <div id="Personal" class="tabcontent">
            <form method="post">
                <label for="eid">Employee ID</label><br>
                <input type="text" name="eid" value="<?php echo htmlspecialchars($user['Userid']); ?>"><br>
                <label for="fname">First Name</label><br>
                <input type="text" name="fname" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Firstname']); ?>"><br>
                <label for="lname">Last Name</label><br>
                <input type="text" name="lname" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Lastname']); ?>"><br>
                <label for="bldgrp">Blood Group</label><br>
                <input type="text" name="bldgrp" value="<?php echo htmlspecialchars($user['Bloodgroup']); ?>"><br>
                <label for="gender">Gender</label><br>
                <div class="radiocontainer">
                    <label>
                        <input type="radio" name="gender" value="Male" <?php echo ($user['Gender'] == 'Male') ? 'checked' : ''; ?>>
                        <span>Male</span>
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Female" <?php echo ($user['Gender'] == 'Female') ? 'checked' : ''; ?>>
                        <span>Female</span>
                    </label>
                    <label>
                        <input type="radio" name="gender" value="Other" <?php echo ($user['Gender'] == 'Other') ? 'checked' : ''; ?>>
                        <span>Other</span>
                    </label>
                </div>
                <button type="submit" name="personal">Update Personal Info</button>
            </form>
        </div>

        <div id="Address" class="tabcontent">
            <form method="post">
                <legend>
                    <h3>Temporary Address</h3>
                </legend><br>
                <label for="taddress">Address</label><br>
                <input type="text" name="taddress" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Tempadd']); ?>"><br>
                <label for="tcity">City</label><br>
                <input type="text" name="tcity" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Tempcity']); ?>"><br>
                <label for="tcountry">Country</label><br>
                <input type="text" name="tcountry" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Tempcnty']); ?>"><br>

                <legend>
                    <h3>Permanent Address</h3>
                </legend><br>
                <label for="paddress">Address</label><br>
                <input type="text" name="paddress" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Peradd']); ?>"><br>
                <label for="pcity">City</label><br>
                <input type="text" name="pcity" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Percity']); ?>"><br>
                <label for="pcountry">Country</label><br>
                <input type="text" name="pcountry" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Percnty']); ?>"><br>
                <button type="submit" name="address">Update Personal Info</button>
            </form>
        </div>

        <div id="Education" class="tabcontent">
            <form method="post">
                <label for="degreename">Degree Name</label><br>
                <input type="text" name="degreename" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Degree']); ?>"><br>
                <label for="institutename">Institute Name</label><br>
                <input type="text" name="institutename"
                    value="<?php echo htmlspecialchars($user['Institutename']); ?>"><br>
                <label for="result">Result</label><br>
                <input type="text" name="result" value="<?php echo htmlspecialchars($user['Grade']); ?>"><br>
                <label for="passingyear">Passing Year</label><br>
                <input type="text" name="passingyear" value="<?php echo htmlspecialchars($user['Passedyr']); ?>"><br>
                <button type="submit" name="education">Update Personal Info</button>
            </form>
        </div>

        <div id="Experience" class="tabcontent">
            <form method="post">
                <label for="Experience">Describe previous work experience</label><br>
                <textarea name="Experience" cols="30"
                    rows="10"><?php echo htmlspecialchars($user['Expdesc']); ?></textarea>
                    <button type="submit" name="experience">Update Personal Info</button>
            </form>
        </div>

        <div id="Bank Account" class="tabcontent">
            <form method="post">
                <label for="Bankname">Bank Name</label><br>
                <input type="text" name="Bankname" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Bankname']); ?>"><br>
                <label for="Accountname">Account Name</label><br>
                <input type="text" name="Accountname" pattern="[A-Za-z]+" value="<?php echo htmlspecialchars($user['Accname']); ?>"><br>
                <label for="Accountnumber">Account Number</label><br>
                <input type="text" name="Accountnumber" value="<?php echo htmlspecialchars($user['Accnum']); ?>"><br>
                <label for="PANnumber">PAN Number</label><br>
                <input type="text" name="PANnumber" value="<?php echo htmlspecialchars($user['Pannum']); ?>"><br>
                <button type="submit" name="bank">Update Personal Info</button>
            </form>
        </div>

        <div id="Change Password" class="tabcontent">
            <form method="post">
                <label for="Currentpsw">Current Password</label><br>
                <input type="password" name="Currentpsw"><br>
                <label for="Newpsw">New Password</label><br>
                <input type="password" name="Newpsw"><br>
                <label for="Renewpsw">Re-type New Password</label><br>
                <input type="password" name="Renewpsw"><br>
                <button type="submit" name="psw">Update Personal Info</button>
            </form>
        </div>
     
    </div>

    <script>
        document.getElementById("defaultOpen").click();
        function opentab(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>

</html>



