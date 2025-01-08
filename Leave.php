<?php
include ('./components/navbar.php');
include ('./components/connect.php');
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #eee;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-content {
            width: calc(100% - 80px);
            padding: 2rem;
            /* background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .container button {
            width: 400px;
            height: 250px;
            background-color: #2193b0;
            background-image: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .container button:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .container button i {
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .container button {
                width: 100%;
                height: 100px;
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="main-content">

        <div class="container">
            <button onclick="window.location.href = 'Leavereq.php';">
                <i class="fas fa-plane-departure"></i> Request A Leave
            </button>
            <button onclick="window.location.href = 'Manageleavereq.php';">
                <i class="fas fa-tasks"></i> Pending Requests
            </button>
            <button onclick="window.location.href = 'Manageleavetype.php';">
                <i class="fas fa-cogs"></i> Manage Leave Types
            </button>
            <button onclick="window.location.href = 'EmpOnLeave.php';">
                <i class="fas fa-history"></i> Leave History
            </button>
        </div>
    </div>
</body>

</html>