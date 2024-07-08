<?php
session_start();
require '../DonorRegistration/Database.php'; // Adjust the path as per your file structure

$db = new Database(); // Create an instance of Database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process password reset form submission
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (isset($_SESSION['reset_email'])) {
        if ($newPassword === $confirmPassword) {
            // Update password in the database
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $email = $_SESSION['reset_email'];

            // Assuming 'username' is the column in 'users' that matches 'email' from 'donors'
            $sql = "UPDATE users SET password = ? WHERE username = (SELECT username FROM donors WHERE email = ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("ss", $hashedPassword, $email);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Password has been reset successfully. <a href='../Login/login.php'>Login</a></div>";
                unset($_SESSION['reset_email']); // Clear session variable
            } else {
                echo "<div class='alert alert-danger'>Error resetting password: " . $stmt->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Passwords do not match.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'> Please try again <a href='ForgotPassword.php'>click here</a>.</div>";
    }
}
?>
