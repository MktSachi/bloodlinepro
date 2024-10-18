<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class EmailSender {
    private $mail;

    public function __construct($smtpConfig) {
        $this->mail = new PHPMailer(true);

        try {
            // SMTP configuration
            $this->mail->isSMTP();
            $this->mail->Host       = $smtpConfig['host'];
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $smtpConfig['username'];
            $this->mail->Password   = $smtpConfig['password'];
            $this->mail->SMTPSecure = $smtpConfig['secure'];
            $this->mail->Port       = $smtpConfig['port'];

            // Sender details
            $this->mail->setFrom($smtpConfig['from_email'], $smtpConfig['from_name']);
            $this->mail->isHTML(true);
        } catch (Exception $e) {
            die("Mailer could not be initialized. Error: {$e->getMessage()}");
        }
    }

    public function sendEmail($email, $subject, $body) {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->addAddress($email);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent to {$email}. Mailer Error: {$this->mail->ErrorInfo}\n", 3, '../logs/email_errors.log');
            return false;
        }
    }
}

class DonationEmailSender {
    private $db;
    private $emailSender;

    public function __construct($dbConfig, $smtpConfig) {
        $this->db = new mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['dbname']
        );

        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }

        $this->emailSender = new EmailSender($smtpConfig);
    }

    private function getDonors($address2, $bloodGroup) {
        // Join donors and donations to get the last donation date and donor first name
        $sql = "
            SELECT d.donorNIC, d.email, d.first_name, MAX(dt.donationDate) AS lastDonationDate
            FROM donors AS d
            LEFT JOIN donations AS dt ON d.donorNIC = dt.donorNIC
            WHERE d.address2 = ? AND d.bloodType = ?
            GROUP BY d.donorNIC
        ";

        // Prepare statement
        $stmt = $this->db->prepare($sql);
        
        // Check for errors in the preparation
        if ($stmt === false) {
            die("SQL prepare failed: " . $this->db->error);
        }

        $stmt->bind_param("ss", $address2, $bloodGroup);
        $stmt->execute();
        $result = $stmt->get_result();

        $donors = [];
        while ($row = $result->fetch_assoc()) {
            // Calculate if it's been more than 1 month since last donation
            if (!empty($row['lastDonationDate'])) {
                $lastDonationDate = new DateTime($row['lastDonationDate']);
                $currentDate = new DateTime();
                $interval = $lastDonationDate->diff($currentDate);
                // Check if more than 1 month has passed
                if ($interval->d >= 10 || $interval->m >= 0 || $interval->y > 0) {
                    $donors[] = $row;
                }
            }
        }

        $stmt->close();
        return $donors;
    }

    private function createEmailMessage($donorFirstName, $date, $time, $venue, $bloodGroup) {
        return '
        <html>
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
                        color: #8B0000;
                        font-size: 28px;
                        text-align: center;
                    }
                    h2 {
                        color: rgb(131, 26, 26);
                        font-size: 24px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.6;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: #8B0000;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 5px;
                        text-align: center;
                        margin-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1>Donation Camp Reminder!</h1>
                    <p>Dear ' . htmlspecialchars($donorFirstName) . ',</p>
                    <p>We are organizing a blood donation camp for donors with blood type <strong>' . htmlspecialchars($bloodGroup) . '</strong>.</p>
                    <p><strong>Date:</strong> ' . htmlspecialchars($date) . '</p>
                    <p><strong>Time:</strong> ' . htmlspecialchars($time) . '</p>
                    <p><strong>Venue:</strong> ' . htmlspecialchars($venue) . '</p>
                    <p>We hope to see you there! Your donation can save lives.</p>
                    <p>Thank you for your generosity.</p>
                    <p>Best regards,<br>BloodlinePro Team</p>
                </div>
            </body>
        </html>';
    }

    public function sendDonationEmails($formData) {
        $bloodGroup = trim($formData['blood']);
        $address2   = trim($formData['address2']);
        $date       = trim($formData['date']);
        $time       = trim($formData['time']);
        $venue      = trim($formData['venue']);

        if (empty($bloodGroup) || empty($address2) || empty($date) || empty($time) || empty($venue)) {
            die("All form fields are required.");
        }

        $donors = $this->getDonors($address2, $bloodGroup);

        if (empty($donors)) {
            echo "No eligible donors found for the specified criteria.";
            return;
        }

        $subject = "Upcoming Blood Donation Camp";
        $sentCount = 0;
        $failedCount = 0;

        foreach ($donors as $donor) {
            $email       = $donor['email'];
            $donorFirstName = htmlspecialchars($donor['first_name']);
            $message     = $this->createEmailMessage($donorFirstName, $date, $time, $venue, $bloodGroup);

            if ($this->emailSender->sendEmail($email, $subject, $message)) {
                echo "Email sent to: {$email}<br>";
                $sentCount++;
            } else {
                echo "Failed to send email to: {$email}<br>";
                $failedCount++;
            }
        }

        echo "<br>Total successful emails sent: {$sentCount}<br>";
        if ($failedCount > 0) {
            echo "Total emails failed: {$failedCount}<br>";
        }
    }
}

// Main Execution Block
$dbConfig = [
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname'   => 'bloodlinepro_', // Database name
];

$smtpConfig = [
    'host'       => 'smtp.gmail.com',
    'username'   => 'bloodlinepro.lk@gmail.com',
    'password'   => 'czqktgongmcdolnn',
    'secure'     => PHPMailer::ENCRYPTION_SMTPS,
    'port'       => 465,
    'from_email' => 'bloodlinepro.lk@gmail.com',
    'from_name'  => 'BloodlinePro',
];

$donationEmailSender = new DonationEmailSender($dbConfig, $smtpConfig);

$formData = [
    'blood'    => isset($_POST['blood']) ? $_POST['blood'] : '',
    'address2' => isset($_POST['address2']) ? $_POST['address2'] : '',
    'date'     => isset($_POST['date']) ? $_POST['date'] : '',
    'time'     => isset($_POST['time']) ? $_POST['time'] : '',
    'venue'    => isset($_POST['venue']) ? $_POST['venue'] : '',
];

$donationEmailSender->sendDonationEmails($formData);

?>
