<?php
session_start();
require_once('../DonorRegistration/Database.php'); // Include your Database class file here

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
    $donationCount = $row['donation_count']; // Fetch the donation count
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