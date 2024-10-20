<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class WarningEmailSender {
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->SMTPAuth = true;
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->Username = 'bloodlinepro.lk@gmail.com';
        $this->mail->Password = 'czqktgongmcdolnn';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;
        $this->mail->setFrom('bloodlinepro.lk@gmail.com', 'BloodlinePro');
    }

    public function sendLowStockAlert($recipientEmail, $hospitalName, $lowStockBloodTypes)
    {
        try {
            // Clear all recipient addresses
            $this->mail->clearAllRecipients();
            
            $this->mail->addAddress($recipientEmail);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Blood Inventory Low Stock Alert';

            $emailBody = $this->createEmailBody($hospitalName, $lowStockBloodTypes);
            $this->mail->Body = $emailBody;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    

    private function createEmailBody($hospitalName, $lowStockBloodTypes)
    {
        $body = "<html><body>";
        $body .= "<h2>Blood Inventory Low Stock Alert</h2>";
        $body .= "<p>Dear {$hospitalName},</p>";
        $body .= "<p>This is to inform you that the following blood types are critically low in our system (below 100 units):</p>";
        $body .= "<ul>";
        foreach ($lowStockBloodTypes as $bloodGroup) {
            $body .= "<li>{$bloodGroup['bloodType']}: {$bloodGroup['quantity']} units</li>";
        }
        $body .= "</ul>";
        $body .= "<p>Please check your inventory to confirm if these blood types are low and take the necessary action to replenish them.</p>";
        $body .= "<p>Thank you for your prompt attention to this matter.</p>";
        $body .= "<p>Best regards,<br>BloodlinePro Team</p>";
        $body .= "</body></html>";

        return $body;
    }
}
?>