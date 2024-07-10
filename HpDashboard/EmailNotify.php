<?php
// Include the PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust these paths to match your directory structure
require __DIR__ . '/../../PHPMailer/src/Exception.php';
require __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer/src/SMTP.php';

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