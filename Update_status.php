<?php
require 'vendor/autoload.php';
include('./components/connect.php');
include('userclass.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start(); // Ensure session is started to use session variables

// Instantiate the Admin class for updating leave status
$admin = new Admin($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the necessary parameters are set in POST request
    if (isset($_POST['lid']) && isset($_POST['status']) && isset($_POST['uname'])) {
        $lid = $_POST['lid'];
        $status = $_POST['status'];
        $uname = $_POST['uname'];

        // Call the method to update leave status in Admin class
        $result = $admin->updateLeaveStatus($lid, $status);

        // Fetch the email address of the user who made the leave request
        $stmt = $conn->prepare("SELECT Email FROM userdb WHERE Username = ?");
        if ($stmt) {
            $stmt->bind_param("s", $uname);
            $stmt->execute();
            $stmt->bind_result($to);
            $stmt->fetch();
            $stmt->close();

            if ($to) {
                // Prepare email details
                $subject = 'Decision on Leave Request';
                $message = "The leave you requested has been $status.";

                // Send email
                if ($result) {
                    if (sendEmail($to, $subject, $message)) {
                        echo "Success: Email sent.";
                    } else {
                        echo "Success: Email not sent.";
                    }
                } else {
                    echo "Error: Leave status update failed.";
                }
            } else {
                echo "Error: Email address not found for user.";
            }
        } else {
            echo "Error: Failed to prepare SQL statement.";
        }
    } else {
        echo "Invalid parameters.";
    }
} else {
    echo "Invalid request method.";
}

// Function to send email using PHPMailer
function sendEmail($to, $subject, $message)
{
    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 2; // Enable verbose debug output
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
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    } catch (Error $err) {
        error_log('Error: ' . $err->getMessage());
        return false;
    }
}
?>
