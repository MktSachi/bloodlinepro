<?php
session_start();
require '../DonorRegistration/Database.php';
require '../DonorRegistration/Donor.php';

$db = new Database(); // Create an instance of Database
$donor = new Donor($db); // Pass $db to the Donor class constructor

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($donor->CheckUserName($username)) { // Ensure method name is correct (CheckUserName instead of isCheckUserName)
        $user_data = $donor->getUserByUsername($username);

        if (password_verify($password, $user_data['password'])) {
            $_SESSION['username'] = $username;
            header("Location: ../DonorProfile/index.php");
            exit();
        } else {
            $error_msg = "Incorrect password. Please try again.";
        }
    } else {
        $error_msg = "Username not found. Please try again.";
    }
}
?>
