<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin - Blood Management System</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/adminUi.css">
    </head>
    <body class="w3-light-grey" style="font-family: arial;">

        <div class="w3-bar w3-top w3-large" style="z-index:4; background-color:#000033; color:white; height: 43px;">
            <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();">
                <i class="fa fa-bars"></i> Menu
            </button>
        </div>

        <nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3; width:250px; background-color:#000033; color:white;" id="mySidebar">
            <br>
            <div style="text-align: center; font-size:25px;">
                <strong>Admin</strong>
            </div>
            <hr>
            <div class="w3-bar-block" style="flex-grow: 1; text-align: center; padding-left: 30px;">
                <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu">
                    <i class="fa fa-remove fa-fw"></i> Close Menu
                </a>
                <a href="#" class="w3-bar-item w3-button w3-padding" onclick="loadDashboard()">
                    <i class="fa fa-users fa-fw"></i> Dashboard
                </a>
                <a href="#" class="w3-bar-item w3-button w3-padding" onclick="loadProfile()">
                    <i class="fa fa-cog fa-fw"></i> Profile
                </a>
                <a class="w3-bar-item w3-button w3-padding logout-button" style="cursor: pointer;">
                    &nbsp;<i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </nav>

        <div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

        <div class="w3-main" style="margin-left:250px;margin-top:43px;">
            <iframe id="contentFrame" style="width:100%; height:100vh; border:none;"></iframe>
        </div>

        <script>
            var mySidebar = document.getElementById("mySidebar");

            var overlayBg = document.getElementById("myOverlay");

            function w3_open() {
                if (mySidebar.style.display === 'block') {
                    mySidebar.style.display = 'none';
                    overlayBg.style.display = "none";
                } else {
                    mySidebar.style.display = 'block';
                    overlayBg.style.display = "block";
                }
            }

            function w3_close() {
                mySidebar.style.display = "none";
                overlayBg.style.display = "none";
            }

            function logout() {
                alert("Logging out from your account");
            }

            function loadProfile() {
                document.getElementById('contentFrame').src = 'profile.php';
            }

            function loadDashboard() {
                document.getElementById('contentFrame').src = 'dashboard.php';
            }

            document.addEventListener("DOMContentLoaded", function () {
                document.querySelector(".logout-button").addEventListener("click", logout);
                loadDashboard();
            });
        </script>
</html>
