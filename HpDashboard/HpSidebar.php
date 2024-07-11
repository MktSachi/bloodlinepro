<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel ="stylesheet" href="css/sidebar.css">


</head>

<body class="w3-light-grey" style="font-family: arial;">

<!-- Top container -->
<div class="w3-bar w3-top w3-large" style="z-index:4; background: linear-gradient(to right, #8e1b1b 0%, #230606 100%); color:white; height:43px;">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();">
        <i class="fa fa-bars"></i>  Menu
    </button>
</div>

<nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;background-color:#FFFFFF;color:black;" id="mySidebar">
    <br>
    <div class="w3-center">
        <img src="images/avatar.png" class="w3-circle w3-margin-bottom profile-pic">
    </div>
    <div style="text-align: center;font-size: 18px;font-family: arial;">
        <strong>person name</strong>
    </div>
    <hr>
    <!-- Main navigation content container -->
    <div class="w3-bar-block" style="text-align: center; padding-left: 30px;">
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu">
            <i class="fa fa-remove fa-fw"></i> Close Menu
        </a> 
        <a href="#" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-home fa-fw"></i> Home
        </a>
        <a href="HpDashboard.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-users fa-fw"></i> Dashboard
        </a>
        
        <a href="Profile.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-cog fa-fw"></i> Profile
        </a>
        <a class="w3-bar-item w3-button w3-padding logout-button" style="cursor: pointer;" onclick="logout()">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:200px;margin-top:43px;">
    <!-- Content here -->
</div>

<div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>

<!--JavaScript functions-->
<script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            document.getElementById("myOverlay").style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            document.getElementById("myOverlay").style.display = "block";
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        document.getElementById("myOverlay").style.display = "none";
    }

    // Logout function to display a message
    function logout() {
        alert("Logging out from your account");
    }
</script>

</body>
</html>