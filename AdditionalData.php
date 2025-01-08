<?php
if (isset($_POST['update'])) {
            // Get additional data from the signup form
            $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
            $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
            $bloodgroup = mysqli_real_escape_string($conn, $_POST['bloodgroup']);
            $gender = mysqli_real_escape_string($conn, $_POST['gender']);
            $tempadd = mysqli_real_escape_string($conn, $_POST['tempadd']);
            $tempcity = mysqli_real_escape_string($conn, $_POST['tempcity']);
            $tempcnty = mysqli_real_escape_string($conn, $_POST['tempcnty']);
            $peradd = mysqli_real_escape_string($conn, $_POST['peradd']);
            $percity = mysqli_real_escape_string($conn, $_POST['percity']);
            $percnty = mysqli_real_escape_string($conn, $_POST['percnty']);
            $degree = mysqli_real_escape_string($conn, $_POST['degree']);
            $institutename = mysqli_real_escape_string($conn, $_POST['institutename']);
            $grade = mysqli_real_escape_string($conn, $_POST['grade']);
            $passedyr = mysqli_real_escape_string($conn, $_POST['passedyr']);
            $edudoc = mysqli_real_escape_string($conn, $_POST['edudoc']);
            $expdesc = mysqli_real_escape_string($conn, $_POST['expdesc']);
            $bankname = mysqli_real_escape_string($conn, $_POST['bankname']);
            $accname = mysqli_real_escape_string($conn, $_POST['accname']);
            $accnum = mysqli_real_escape_string($conn, $_POST['accnum']);
            $pannum = mysqli_real_escape_string($conn, $_POST['pannum']);

            // Insert the user details into the personalinfo table
            $sql = "INSERT INTO `personalinfo`(`Firstname`, `Lastname`,  `Bloodgroup`, `Gender`, `Tempadd`, `Tempcity`, `Tempcnty`, `Peradd`, `Percity`, `Percnty`, `Degree`, `Institutename`, `Grade`, `Passedyr`, `Edudoc`, `Expdesc`, `Bankname`, `Accname`, `Accnum`, `Pannum`) 
            VALUES ('$firstname', '$lastname', '$bloodgroup', '$gender', '$tempadd', '$tempcity', '$tempcnty', '$peradd', '$percity', '$percnty', '$degree', '$institutename', '$grade', '$passedyr', '$edudoc', '$expdesc', '$bankname', '$accname', '$accnum', '$pannum');";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                die("Error: " . mysqli_error($conn));
            } else {
                echo "User personal information saved.";
            }
        }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
</head>
<body>
<form method="post" action="">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <!-- Additional personal information fields -->
    <label for="firstname">First Name:</label>
    <input type="text" name="firstname" id="firstname" required><br>

    <label for="lastname">Last Name:</label>
    <input type="text" name="lastname" id="lastname" required><br>

    <label for="bloodgroup">Blood Group:</label>
    <input type="text" name="bloodgroup" id="bloodgroup"><br>

    <label for="gender">Gender:</label>
    <input type="text" name="gender" id="gender"><br>

    <label for="tempadd">Temporary Address:</label>
    <input type="text" name="tempadd" id="tempadd"><br>

    <label for="tempcity">Temporary City:</label>
    <input type="text" name="tempcity" id="tempcity"><br>

    <label for="tempcnty">Temporary Country:</label>
    <input type="text" name="tempcnty" id="tempcnty"><br>

    <label for="peradd">Permanent Address:</label>
    <input type="text" name="peradd" id="peradd"><br>

    <label for="percity">Permanent City:</label>
    <input type="text" name="percity" id="percity"><br>

    <label for="percnty">Permanent Country:</label>
    <input type="text" name="percnty" id="percnty"><br>

    <label for="degree">Degree:</label>
    <input type="text" name="degree" id="degree"><br>

    <label for="institutename">Institute Name:</label>
    <input type="text" name="institutename" id="institutename"><br>

    <label for="grade">Grade:</label>
    <input type="text" name="grade" id="grade"><br>

    <label for="passedyr">Passed Year:</label>
    <input type="text" name="passedyr" id="passedyr"><br>

    <label for="edudoc">Education Document:</label>
    <input type="text" name="edudoc" id="edudoc"><br>

    <label for="expdesc">Experience Description:</label>
    <input type="text" name="expdesc" id="expdesc"><br>

    <label for="bankname">Bank Name:</label>
    <input type="text" name="bankname" id="bankname"><br>

    <label for="accname">Account Name:</label>
    <input type="text" name="accname" id="accname"><br>

    <label for="accnum">Account Number:</label>
    <input type="text" name="accnum" id="accnum"><br>

    <label for="pannum">PAN Number:</label>
    <input type="text" name="pannum" id="pannum"><br>

    <input type="submit" name="update" value="Update">
</form>

</body>
</html>