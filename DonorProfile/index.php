<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style.css">
</head>

<body class="w3-light-grey" style="font-family: arial;">

  <!-- Top container -->
<div class="w3-bar w3-top w3-large" style="z-index:4;background-color:#000033;color:white;height: 43px;">
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
    <div class="w3-bar-block" style="flex-grow: 1; text-align: center; padding-left: 10px;">
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu">
            <i class="fa fa-remove fa-fw"></i> Close Menu
        </a> 
        <a href="#" class="w3-bar-item w3-button w3-padding" onclick="showSection('general')">
            <i class="fa fa-user fa-fw"></i> General
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding" onclick="showSection('info')">
            <i class="fa fa-info-circle fa-fw"></i> Info
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding change-password" onclick="showSection('change-password')">
            <i class="fa fa-key fa-fw"></i> Change Password
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding" onclick="showSection('awards')">
            <i class="fa fa-trophy fa-fw"></i> Awards
        </a>
        <a class="w3-bar-item w3-button w3-padding logout-button" style="cursor: pointer;">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:250px;margin-top:43px;">
    <div class="w3-container profile-section" id="general">
        <h2>General Information</h2>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" value="" readonly>
        </div>
        <div class="form-group">
            <label for="nic">NIC</label>
            <input type="text" id="nic" value="" readonly>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" value="" readonly>
        </div>
        <div class="form-group">
            <label for="bloodType">Blood Type</label>
            <input type="text" id="bloodType" value="" readonly>
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <input type="text" id="gender" value="" readonly>
        </div>
        <div class="form-group">
            <label for="healthStatus">Health Status</label>
            <input type="text" id="healthStatus" value="" class="small-readonly" readonly>
        </div>
    </div>

    <div class="w3-container profile-section" id="info" style="display:none;">
        <h2>Info</h2>
        <div class="form-group">
            <label for="district">District</label>
            <select id="district">
                <option value="select">Select</option>
                <option value="badulla">Badulla</option>
                <option value="monaragala">Monaragala</option>
            </select>
        </div>
        <div class="form-group">
            <label for="phone">Phone No</label>
            <input type="text" id="phone" value="">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="">
        </div>
        <div class="form-group">
            <label for="profession">Profession</label>
            <input type="text" id="profession" value="">
        </div>
        <button class="w3-button w3-blue">Save Changes</button>
    </div>

    <div class="w3-container profile-section" id="change-password" style="display:none;">
        <h2>Change Password</h2>
        <div class="form-group">
            <label for="current-password">Current Password</label>
            <input type="password" id="current-password">
        </div>
        <div class="form-group">
            <label for="new-password">New Password</label>
            <input type="password" id="new-password">
        </div>
        <div class="form-group">
            <label for="repeat-new-password">Repeat New Password</label>
            <input type="password" id="repeat-new-password">
        </div>
        <button class="w3-button w3-blue">Change Password</button>
    </div>

    <div class="w3-container profile-section" id="awards" style="display:none;">
        <h2>Awards</h2>
        <div class="css-card">Award 1</div>
        <div class="css-card">Award 2</div>
        <div class="css-card">Award 3</div>
    </div>
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

    // Function to show the relevant section
    function showSection(sectionId) {
        var sections = document.querySelectorAll('.profile-section');
        sections.forEach(function(section) {
            section.style.display = 'none';
        });
        document.getElementById(sectionId).style.display = 'block';
    }
</script>

</body>
</html>
