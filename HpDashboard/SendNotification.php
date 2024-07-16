<?php
// Include the PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust these paths to match your directory structure
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

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

// Fetch donor IDs and emails based on address2 (city)
$sql = "SELECT donorNIC, email FROM donors WHERE address2 = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the statement: " . $conn->error);
}

// Bind the parameters and execute the statement
$stmt->bind_param("s", $address2);
$stmt->execute();
$result = $stmt->get_result();

$donors = [];
while ($row = $result->fetch_assoc()) {
    $donors[] = $row;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Email details
$subject = "Upcoming Donation Camp in $address2";

// Send emails using PHPMailer
foreach ($donors as $donor) {
    $donorId = $donor['donorNIC'];
    $email = $donor['email'];
    $message = createEmailMessage($donorId, $date, $time, $venue);

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

function createEmailMessage($donorId, $date, $time, $venue) {
    // Fetch donor details from the database
    global $servername, $username, $password, $dbname;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch donor details
    $sql = "SELECT first_name, last_name, bloodType FROM donors WHERE donorNIC = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $donorId);
    $stmt->execute();
    $result = $stmt->get_result();
    $donor = $result->fetch_assoc();

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    $donorName = $donor['first_name'] . ' ' . $donor['last_name'];
    $bloodType = $donor['bloodType'];

    // Create the email message
    return "<html>
        <body style='font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333; margin: 0; padding: 0;'>
            <div style='max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'>
                <h1 style='color: rgb(131, 26, 26); font-size: 28px;'>Upcoming Donation Camp</h1>
                <p style='font-size: 16px; line-height: 1.6;'>Dear $donorName,</p>
                <p style='font-size: 16px; line-height: 1.6;'>We are excited to announce a donation camp in your city.</p>
                <p style='font-size: 16px; line-height: 1.6;'>As a valued donor with blood type $bloodType, your contribution is crucial.</p>
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