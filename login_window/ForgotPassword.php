<?php
session_start();
require '../DonorRegistration/Database.php'; // Adjust the path as per your file structure
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new Database(); // Create an instance of Database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate if email exists in your database (assuming you have a donors table)
    $stmt = $db->prepare("SELECT * FROM donors WHERE email = ?");
    $stmt->bind_param("s", $email); // Bind parameter to query
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a unique token (for demonstration, generate a random 6-digit number)
        $token = mt_rand(100000, 999999);

        // Store the token and email in session for validation in ResetPassword.php
        $_SESSION['reset_token'] = $token;
        $_SESSION['reset_email'] = $email;

        // Send reset link via email using PHPMailer
        $mail_sent = sendPasswordResetEmail($email, $token);

        if ($mail_sent) {
            // Set success message for popup
            $_SESSION['mail_sent_message'] = "Password reset link sent successfully to $email.";
        } else {
            // Set error message for popup
            $_SESSION['mail_sent_message'] = "Failed to send password reset link. Please try again later.";
        }

        // Redirect to this page to show popup
        header('Location: ForgotPassword.php');
        exit();
    } else {
        $_SESSION['mail_sent_message'] = "Email not found in our records.";
        header('Location: ForgotPassword.php');
        exit();
    }
}

function sendPasswordResetEmail($email, $token) {
    // Instantiate PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bloodlinepro.lk@gmail.com';
        $mail->Password = 'czqktgongmcdolnn';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('bloodlinepro.lk@gmail.com', '');
        $mail->addAddress($email);

        // Content
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
        // Handle email sending errors here
        // echo "Failed to send password reset link. Please try again later.";
        // Uncomment the line below for debugging purposes
        // echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
  <!-- Custom styles -->
  <style>
    .forgot-password-container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f8f9fa;
    }
    .forgot-password-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 400px;
    }
    .forgot-password-card .icon {
      font-size: 3rem;
      color: #007bff; /* Bootstrap primary color */
      margin-bottom: 1rem;
    }
    .forgot-password-card .title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 1rem;
    }
    .forgot-password-card .sub-title {
      font-size: 1rem;
      color: #6c757d;
      margin-bottom: 2rem;
    }
    .forgot-password-card .form-group {
      margin-bottom: 1.5rem;
    }
    .forgot-password-card .btn-primary {
      background-color: #007bff; /* Bootstrap primary color */
      border-color: #007bff;
    }
  </style>
</head>
<body>
  <div class="forgot-password-container">
    <div class="forgot-password-card">
      <div class="text-center">
        <i class="fas fa-key icon"></i>
        <h2 class="title">Forgot Password</h2>
        <p class="sub-title">Enter your email address to receive a password reset link.</p>
      </div>
      <form action="ForgotPassword.php" method="post">
        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap 5 JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery for Bootstrap's modal -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <!-- Script for showing popup message -->
  <script>
    $(document).ready(function() {
      <?php
      if (isset($_SESSION['mail_sent_message'])) {
        $message = $_SESSION['mail_sent_message'];
        echo "alert('$message');";
        unset($_SESSION['mail_sent_message']);
      }
      ?>
    });
  </script>
</body>
</html>
