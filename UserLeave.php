<?php
include ('./components/usernavbar.php');
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
    <title>User Dashboard</title>
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
    min-height: 100vh;
}

.main-content {
    width: calc(100% - 80px);
    padding: 2rem;
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
    width: 300px;
    height: 150px;
    background: linear-gradient(135deg, #6dd5ed, #2193b0);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 24px;
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
        width: calc(50% - 20px);
        height: 100px;
        font-size: 18px;
    }
}

    </style>
</head>

<body>
    <div class="main-content">
        <div class="container">
            <button onclick="window.location.href = 'UserLeavereq.php';">
                <i class="fas fa-plane-departure"></i> Request A Leave
            </button>
            <button onclick="window.location.href = 'UserLeaveHistory.php';">
                <i class="fas fa-history"></i> Leave History
            </button>
        </div>
    </div>
</body>

</html>