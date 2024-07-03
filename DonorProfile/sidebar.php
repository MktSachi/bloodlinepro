<?php
session_start();
require_once('../donor_registration/Database.php'); // Include your Database class file here

// Check if the donor is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page or handle authentication failure
    header('Location: login.php');
    exit;
}

// Initialize Database connection
$db = new Database();
$conn = $db->getConnection();

// Prepare SQL query to fetch donor information including profile picture
$username = $_SESSION['username'];
$sql = "SELECT * FROM donors WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if donor record exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
    $donorNIC = $row['donorNIC'];
    $phoneNumber = $row['phoneNumber'];
    $address = $row['address'];
    $address2 = $row['address2'];
    $gender = $row['gender'];
    $bloodType = $row['bloodType'];
    $profilePicture = $row['profile_picture']; // Profile picture URL from database
    // Add more fields as needed

    // Close prepared statement and database connection
    $stmt->close();
    $db->close();
} else {
    // Handle case where donor record is not found
    // This might be due to an error or no matching record found
    $error_msg = "Error fetching donor information.";
    // Close prepared statement and database connection
    $stmt->close();
    $db->close();
    // Optionally redirect or display an error message
}
?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- css-->
 <link rel="stylesheet" href="../HP_Dashboard/css/sidebar.css">

 <style>
     .profile-picture-container {
    display: flex;
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    width: 100px; /* Adjust width and height as needed */
    height: 100px;
    overflow: hidden;
    border-radius: 50%; /* Makes the container circular */
    margin: 0 auto; /* Centers the container horizontally */
}

.profile-picture-container img {
    width: 100%; /* Ensures the image fills the circular container */
    height: auto; /* Maintains aspect ratio */
    display: block; /* Fixes any potential spacing issues */
}
</style>
</head>

<body class="w3-light-grey" style="font-family: arial;">

<!-- Top container -->
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
    <!-- Main navigation content container -->
    <div class="w3-bar-block" style="text-align: center; padding-left: 30px;">
        <a href="#" class="w3-bar-item w3-button w3-padding-16 w3-hide-large w3-dark-grey w3-hover-black" onclick="w3_close()" title="close menu">
            <i class="fa fa-remove fa-fw"></i> Close Menu
        </a> 
        <a href="Index.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-home fa-fw"></i> General
        </a>
        <a href="EditeProfile.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-users fa-fw"></i> Edit Profile
        </a>
        <a href="#" class="w3-bar-item w3-button w3-padding change-password" onclick="showSection('change-password')">
            <i class="fa fa-key fa-fw"></i> Security
        </a>
        </a>
        <a href="Awards.php" class="w3-bar-item w3-button w3-padding">
            <i class="fa fa-cog fa-fw"></i> Awards
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