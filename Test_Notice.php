<?php
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


if (isset($_POST['submit'])) {
    //process for login
    //1. get the data from login form

    $date = $_POST['date'];
    $details = $_POST['details'];
    $file = $_POST['file'];
    $insertsql = "INSERT INTO `notice`(`Date`, `Details`, `File`) VALUES ('$date', '$details', '$file');";
    $result = mysqli_query($conn, $insertsql);
    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Form</title>
    <style>
        /* body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
           
            height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        } */

        .btn-open-popup {
            padding: 12px 24px;
            font-size: 18px;
            background-color: green;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-open-popup:hover {
            background-color: #4caf50;
        }

        .overlay-container {
            display: none;
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* background: rgba(0, 0, 0, 0.6); */
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .popup-box {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
            width: 320px;
            text-align: center;
            opacity: 0;
            transform: scale(0.8);
            animation: fadeInUp 0.5s ease-out forwards;
        }

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

        .btn-submit,
        .btn-close-popup {
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

        .btn-close-popup {
            margin-top: 12px;
            background-color: #e74c3c;
            color: #fff;
        }

        .btn-submit:hover,
        .btn-close-popup:hover {
            background-color: #4caf50;
        }

        /* Keyframes for fadeInUp animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation for popup */
        .overlay-container.show {
            display: flex;
            opacity: 1;
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
</head>

<body>
    <div class="main-content">
        <div class="noticeform">
            <button class="btn-open-popup" onclick="togglePopup()">
                Issue Notice
            </button>

            <div id="popupOverlay" class="overlay-container">
                <div class="popup-box">
                    <h2 style="color: green;">Notice</h2>
                    <form class="form-container" method="post">
                        <label class="form-label" for="date">Date </label>
                        <input class="form-input" type="date" id="date " name="date" required>

                        <label class="form-label" for="details">Details</label>
                        <textarea name="details" rows="10" required></textarea><br>
                        <label class="form-label" for="file">File To Be Attached</label>
                        <input class="form-input" type="file" id="file" name="file">

                        <button class="btn-submit" type="submit" name="submit">
                            Submit
                        </button>
                    </form>

                    <button class="btn-close-popup" onclick="togglePopup()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        <?php





        $sql = "SELECT Date, Details, File FROM notice";
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            // Output data of each row
            echo "<table border='1'>";
            echo "<tr><th>Date</th><th>Details</th><th>File</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["Date"] . "</td><td>" . $row["Details"] . "</td><td>" . $row["File"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        ?>

    </div>

    <script>
        function togglePopup() {
            const overlay = document.getElementById('popupOverlay');
            overlay.classList.toggle('show');
        } 
    </script>
</body>

</html>