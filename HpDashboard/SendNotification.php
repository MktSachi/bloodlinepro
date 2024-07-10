<?php
// Include the PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust these paths to match your directory structure
require __DIR__ . '/../../PHPMailer/src/Exception.php';
require __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer/src/SMTP.php';

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodlinepro";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$address2 = $_POST['address2'];
$date = $_POST['date'];
$time = $_POST['time'];
$venue = $_POST['venue'];

// Fetch donor emails based on address2 (city)
$sql = "SELECT email FROM donors WHERE address2 = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind the parameters and execute the statement
$stmt->bind_param("s", $address2);
$stmt->execute();
$result = $stmt->get_result();

$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails[] = $row['email'];
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Email details
$subject = "Upcoming Donation Camp in $address2";
$message = createEmailMessage($date, $time, $venue);

// Send emails using PHPMailer
foreach ($emails as $email) {
    if (sendEmail($email, $subject, $message)) {
        echo "Email sent to: $email<br>";
    } else {
        echo "Failed to send email to: $email<br>";
    }
}

echo "Notification emails processed!";

function sendEmail($email, $subject, $message) {
    $mail = new PHPMailer(true);
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
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Failed to send email to: $email. Error: {$mail->ErrorInfo}", 3, "/path/to/your/logfile.log");
        return false;
    }
}

function createEmailMessage($date, $time, $venue) {
    return "<html>
        <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333; margin: 0; padding: 0;'>
            <div style='max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <h1 style='color: rgb(131, 26, 26); font-size: 28px;'>Upcoming Donation Camp</h1>
                <p style='font-size: 16px; line-height: 1.6;'>Dear Donor,</p>
                <p style='font-size: 16px; line-height: 1.6;'>We are excited to announce a donation camp in your city.</p>
                <p style='font-size: 16px; line-height: 1.6;'>Date: $date</p>
                <p style='font-size: 16px; line-height: 1.6;'>Time: $time</p>
                <p style='font-size: 16px; line-height: 1.6;'>Venue: $venue</p>
                <p style='font-size: 16px; line-height: 1.6;'>We hope to see you there!</p>
                <p style='font-size: 16px; line-height: 1.6;'>Best regards,<br>The Donation Team</p>
            </div>
        </body>
    </html>";
}
?>