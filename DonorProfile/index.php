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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Profile</title>
    <link rel="stylesheet" href="css/Profile.css">
    <style>
        
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <!-- !PAGE CONTENT! -->
    <div class="w3-main">
        <div class="container">
         
            <div class="profile-container">
                <div class="profile-section" id="general" style="display: block;">
                    <h2>General Information</h2>
                    <div class="form-columns-container">
                        <div class="form-column">
                            <div class="form-group">
                                <label for="firstName">First Name</label>
                                <input type="text" id="firstName" value="<?php echo htmlspecialchars($firstName); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="donorNIC">Donor NIC</label>
                                <input type="text" id="donorNIC" value="<?php echo htmlspecialchars($donorNIC); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <input type="text" id="gender" value="<?php echo htmlspecialchars($gender); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="bloodType">Blood Type</label>
                                <input type="text" id="bloodType" value="<?php echo htmlspecialchars($bloodType); ?>" readonly>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label for="lastName">Last Name</label>
                                <input type="text" id="lastName" value="<?php echo htmlspecialchars($lastName); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">Phone Number</label>
                                <input type="text" id="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" value="<?php echo htmlspecialchars($address); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="address2">Address 2</label>
                                <input type="text" id="address2" value="<?php echo htmlspecialchars($address2); ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add other sections like Info, Change Password, Awards, etc. here -->

            </div>

            <script>
                function makeEditable(element) {
                    const inputId = element.id + 'Input';
                    const inputElement = document.getElementById(inputId);

                    inputElement.value = element.innerText;
                    element.style.display = 'none';
                    inputElement.style.display = 'block';
                    inputElement.focus();
                }

                function saveInfo() {
                    const fields = ['registration', 'fname', 'lname', 'address', 'phone', 'email', 'password'];
                    
                    fields.forEach(field => {
                        const spanElement = document.getElementById(field);
                        const inputElement = document.getElementById(field + 'Input');

                        if (inputElement.style.display === 'block') {
                            spanElement.innerText = inputElement.value;
                            spanElement.style.display = 'block';
                            inputElement.style.display = 'none';
                        }
                    });
                }

                function loadProfilePic(event) {
                    const output = document.getElementById('profilePic');
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() {
                        URL.revokeObjectURL(output.src) // Free memory
                    }
                }

                function showSection(sectionId) {
                    var sections = document.querySelectorAll('.profile-section');
                    sections.forEach(function(section) {
                        section.style.display = 'none';
                    });

                    document.getElementById(sectionId).style.display = 'block';
                }

                function logout() {
                    // Handle logout logic here
                    window.location.href = 'logout.php'; // Redirect to logout page or perform logout action
                }

                // Script for handling the sidebar toggle on small screens
                function w3_open() {
                    document.getElementById("mySidebar").style.display = "block";
                    document.getElementById("myOverlay").style.display = "block";
                }

                function w3_close() {
                    document.getElementById("mySidebar").style.display = "none";
                    document.getElementById("myOverlay").style.display = "none";
                }
            </script>
        </div>
    </div>
</body>
</html>