<?php
session_start();

if (!isset($_POST['confirm_logout'])) {
    header("Location: ConfirmLogout.php");
    exit();
}

// Set session destroy time to 1 minute from now
$_SESSION['destroy_time'] = time() + 60;

// Clear session variables
$_SESSION = array();

// Clear remember me cookie if it exists
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/");
}

// Redirect to login page after logout
header("Location: login.php");
exit();
?>