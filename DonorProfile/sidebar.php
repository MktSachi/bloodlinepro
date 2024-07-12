<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel ="stylesheet" href="../HpDashboard/css/sidebar.css">
<style>
     .profile-picture-container {
     display: flex;
     justify-content: center; 
    align-items: center; 
    width: 100px; 
    height: 100px;
    overflow: hidden;
    border-radius: 50%; 
    margin: 0 auto; 
}

.profile-picture-container img {
    width: 100%; 
    height: auto; 
    display: block; 
}

hr{
    background-color: black;
}
</style>

</head>

<body class="w3-light-grey" style="font-family: arial;">


<div class="w3-bar w3-top w3-large" style="z-index:4;background-color:#00264d;color:white;height: 43px;">
    <button class="w3-bar-item w3-button w3-hide-large w3-hover-none w3-hover-text-light-grey" onclick="w3_open();">
        <i class="fa fa-bars"></i>  Menu
    </button>
</div>

<nav class="w3-sidebar w3-collapse w3-animate-left" style="z-index:3;background-color:#FFFFFF;color:black;" id="mySidebar">
    <br>
    <div class="w3-container w3-center">
        <?php if (!empty($profilePicture)) : ?>
            <div class="profile-picture-container">
    <img src="<?php echo htmlspecialchars($profilePicture); ?>" class="w3-circle w3-margin-bottom" alt="Profile Picture">
</div>


        <?php else : ?>
            <div class="profile-picture-container">
                <img src="images/default_avatar.png" class="w3-circle w3-margin-bottom" style="width: 100px; height: 100px;">
            </div>
        <?php endif; ?>
        <h4><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></h4>
    </div>
    <hr>
    
    <div class="w3-bar-block" style="text-align: center; padding-left: 30px;">
       
        <a href="Home.php" class="w3-bar-item w3-button w3-padding">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="Award.php" class="w3-bar-item w3-button w3-padding">
    <i class="fas fa-trophy"></i> Awards
</a>
        
        <a href="SettingProfile.php" class="w3-bar-item w3-button w3-padding">
            <i class="fas fa-cog"></i> Profile
        </a>
        <a href="Contact.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-envelope"></i> Contact
        </a>
        <a href="../login_window/Logout.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-sign-out"></i> Logout
        </a>
    </div>
</nav>


<div class="w3-overlay w3-hide-large w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<div class="w3-main" style="margin-left:200px;margin-top:43px;">
    <!-- Content here -->
</div>

<div class="footer">
    @2024 - Developed by Bloodlinepro BLOOD BANK MANAGEMENT SYSTEM
</div>

<!--JavaScript functions-->
<script>

    var mySidebar = document.getElementById("mySidebar");


    function w3_open() {
        if (mySidebar.style.display === 'block') {
            mySidebar.style.display = 'none';
            document.getElementById("myOverlay").style.display = "none";
        } else {
            mySidebar.style.display = 'block';
            document.getElementById("myOverlay").style.display = "block";
        }
    }

    
    function w3_close() {
        mySidebar.style.display = "none";
        document.getElementById("myOverlay").style.display = "none";
    }

    
    function logout() {
        alert("Logging out from your account");
    }
</script>

</body>
</html>