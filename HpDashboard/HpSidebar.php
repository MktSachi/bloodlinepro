<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
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
      width: 100px;
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
    }

    .nav-item.active {
      background-color: var(--active-bg);
      color: var(--accent-color);
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
        <a href="Home.php" class="nav-item">
            <i class="bx bx-home-alt"></i> Home
        </a>
        <a href="HpDashboard.php" class="nav-item">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="Profile.php" class="nav-item">
            <i class="bx bx-cog"></i> Profile
        </a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        <a href="../login_window/Logout.php" class="nav-item" onclick="logout()">
            <i class="bx bx-log-out"></i> Logout
        </a>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>


<script>
    var mySidebar = document.getElementById("mySidebar");
    var overlayBg = document.getElementById("myOverlay");
    var main = document.getElementById("main");

    function w3_toggle() {
        if (window.innerWidth <= 768) {
            if (mySidebar.style.transform === 'translateX(0px)') {
                w3_close();
            } else {
                w3_open();
            }
        }
    }

    function w3_open() {
        mySidebar.style.transform = "translateX(0)";
        overlayBg.style.display = "block";
        if (window.innerWidth <= 768) {
            main.style.marginLeft = "0";
        }
    }

    function w3_close() {
        mySidebar.style.transform = "translateX(-100%)";
        overlayBg.style.display = "none";
        if (window.innerWidth <= 768) {
            main.style.marginLeft = "0";
        }
    }

    function logout() {
        alert("Logging out from your account");
    }

    function handleResize() {
        var breadcrumbContainer = document.querySelector('.breadcrumb-container');
        if (window.innerWidth > 768) {
            mySidebar.style.transform = "translateX(0)";
            main.style.marginLeft = "210px";
            overlayBg.style.display = "none";
            breadcrumbContainer.style.marginLeft = "230px";
        } else {
            mySidebar.style.transform = "translateX(-100%)";
            main.style.marginLeft = "0";
            breadcrumbContainer.style.marginLeft = "20px";
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize();
</script>

</body>
</html>