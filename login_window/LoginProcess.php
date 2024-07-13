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

    if ($donor->CheckUserName($username)) {
        $user_data = $donor->getUserByUsername($username);

        if (password_verify($password, $user_data['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['roleID'] = $user_data['roleID'];
            $_SESSION['active'] = $user_data['active'];

            if (isset($user_data['hospitalID'])) {
                $_SESSION['hospitalID'] = $user_data['hospitalID'];
            }

            // Set a cookie to store the username for personalization
            setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 = 1 day, cookie lasts for 30 days

            switch ($user_data['roleID']) {
                case 'donor':
                    if ($user_data['active'] == 1) {
                        header("Location: ../DonorProfile/index.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                case 'hp':
                    if ($user_data['active'] == 2) {
                        header("Location: ../HpDashboard/Profile.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                case 'admin':
                    if ($user_data['active'] == 3) {
                        header("Location: ../AdminDashboard/index.php");
                    } else {
                        $error_msg = "Account not active. Please contact support.";
                    }
                    break;
                default:
                    $error_msg = "Invalid role. Please contact support.";
            }
            exit();
        } else {
            $error_msg = "Incorrect password. Please try again.";
        }
    } else {
        $error_msg = "Username not found. Please try again.";
    }
}
?>

