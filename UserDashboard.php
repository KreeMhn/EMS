<?php

include ('./components/usernavbar.php');
include ("./components/connect.php");

function isLoggedIn()
{
    return isset($_SESSION['username']);
}

if (!isLoggedIn()) {
    echo "<script>
    window.location.href = 'index.php';
</script>";
    exit;
}

$count_emp_query = "SELECT COUNT(Username) AS count FROM userdb;";
$result_countemp = mysqli_query($conn, $count_emp_query);
$row = mysqli_fetch_assoc($result_countemp);
$count_emp = $row['count'];

$userquery = "SELECT Username FROM userdb";
$result = $conn->query($userquery);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo "No records found.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }



        body {
            font-family: 'Roboto', sans-serif;
            /* background-image: url('images/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat; */
            background-attachment: fixed;
            color: #333;
        }

        .main-content {
            margin-left: 30px;
            padding: 2rem;
            transition: margin-left 0.5s;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - 4rem);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 2rem;
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
        }

        .top-half-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 1rem;
        }

        .top-half-content>div {
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .top-half-content>div:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: white;
            color: #2193b0;
            border: none;
            margin-top: 10px;
            padding: 10px 20px;
            text-align: center;
            border-radius: 30px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2193b0;
            color: white;
        }

        .btn-primary h3 {
            margin: 0;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .container {
                padding: 1rem;
            }

            .btn-primary {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">
        <div class="container">
            <h1>Dashboard</h1>
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <div class="top-half-content">
                <div class="on-leave">
                    <button class="btn-primary" onclick="location.href='UserLeavereq.php'">
                        <h3>Apply For Leave</h3>
                    </button>
                </div>
                <div class="task-vieww">
                    <button class="btn-primary" onclick="location.href='UserTaskview.php'">
                        <h3>Task View</h3>
                    </button>
                </div>
                <div class="notice-view">
                    <button class="btn-primary" onclick="location.href='UserNoticeview.php'">
                        <h3>View Notice</h3>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>