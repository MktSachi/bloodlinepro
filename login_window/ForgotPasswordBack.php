<?php
session_start();
require '../DonorRegistration/Database.php'; 
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new Database(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    
    $stmt = $db->prepare("SELECT * FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email); 
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        
        $token = mt_rand(100000, 999999);

        
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;

        
        $mail_sent = sendPasswordResetEmail($email, $token);

        if ($mail_sent) {
            
            $_SESSION['mail_sent_message'] = "Password reset link sent successfully to $email.";
        } else {
            
            $_SESSION['mail_sent_message'] = "Failed to send password reset link. Please try again later.";
        }

        
        header('Location: ForgotPassword.php');
        exit();
    } else {
        $_SESSION['mail_sent_message'] = "Email not found in our records.";
        header('Location: ForgotPassword.php');
        exit();
    }
}

function sendPasswordResetEmail($email, $token) {
    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bloodlinepro.lk@gmail.com';
        $mail->Password = 'czqktgongmcdolnn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        
        $mail->setFrom('bloodlinepro.lk@gmail.com', '');
        $mail->addAddress($email);

        
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Link';
        $mail->Body = '<html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f5f5f5;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                    }
                    h1 {
                        color: rgb(131, 26, 26);
                        font-size: 28px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.6;
                    }
                    .button-container {
                        text-align: center;
                        margin-top: 20px;
                    }
                    .login-button {
                        display: inline-block;
                        padding: 12px 24px;
                        background-color: rgb(131, 26, 26);
                        color: #FFFFFF;
                        text-decoration: none;
                        font-size: 16px;
                        border-radius: 5px;
                        transition: background-color 0.3s;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Password Reset Request</h1>
                    <p>Dear User,</p>
                    <p>We have received a request to reset your password.</p>
                    <p>Please click on the following link to reset your password:</p>
                    <p><a href="http://localhost/bloodlinepro/login_window/ResetPassword.php?token='.$token.'" class="login-button">Reset Password</a></p>
                    <p>If you did not request this, please ignore this email.</p>
                    <p>Regards,<br>Your BloodlinePro Team</p>
                </div>
            </body>
            </html>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>