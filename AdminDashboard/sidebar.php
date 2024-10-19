<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Fetch low stock notifications if not already set
if (!isset($_SESSION['lowStockNotifications'])) {
    $queryLowStock = "SELECT bloodType, quantity FROM hospital_blood_inventory 
                      WHERE hospitalID = ? AND quantity < 10";
    $stmt = $conn->prepare($queryLowStock);
    $stmt->bind_param("i", $hospitalID);
    $stmt->execute();
    $result = $stmt->get_result();

    $lowStockNotifications = [];
    while ($row = $result->fetch_assoc()) {
        $lowStockNotifications[] = [
            'message' => "Low stock alert: " . $row['bloodType'] . " (Quantity: " . $row['quantity'] . ")",
            'bloodType' => $row['bloodType'],
            'quantity' => $row['quantity']
        ];
    }

    $_SESSION['lowStockNotifications'] = $lowStockNotifications;
    $_SESSION['lowStockCount'] = count($lowStockNotifications);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --sidebar-bg: #ffffff;
            --text-primary: #333333;
            --text-secondary: #5f6368;
            --accent-color: #1a73e8;
            --hover-bg: #4070f4;
            --active-bg: #e8f0fe;
            --border-color: #dadce0;
        }
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding-bottom: 60px;
        }
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: var(--sidebar-bg);
            color: var(--text-primary);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            padding: 20px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            border-right: 1px solid var(--border-color);
        }
        .sidebar-header {
            text-align: center;
        }
        .profile-pic {
            width: 105px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0 12px;
            height: 48px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0 24px 24px 0;
            margin-right: 12px;
            transition: all 0.2s ease;
            font-size: 14px;
            font-weight: 500;
        }
        .nav-item:hover {
            background-color: var(--hover-bg);
            color: #fff;
        }
        .nav-item.active {
            background-color: var(--active-bg);
            color: var(--accent-color);
            font-weight: bold;
        }
        .nav-item i {
            margin-right: 18px;
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        .menu-icon {
            display: none;
            font-size: 24px;
            cursor: pointer;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            color: var(--text-primary);
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .menu-icon {
                display: block;
            }
            .footer {
                left: 0;
                width: 100%;
            }
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }
        .dropdown-item {
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-item:hover {
            background-color: #f1f1f1;
        }
        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        .badge-danger {
            color: #fff;
            background-color: #dc3545;
        }
        .sidebar-footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="w3-light-grey">

<div class="menu-icon" onclick="w3_toggle()">☰</div>

<nav class="sidebar" id="mySidebar">
    <div class="sidebar-header">
        <img src="doc.jpg" class="profile-pic">
        <div class="profile-name">
            <strong>M.Perera</strong>
        </div>
    </div>

    <div class="w3-bar-block">
        <a href="Home.php" class="nav-item" id="home-link">
            <i class="bx bx-home-alt"></i> Home
        </a>
        <a href="AdminDashboard.php" class="nav-item" id="dashboard-link">
          <i class="bx bx-bar-chart"></i> Dashboard
        </a>
        <a href="Profile.php" class="nav-item" id="profile-link">
            <i class="bx bx-cog"></i> Profile
        </a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="notification-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="bx bx-bell"></i> Notifications
                <?php if (isset($_SESSION['lowStockCount']) && $_SESSION['lowStockCount'] > 0): ?>
                    <span class="badge badge-danger"><?= $_SESSION['lowStockCount'] ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="notificationDropdown">
                <?php if (isset($_SESSION['lowStockNotifications']) && count($_SESSION['lowStockNotifications']) > 0): ?>
                    <?php foreach ($_SESSION['lowStockNotifications'] as $index => $notification): ?>
                        <div class="dropdown-item">
                            <?= $notification['message'] ?>
                            <button class="btn btn-sm btn-danger" onclick="deleteNotification(<?= $index ?>)">Delete</button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="dropdown-item">No notifications</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="../login_window/Logout.php" class="nav-item" id="logout-link" onclick="logout()">
            <i class="bx bx-log-out"></i> Logout
        </a>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

    <script>
        var mySidebar = document.getElementById("mySidebar");
        var overlayBg = document.getElementById("myOverlay");

        function w3_toggle() {
            if (mySidebar.style.transform === "translateX(0px)") {
                mySidebar.style.transform = "translateX(-100%)";
            } else {
                mySidebar.style.transform = "translateX(0px)";
            }
        }

        function toggleNotifications() {
            var dropdown = document.getElementById("notificationDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        function deleteNotification(index) {
            $.ajax({
                url: 'delete_notification.php',
                method: 'POST',
                data: { index: index },
                success: function(response) {
                    location.reload();
                }
            });
        }

        function logout() {
            $.ajax({
                url: '../login_window/Logout.php',
                method: 'POST',
                success: function(response) {
                    window.location.href = '../login_window/Login.php';
                }
            });
        }
    </script>
</body>
</html>
