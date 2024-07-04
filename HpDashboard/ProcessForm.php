<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

class EmailSender {
    public function sendConfirmationEmail($email, $hospital, $blood, $quantity, $description) {
        $mail = new PHPMailer(true); // Passing `true` enables exceptions

        try {
            // SMTP configuration
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmail.com';
            $mail->Username = 'bloodlinepro.lk@gmail.com';
            $mail->Password = 'czqktgongmcdolnn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Sender and recipient
            $mail->setFrom('bloodlinepro.lk@gmail.com', 'bloodlinepro');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Blood Request Submitted';
            $mail->Body = "<html>
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
                      color:rgb(131, 26, 26);
                      font-size: 28px;
                    }
                    p {
                      font-size: 16px;
                      line-height: 1.6;
                    }
                  </style>
                </head>
                <body>
                  <div class='container'>
                    <h1>Blood Request Submitted!</h1>
                    <p>Dear Hospital,</p>
                    <p>Thank you for your request. Here are the details:</p>
                    <p>Hospital: <strong>$hospital</strong></p>
                    <p>Blood Group: <strong>$blood</strong></p>
                    <p>Quantity: <strong>$quantity</strong> pints</p>
                    <p>Description: <strong>$description</strong></p>
                    <p>We will process your request as soon as possible.</p>
                    <p>Best Regards,<br>BloodlinePro</p>
                  </div>
                </body>
                </html>";

            // Send email
            $mail->send();
            echo 'Email sent successfully';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}

// Error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodlinepro";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $hospital = $_POST['hospital'];
    $email = $_POST['email'];
    $blood = $_POST['blood'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    
    // Error message array
    $errors = [];

    // Basic validation
    if (empty($hospital)) $errors[] = "Hospital is required.";
    if (empty($email)) $errors[] = "Email is required.";
    if (empty($blood)) $errors[] = "Blood group is required.";
    if (empty($quantity)) $errors[] = "Quantity is required.";
    if (empty($description)) $errors[] = "Description is required.";
    
    if (count($errors) == 0) {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO blood_requests (hospital, email, blood_group, quantity, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $hospital, $email, $blood, $quantity, $description);

        if ($stmt->execute()) {
            // Send email using PHPMailer
            $emailSender = new EmailSender();
            $emailSender->sendConfirmationEmail($email, $hospital, $blood, $quantity, $description);
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
} else {
    echo "Invalid request.";
}
?>
