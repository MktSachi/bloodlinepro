<?php
session_start();
require_once '../Classes/Database.php';
require_once '../Classes/BloodRequest.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $db = new Database();
    $connection = $db->getConnection();
    $bloodRequest = new BloodRequest($connection);

    $donatingHospitalID = $_POST['hospital'];
    $bloodType = $_POST['blood'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];  // Assuming username is stored in the session upon login

    // Validate input
    if (empty($donatingHospitalID) || empty($bloodType) || empty($quantity) || empty($description)) {
        $_SESSION['error_msg'] = "All fields are required.";
        header("Location: Request.php");
        exit();
    }

    // Process the blood request
    $success = $bloodRequest->processBloodRequest($donatingHospitalID, $bloodType, $quantity, $username);

    if ($success) {
        // Get the last inserted request ID
        $requestID = $bloodRequest->getLastRequestID();

        // Update the hospital's blood inventory
        if ($bloodRequest->updateHospitalBloodInventory($requestID)) {
            $_SESSION['success_msg'] = "Blood request submitted and inventory updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Blood request submitted, but error updating inventory.";
        }
    } else {
        $_SESSION['error_msg'] = "Error submitting blood request. Please try again.";
    }

    header("Location: Request.php");
    exit();
} else {
    // If someone tries to access this file directly without submitting the form
    header("Location: Request.php");
    exit();
}
?>
