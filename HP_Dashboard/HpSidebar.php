<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .w3-sidebar .w3-bar-item, .w3-sidebar .w3-bar-item a {
        padding: 20px; /* Increase padding for all content */
        font-size: 18px; /* Increase font size */
    }
    .w3-sidebar .w3-bar-item i {
        margin-right: 15px; /* Increase gap between icon and text */
    }
    .w3-sidebar .w3-bar-item:hover {
        background-color: transparent !important; /* Remove background color on hover */
        color: #0000b3 !important; /* Set font color to blue on hover */
    }
    .logout-button {
        background-color: #990000; /* Set background color to a red shade */
        color: white; /* Set text color to white */
        box-sizing: border-box; /* Include padding and border in the element's total width and height */
    }
    .logout-button:hover {
        background-color: #990000 !important; /* Maintain background color on hover */
    }
    .logout-button i {
        margin-right: 15px; /* Ensure the icon margin stays the same */
    }
   
    
</style>
</head>

<body class="w3-light-grey" style="font-family: arial;">

  <!-- Top container -->
<div class="w3-bar w3-top w3-large" style="z-index:4;background-color:#000033;color:white;height: 43px;;">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();">
        <i class="fa fa-bars"></i>  Menu
    </button>
   
</div>
<nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;width:250px;background-color:#000033;color:white;" id="mySidebar">
    <br>
    <div class="w3-container w3-center">
        <img src="images/avatar.png" class="w3-circle w3-margin-bottom" style="width:100px;">
    </div>
    <div style="text-align: center;font-size: 18px;font-family: arial;">
        <strong>person name</strong>
    </div>
    <hr>
    <!-- Main navigation content container -->
    <div class="w3-bar-block" style="flex-grow: 1; text-align: center; padding-left: 30px;">
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu">
            <i class="fa fa-remove fa-fw"></i> Close Menu
        </a> 
        <a href="#" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-home fa-fw"></i> Home
        </a>
        <a href="HpDashboard.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-users fa-fw"></i> Dashboard
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-diamond fa-fw"></i> Warnings
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-cog fa-fw"></i> Profile
        </a>
        <a class="w3-bar-item w3-button w3-padding logout-button" style="cursor: pointer;">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>
    <div class="w3-main" style="margin-left:250px;margin-top:43px;"></div>
</div>

<!--JavaScript functions-->
<script>
    // Get the Sidebar
    var mySidebar = document.getElementById("mySidebar");

    // Get the DIV with overlay effect
    var overlayBg = document.getElementById("myOverlay");

    // Toggle between showing and hiding the sidebar, and add overlay effect
    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            overlayBg.style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            overlayBg.style.display = "block";
        }
    }

    // Close the sidebar with the close button
    function w3_close() {
        mySidebar.style.display = "none";
        overlayBg.style.display = "none";
    }

    // Logout function to display a message
    function logout() {
        alert("Logging out from your account");
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".logout-button").addEventListener("click", logout);
    });
</script>

</body>
</html>
