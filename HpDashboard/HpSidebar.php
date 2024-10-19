<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../Classes/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Only fetch low stock notifications if not already set
if (!isset($_SESSION['lowStockNotifications'])) {
    $hospitalID = $_SESSION['hospitalID'];
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

// Only fetch blood request notifications if not already set
if (!isset($_SESSION['bloodRequestNotifications'])) {
    $queryBloodRequests = "SELECT BR.requestID, BR.bloodType, BR.requestedQuantity, DH.hospitalName AS DonatingHospitalName
                           FROM blood_requests BR
                           INNER JOIN hospitals DH ON BR.DonatingHospitalID = DH.hospitalID
                           WHERE BR.RequestingHospitalID = ? AND BR.status = 'Pending'";
    $stmt = $conn->prepare($queryBloodRequests);
    $stmt->bind_param("i", $hospitalID);
    $stmt->execute();
    $result = $stmt->get_result();

    $bloodRequestNotifications = [];
    while ($row = $result->fetch_assoc()) {
        $bloodRequestNotifications[] = [
            'message' => "Blood request from: " ."<br>". $row['DonatingHospitalName'] ."\n"."(". $row['bloodType'] .")" . "\n".$row['requestedQuantity'] ."ml",
            'bloodType' => $row['bloodType'],
            'requestedQuantity' => $row['requestedQuantity'],
            'donatingHospitalName' => $row['DonatingHospitalName']
        ];
    }

    $_SESSION['bloodRequestNotifications'] = $bloodRequestNotifications;
    $_SESSION['bloodRequestCount'] = count($bloodRequestNotifications);

    $stmt->close();
}

$conn->close();

// Handle notification deletion
if (isset($_POST['delete_notification'])) {
    $index = $_POST['notification_index'];
    $type = $_POST['notification_type'];

    // Debug output for received parameters
    error_log("Type: " . $type . ", Index: " . $index);

    if ($type === 'lowStock') {
        if (isset($_SESSION['lowStockNotifications']) && isset($_SESSION['lowStockNotifications'][$index])) {
            unset($_SESSION['lowStockNotifications'][$index]);
            $_SESSION['lowStockNotifications'] = array_values($_SESSION['lowStockNotifications']);
            $_SESSION['lowStockCount'] = count($_SESSION['lowStockNotifications']);
            echo 'Low stock notification deleted'; // Debug output
        } else {
            echo 'Low stock notification not found'; // Debug output
        }
    } elseif ($type === 'bloodRequest') {
        if (isset($_SESSION['bloodRequestNotifications']) && isset($_SESSION['bloodRequestNotifications'][$index])) {
            unset($_SESSION['bloodRequestNotifications'][$index]);
            $_SESSION['bloodRequestNotifications'] = array_values($_SESSION['bloodRequestNotifications']);
            $_SESSION['bloodRequestCount'] = count($_SESSION['bloodRequestNotifications']);
            echo 'Blood request notification deleted'; // Debug output
        } else {
            echo 'Blood request notification not found'; // Debug output
        }
    } else {
        echo 'Invalid notification type'; // Debug output
    }

    exit; // Ensure exit after handling the request
}

// Handle marking notifications as viewed
if (isset($_POST['viewed_notifications'])) {
    $_SESSION['notificationsViewed'] = true;
    exit;
}
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fetch the logged-in user's username from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'User';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            border-right: 1px solid var(--border-color);
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
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

        .footer {
            text-align: center;
            padding: 15px;
            background-color: var(--sidebar-bg);
            color: var(--text-secondary);
            position: fixed;
            bottom: 0;
            left: 240px;
            width: calc(100% - 240px);
            transition: all 0.3s ease;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            font-size: 13px;
            letter-spacing: 0.5px;
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .menu-icon {
                display: block;
            }

            .footer {
                left: 0;
                width: 100%;
            }
        }

        .sidebar-footer {
            margin-top: auto;
            padding-bottom: 20px;
        }

        .profile-name {
            margin-top: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .breadcrumb-container {
            margin-left: 230px;
            padding: 15px;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .breadcrumb-container {
                margin-left: 20px;
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

        .dropdown-menu:empty {
            display: none;
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
    </style>
</head>

<body class="w3-light-grey">

    <div class="menu-icon" onclick="w3_toggle()">☰</div>

    <nav class="sidebar" id="mySidebar">
        <div class="sidebar-header">
            <img src="doc.jpg" class="profile-pic">
            <div class="profile-name">
                <strong><?php echo $username; ?></strong>
            </div>
        </div>

        <div class="w3-bar-block">
            <a href="Home.php" class="nav-item" id="home-link">
                <i class="bx bx-home-alt"></i> Home
            </a>
            <a href="HpDashboard.php" class="nav-item" id="dashboard-link">
                <i class="bx bx-bar-chart"></i> Dashboard
            </a>
            <a href="Profile.php" class="nav-item" id="profile-link">
                <i class="bx bx-cog"></i> Profile
            </a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="notification-link" onclick="toggleNotifications()">
                    <i class="bx bx-bell"></i> Notifications
                    <?php if (isset($_SESSION['lowStockCount']) && $_SESSION['lowStockCount'] > 0 && !isset($_SESSION['notificationsViewed'])) : ?>
                        <span class="badge badge-danger notification-badge"><?= $_SESSION['lowStockCount'] ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu" id="notificationDropdown" style="display: none;">
                    <!-- Notification dropdown logic remains unchanged -->
                    <?php if (isset($_SESSION['lowStockNotifications']) && count($_SESSION['lowStockNotifications']) > 0) : ?>
                        <?php foreach ($_SESSION['lowStockNotifications'] as $index => $notification) : ?>
                            <div class="dropdown-item">
                                <?= $notification['message'] ?>
                                <button class="btn btn-sm btn-danger" style="float: right;" onclick="deleteNotification(event, <?= $index ?>, 'lowStock')">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['bloodRequestNotifications']) && count($_SESSION['bloodRequestNotifications']) > 0) : ?>
                        <?php foreach ($_SESSION['bloodRequestNotifications'] as $index => $notification) : ?>
                            <div class="dropdown-item">
                                <?= $notification['message'] ?>
                                <button class="btn btn-sm btn-danger" style="float: right;" onclick="deleteNotification(event, <?= $index ?>, 'bloodRequest')">Delete</button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (empty($_SESSION['lowStockNotifications']) && empty($_SESSION['bloodRequestNotifications'])) : ?>
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

    <div class="breadcrumb-container">
        <!-- Content goes here -->
    </div>

    <div class="footer">
        Designed by BloodLinePro
    </div>

    <script>
        function w3_toggle() {
            var sidebar = document.getElementById("mySidebar");
            sidebar.classList.toggle("active");
        }

        function toggleNotifications() {
            var dropdown = document.getElementById("notificationDropdown");
            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
        }

        function deleteNotification(event, index, type) {
    event.preventDefault();

    // Send an AJAX request to delete the notification
    $.ajax({
        url: '', // Use the same PHP file or the appropriate URL for deletion handling
        type: 'POST',
        data: {
            delete_notification: true,
            notification_index: index,
            notification_type: type
        },
        success: function(response) {
            console.log(response); // Debug response from server

            // After successfully deleting, remove the notification from the DOM
            var dropdownItem = event.target.closest('.dropdown-item');
            dropdownItem.parentNode.removeChild(dropdownItem);
        },
        error: function() {
            console.log('Error deleting notification');
        }
    });
}



        function logout() {
            // Add the logout logic here
            sessionStorage.clear(); // Clear sessionStorage on logout
        }

        // Function to set active link
        function setActiveLink(id) {
            const links = document.querySelectorAll('.nav-item');
            links.forEach(link => link.classList.remove('active')); // Remove 'active' from all links
            document.getElementById(id).classList.add('active'); // Add 'active' to the selected link
        }

        // Save the selected link in sessionStorage and set it as active
        function selectLink(id) {
            sessionStorage.setItem('activeLink', id);
            setActiveLink(id);
        }

        // On page load, set the active link from sessionStorage
        window.onload = function () {
            const activeLinkId = sessionStorage.getItem('activeLink') || 'home-link'; // Default to 'home-link'
            setActiveLink(activeLinkId);
        }

        // Attach click event listeners to the sidebar links
        document.getElementById('home-link').addEventListener('click', function () { selectLink('home-link'); });
        document.getElementById('dashboard-link').addEventListener('click', function () { selectLink('dashboard-link'); });
        document.getElementById('profile-link').addEventListener('click', function () { selectLink('profile-link'); });
        document.getElementById('logout-link').addEventListener('click', function () { selectLink('logout-link'); });
    </script>

</body>

</html>

