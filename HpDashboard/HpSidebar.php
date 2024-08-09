<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel ="stylesheet" href="SideBarStyle.css">
    <style>

</style>
</head>

<body class="w3-light-grey">

<div class="menu-icon" onclick="w3_toggle()">☰</div>

<nav class="sidebar" id="mySidebar">
    <div class="sidebar-header">
        <img src="images/avatar.png" class="profile-pic">
        <div style="font-size: 18px;">
            <strong>Mandira Perera</strong>
        </div>
    </div>
 

    <div class="w3-bar-block">
    <a href="Home.php" class="nav-item large-icon-text ">
        <i class="fa fa-home large-icon"></i> Home
    </a>
</div>
        <a href="HpDashboard.php" class="nav-item">
            <i class="fa fa-users"></i> Dashboard
        </a>
        <a href="Profile.php" class="nav-item">
            <i class="fa fa-cog"></i> Profile
        </a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bell"></i> Notifications
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
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="breadcrumb-container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="Home.php">Home</a></li>
            <li class="breadcrumb-item " aria-current="page">Dashboard</li>
        </ol>
    </nav>
</div>


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

    // Function to handle window resize
    function handleResize() {
        if (window.innerWidth > 768) {
            mySidebar.style.transform = "translateX(0)";
            main.style.marginLeft = "210px";
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.transform = "translateX(-100%)";
            main.style.marginLeft = "0";
        }
    }

    // Add event listener for window resize
    window.addEventListener('resize', handleResize);

    // Initial call to set correct state
    handleResize();

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
</script>

</body>
</html>