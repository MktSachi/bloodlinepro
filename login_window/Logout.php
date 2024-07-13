<?php
session_start();


if (!isset($_POST['confirm_logout'])) {
    header("Location: ConfirmLogout.php");
    exit();
}

$_SESSION = array();


session_destroy();


if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, "/"); 
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, "/"); 
}

// Redirect to login page after logout
header("Location: login.php");
exit();
?>
