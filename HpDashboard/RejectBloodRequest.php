<?php
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

session_start();

// Check if the user is logged in and the requestID is set in the GET request
if (!isset($_SESSION['username']) || !isset($_GET['requestID'])) {
    // Redirect to login page or some error page if not authorized
    header("Location: login.php");
    exit();
}

// Get the requestID from the GET request
$requestID = $_GET['requestID'];

// Initialize database connection and blood request object
$db = new Database();  // Connection is made in the constructor
$conn = $db->getConnection();  // Get the connection using getConnection()
$bloodRequest = new BloodRequest($conn);

// Call a method to delete the blood request by requestID
$success = $bloodRequest->deleteRequestByID($requestID);

// Redirect back to the list of requests with a status message
if ($success) {
    // You can use a session or query string to pass success messages
    $_SESSION['message'] = "Request rejected and deleted successfully.";
    header("Location: ViewRequests.php");
} else {
    // Handle the error case if the deletion fails
    $_SESSION['message'] = "Failed to delete the request.";
    header("Location: ViewRequests.php");
}
exit();
?>
