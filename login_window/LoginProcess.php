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
    
    if ($mac === hash_hmac('sha256', $username . ':' . $token, getenv('COOKIE_SECRET'))) {
        $user_data = $donor->getUserByUsername($username);
        
        if ($user_data && hash_equals($user_data['remember_token'], $token)) {
            $_SESSION['username'] = $username;
            $_SESSION['roleID'] = $user_data['roleID'];
            $_SESSION['active'] = $user_data['active'];
            
            // Set hospitalID for HP role
            if ($user_data['roleID'] === 'hp' && isset($user_data['hospitalID'])) {
                $_SESSION['hospitalID'] = $user_data['hospitalID'];
            }
            
            redirectBasedOnRole($user_data);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? $_POST['remember_me'] : false;

    if ($donor->CheckUserName($username)) {
        $user_data = $donor->getUserByUsername($username);

        if (password_verify($password, $user_data['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['roleID'] = $user_data['roleID'];
            $_SESSION['active'] = $user_data['active'];

            // Set hospitalID for HP role
            if ($user_data['roleID'] === 'hp' && isset($user_data['hospitalID'])) {
                $_SESSION['hospitalID'] = $user_data['hospitalID'];
            }

            if ($remember_me) {
                $token = bin2hex(random_bytes(16));
                $mac = hash_hmac('sha256', $username . ':' . $token, getenv('COOKIE_SECRET'));
                $cookie_value = $username . ':' . $token . ':' . $mac;

                setcookie(
                    'remember_me',
                    $cookie_value,
                    time() + (30 * 24 * 60 * 60), // 30 days
                    '/',
                    null,
                    true, // Secure flag
                    true  // HttpOnly flag
                );

                $donor->updateRememberToken($username, $token);
            }

            redirectBasedOnRole($user_data);
        } else {
            $error_msg = "Incorrect password. Please try again.";
        }
    } else {
        $error_msg = "Username not found. Please try again.";
    }
}

function redirectBasedOnRole($user_data) {
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
