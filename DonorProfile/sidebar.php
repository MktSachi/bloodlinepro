<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../HpDashboard/SideBarStyle.css">
</head>

<body class="w3-light-grey">

<div class="menu-icon" onclick="w3_toggle()">☰</div>

<nav class="sidebar" id="mySidebar">
    <div class="sidebar-header">
    <?php if (!empty($profilePicture)) : ?>
            <div class="profile-picture-container">
    <img src="<?php echo htmlspecialchars($profilePicture); ?>" class="w3-circle w3-margin-bottom" alt="Profile Picture">
</div>


        <?php else : ?>
            <div class="profile-picture-container">
                <img src="../HPDashboard/images/avatar.png" class="w3-circle w3-margin-bottom" style="width: 100px; height: 100px;">
            </div>
        <?php endif; ?>
        <h4><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h4>
        </div>
    </div>
    
    <div class="search-bar">
        <input type="text" placeholder="Search...">
    </div>
    
    <div class="w3-bar-block">
        <a href="Home.php" class="nav-item">
            <i class="fa fa-home"></i> Home
        </a>
        <a href="Award.php" class="nav-item">
            <i class="fa fa-trophy"></i>Awards
        </a>
        <a href="SettingProfile.php" class="nav-item">
            <i class="fa fa-cog"></i> Profile
        </a>
        <a href="Contact.php" class="nav-item">
            <i class="fa fa-cog"></i> Contact
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