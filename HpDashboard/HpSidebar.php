<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-bottom: 60px;
        }
        .sidebar {
            width: 210px;
            height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        .search-bar input:hover,
        .search-bar input:focus {
            border-color: #6c5ce7;
            outline: none;
        }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        .nav-item:hover {
            background-color: #e9ecef;
            color:rgb(131, 26, 26);
        }
        .nav-item.active {
            background-color: #6c5ce7;
            color: white;
        }
        .nav-item i {
            margin-right: 10px;
        }
        .menu-icon {
            display: none;
            font-size: 24px;
            cursor: pointer;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background: linear-gradient(135deg, #5a090a 0%, #060606 100%);
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            color:white;
        }
        .sidebar-footer {
            margin-top: auto;
            padding-bottom: 70px;
        }
        .w3-main {
            transition: margin-left 0.3s ease;
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
            .w3-main {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="w3-light-grey">

<div class="menu-icon" onclick="w3_toggle()">☰</div>

<nav class="sidebar" id="mySidebar">
    <div class="sidebar-header">
        <img src="images/avatar.png" class="profile-pic">
        <div style="font-size: 18px;">
            <strong>person name</strong>
        </div>
    </div>
    
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
    
    <div class="w3-bar-block">
        <a href="Home.php" class="nav-item">
            <i class="fa fa-home"></i> Home
        </a>
        <a href="HpDashboard.php" class="nav-item">
            <i class="fa fa-users"></i> Dashboard
        </a>
        <a href="Profile.php" class="nav-item">
            <i class="fa fa-cog"></i> Profile
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="../login_window/Logout.php" class="nav-item" onclick="logout()">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" id="main">
    <!-- Content here -->
</div>

<div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
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
</script>

</body>
</html>