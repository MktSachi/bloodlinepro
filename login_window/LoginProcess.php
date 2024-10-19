<?php
session_start();
require '../Classes/Database.php';
require '../Classes/Donor.php';

$db = new Database();
$donor = new Donor($db);

$error_msg = "";

// Check if the user is already logged in via cookies
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_me'])) {
    list($username, $token, $mac) = explode(':', $_COOKIE['remember_me']);

    // Verify MAC to ensure the integrity of the cookie
    if (hash_equals($mac, hash_hmac('sha256', $username . ':' . $token, getenv('COOKIE_SECRET')))) {
        $user_data = $donor->getUserByUsername($username);

        // Check if user exists and the token matches
        if ($user_data && !empty($user_data['remember_token']) && hash_equals($user_data['remember_token'], $token)) {
            // Log the user in
            $_SESSION['username'] = $username;
            $_SESSION['roleID'] = $user_data['roleID'];
            $_SESSION['active'] = $user_data['active'];

            // Set hospitalID for HP role
            if ($user_data['roleID'] === 'hp' && isset($user_data['hospitalID'])) {
                $_SESSION['hospitalID'] = $user_data['hospitalID'];
            }

            // Redirect user based on their role
            redirectBasedOnRole($user_data);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $remember_me = isset($_POST['remember_me']) ? true : false;

    if ($username && $password) {
        // Check if the username exists
        if ($donor->CheckUserName($username)) {
            $user_data = $donor->getUserByUsername($username);

            // Verify password
            if ($user_data && password_verify($password, $user_data['password'])) {
                // Set session variables
                $_SESSION['username'] = $username;
                $_SESSION['roleID'] = $user_data['roleID'];
                $_SESSION['active'] = $user_data['active'];

                // Set hospitalID for HP role
                if ($user_data['roleID'] === 'hp' && isset($user_data['hospitalID'])) {
                    $_SESSION['hospitalID'] = $user_data['hospitalID'];
                }

                // If "Remember Me" is checked
                if ($remember_me) {
                    $token = bin2hex(random_bytes(16)); // Generate a secure token
                    $mac = hash_hmac('sha256', $username . ':' . $token, getenv('COOKIE_SECRET')); // MAC for integrity
                    $cookie_value = $username . ':' . $token . ':' . $mac;

                    // Set the cookie
                    setcookie(
                        'remember_me',
                        $cookie_value,
                        time() + (30 * 24 * 60 * 60), // 30 days
                        '/',
                        null,
                        true,  // Secure flag (requires HTTPS)
                        true   // HttpOnly flag
                    );

                    // Update remember_token in the database
                    $donor->updateRememberToken($username, $token);
                }

                // Redirect user based on their role
                redirectBasedOnRole($user_data);
            } else {
                $error_msg = "Incorrect password. Please try again.";
            }
        } else {
            $error_msg = "Username not found. Please try again.";
        }
    } else {
        $error_msg = "Please provide both username and password.";
    }
}

function redirectBasedOnRole($user_data) {
    global $error_msg; // Use global variable to handle errors within the function
    switch ($user_data['roleID']) {
        case 'donor':
            if ($user_data['active'] == 1) {
                header("Location: ../DonorProfile/Home.php");
            } else {
                $error_msg = "Account not active. Please contact support.";
            }
            break;
        case 'hp':
            if ($user_data['active'] == 2) {
                header("Location: ../HpDashboard/Home.php");
            } else {
                $error_msg = "Account not active. Please contact support.";
            }
            break;
        case 'admin':
            if ($user_data['active'] == 3) {
                header("Location: ../AdminDashboard/Home.php");
            } else {
                $error_msg = "Account not active. Please contact support.";
            }
            break;
        default:
            $error_msg = "Invalid role. Please contact support.";
    }
    exit();
}
?>
