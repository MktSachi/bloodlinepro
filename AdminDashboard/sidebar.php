<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="css/Sidebar.css" rel="stylesheet">
</head>

<body class="w3-light-grey">

    <div class="menu-icon" onclick="w3_toggle()">☰</div>

    <nav class="sidebar" id="mySidebar">
        <div class="sidebar-header">
            <img src="doc.jpg" class="profile-pic">
            <div class="profile-name">
                <strong>
                    <?php
                    echo "Admin";
                    ?>
                </strong>
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
                <a href="#" class="nav-link dropdown-toggle" id="notification-link" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
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
                                <button class="btn btn-sm btn-danger"
                                    onclick="deleteNotification(<?= $index ?>)">Delete</button>
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

    <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer"
        title="close side menu" id="myOverlay"></div>

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

        function setActiveNavLink() {
            var currentPage = localStorage.getItem('selectedNav') || window.location.pathname.split("/").pop();
            var navLinks = document.querySelectorAll('.nav-item');

            navLinks.forEach(function (link) {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }

                link.addEventListener('click', function () {
                    localStorage.setItem('selectedNav', link.getAttribute('href'));
                });
            });
        }

        window.addEventListener('resize', handleResize);
        window.addEventListener('load', setActiveNavLink);
        handleResize();
    </script>

</body>

</html>