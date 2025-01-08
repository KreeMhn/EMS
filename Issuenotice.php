<?php
require 'vendor/autoload.php';
include ('./components/navbar.php');
include ('./components/connect.php');
include('userclass.php'); // Include the class definition

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

if (isset($_POST['submit'])) {
    // Process for issuing notice
    // 1. Get the data from the form
    $date = $_POST['date'];
    $details = $_POST['details'];

    // Server-side validation
    if (empty($date) || empty($details)) {
        $error = "All fields are required.";
    } else {
        // Instantiate the Admin class (assuming you have a class for managing notices)
        $admin = new Admin($conn);

        // Call the issueNotice method to save notice in database
        $result = $admin->issueNotice($date, $details);

        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }

        // Fetch all user emails from the database
        $query = "SELECT `Email` FROM `userdb`";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            die("Error fetching emails: " . mysqli_error($conn));
        }

        $emailsSent = true;

        // Loop through each email and send the notice
        while ($row = mysqli_fetch_assoc($result)) {
            $to = $row['Email'];
            $subject = 'New Notice Issued';
            $message = "A new notice has been issued.\n\nDate: $date\nDetails:\n$details";

            if (!sendEmail($to, $subject, $message)) {
                $emailsSent = false;
            }
        }

        if ($emailsSent) {
            echo "<script>alert('Notice issued successfully and emails sent.');</script>";
        } else {
            echo "<script>alert('Notice issued but some emails could not be sent.');</script>";
        }

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Function to send email using PHPMailer
function sendEmail($to, $subject, $message)
{
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'krimhn10@gmail.com'; // Replace with your Gmail email
        $mail->Password = 'ojfqwceqnkwwdpmf'; // Replace with your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('krimhn10@gmail.com', 'Krinesh Maharjan');
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (\Exception $e) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    } catch (\Error $err) {
        echo 'Error: ' . $err->getMessage();
        return false;
    }
}
?>

<div class="main-content">
    <div class="noticeform">
        <div class="popup-box">
            <h2 style="color: green;">Notice</h2>
            <?php if (isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="form-container" method="post" onsubmit="return validateForm()">
            <label class="form-label" for="date">Issue Date</label>
            <input class="form-input" type="date" id="date" name="date"
             required min="<?php echo date('Y-m-d'); ?>">

                <label class="form-label" for="details">Details</label>
                <textarea class="form-input" name="details" id="details" rows="10" required></textarea><br>

                <button class="btn-submit" type="submit" name="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var date = document.getElementById("date").value;
    var details = document.getElementById("details").value;

    if (date === "" || details === "") {
        alert("All fields must be filled out");
        return false;
    }
    return true;
}
</script>

<style>
    .form-container {
        display: flex;
        flex-direction: column;
    }

    .form-label {
        margin-bottom: 10px;
        font-size: 16px;
        color: #444;
        text-align: left;
    }

    .form-input {
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        width: 100%;
        box-sizing: border-box;
    }

    .btn-submit {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-submit {
        background-color: green;
        color: #fff;
    }

    .btn-submit:hover {
        background-color: #4caf50;
    }

    .main-content {
        position: relative;
        background-color: #eee;
        min-height: 100vh;
        top: 0;
        left: 80px;
        transition: all 0.5s ease;
        width: calc(100% - 80px);
        padding: 1rem;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th,
    td {
        text-align: left;
        padding: 16px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>


