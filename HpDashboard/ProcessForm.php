<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

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
            $mail->Subject = 'Requesting Blood!';
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
                    <h1>Urgent Request for Blood Donation</h1>
                    <p>Dear $hospital,</p>
                    <p>We are writing to request an urgent blood donation for [Patient's Name], 
                        who is currently undergoing treatment at $hospital. The details of the
                        required blood are as follows:</p>
                    <p>
                      <strong>Hospital:</strong> $hospital<br>
                      <strong>Blood Group:</strong> $blood<br>
                      <strong>Quantity:</strong>$quantity pints<br>
                      <strong>Description:</strong>$description
                    </p>
        <p>Your immediate response and support in this matter will be highly appreciated. 
        Your donation could be a life-saving gift for [Patient's Name] and many others in need.</p>
        <p>Thank you for your prompt attention to this urgent request.</p>
        <p>Best Regards,<br>$hospital</p>
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
        
        // Check if prepare() failed
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

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
