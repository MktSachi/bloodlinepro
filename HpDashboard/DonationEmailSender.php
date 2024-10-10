<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Since the user provided manual requires, we'll include them directly
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Adjust the path to PHPMailer accordingly
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

/**
 * Class EmailSender
 * Handles the configuration and sending of individual emails using PHPMailer.
 */
class EmailSender {
    private $mail;

    /**
     * Constructor to initialize PHPMailer with SMTP settings.
     *
     * @param array $smtpConfig SMTP configuration settings.
     */
    public function __construct($smtpConfig) {
        $this->mail = new PHPMailer(true); // Enable exceptions

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
            $this->mail->isHTML(true); // Set email format to HTML
        } catch (Exception $e) {
            die("Mailer could not be initialized. Error: {$e->getMessage()}");
        }
    }

    /**
     * Sends an email to a single recipient.
     *
     * @param string $email   Recipient's email address.
     * @param string $subject Email subject.
     * @param string $body    HTML content of the email.
     * @return bool           True on success, False on failure.
     */
    public function sendEmail($email, $subject, $body) {
        try {
            // Clear previous recipients and attachments
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            // Recipient
            $this->mail->addAddress($email);

            // Content
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            // Send the email
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Log the error message (you can customize the logging mechanism)
            error_log("Message could not be sent to {$email}. Mailer Error: {$this->mail->ErrorInfo}\n", 3, '../logs/email_errors.log');
            return false;
        }
    }
}

/**
 * Class DonationEmailSender
 * Manages database interactions and sends emails to donors based on form input.
 */
class DonationEmailSender {
    private $db;
    private $emailSender;

    /**
     * Constructor to initialize database connection and EmailSender.
     *
     * @param array $dbConfig    Database configuration settings.
     * @param array $smtpConfig  SMTP configuration settings.
     */
    public function __construct($dbConfig, $smtpConfig) {
        // Initialize database connection
        $this->db = new mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['dbname']
        );

        if ($this->db->connect_error) {
            die("Database connection failed: " . $this->db->connect_error);
        }

        // Initialize EmailSender
        $this->emailSender = new EmailSender($smtpConfig);
    }

    /**
     * Retrieves donors based on city and blood group.
     *
     * @param string $address2  City address.
     * @param string $bloodGroup Blood group.
     * @return array            Array of donors.
     */
    private function getDonors($address2, $bloodGroup) {
        $sql = "SELECT donorNIC, email FROM donors WHERE address2 = ? AND bloodType = ?";
        $stmt = $this->db->prepare($sql);

        if ($stmt === false) {
            die("Error preparing the statement: " . $this->db->error);
        }

        $stmt->bind_param("ss", $address2, $bloodGroup);
        $stmt->execute();
        $result = $stmt->get_result();

        $donors = [];
        while ($row = $result->fetch_assoc()) {
            $donors[] = $row;
        }

        $stmt->close();

        return $donors;
    }

    /**
     * Creates the HTML email message.
     *
     * @param string $donorNIC   Donor's NIC.
     * @param string $date       Date of the donation camp.
     * @param string $time       Time of the donation camp.
     * @param string $venue      Venue of the donation camp.
     * @param string $bloodGroup  Donor's blood group.
     * @return string            HTML formatted email message.
     */
    private function createEmailMessage($donorNIC, $date, $time, $venue, $bloodGroup) {
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
                        color: #8B0000; /* Dark red color */
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
                        background-color: #8B0000; /* Dark red button */
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
                    <p>Dear Donor,</p>
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

    /**
     * Sends donation emails to eligible donors based on form data.
     *
     * @param array $formData Form data containing blood group, address, date, venue, and time.
     */
    public function sendDonationEmails($formData) {
        // Retrieve and sanitize form data
        $bloodGroup = trim($formData['blood']);
        $address2   = trim($formData['address2']);
        $date       = trim($formData['date']);
        $time       = trim($formData['time']);
        $venue      = trim($formData['venue']);

        // Validate form data
        if (empty($bloodGroup) || empty($address2) || empty($date) || empty($time) || empty($venue)) {
            die("All form fields are required.");
        }

        // Fetch donors
        $donors = $this->getDonors($address2, $bloodGroup);

        // Check if any donors found
        if (empty($donors)) {
            echo "No donors found for the specified criteria.";
            return;
        }

        // Email subject
        $subject = "Upcoming Blood Donation Camp";

        // Counters for summary
        $sentCount = 0;
        $failedCount = 0;

        // Iterate through each donor and send email
        foreach ($donors as $donor) {
            $email     = $donor['email'];
            $donorNIC  = htmlspecialchars($donor['donorNIC']);
            $message   = $this->createEmailMessage($donorNIC, $date, $time, $venue, $bloodGroup);

            if ($this->emailSender->sendEmail($email, $subject, $message)) {
                echo "Email sent to: {$email}<br>";
                $sentCount++;
            } else {
                echo "Failed to send email to: {$email}<br>";
                $failedCount++;
            }
        }

        // Summary of the email sending process
        echo "<br>Total successful emails sent: {$sentCount}<br>";
        if ($failedCount > 0) {
            echo "Total emails failed: {$failedCount}<br>";
        }
    }
}

// -------------------------
// Main Execution Block
// -------------------------

// Define database configuration
$dbConfig = [
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname'   => 'bloodlinepro_', // Database name
];

// Define SMTP configuration
$smtpConfig = [
    'host'       => 'smtp.gmail.com',
    'username'   => 'bloodlinepro.lk@gmail.com', // Replace with your SMTP email
    'password'   => 'czqktgongmcdolnn',          // Replace with your SMTP password or App Password
    'secure'     => PHPMailer::ENCRYPTION_SMTPS,
    'port'       => 465,
    'from_email' => 'bloodlinepro.lk@gmail.com', // Sender's email
    'from_name'  => 'BloodlinePro',             // Sender's name
];

// Instantiate the DonationEmailSender class
$donationEmailSender = new DonationEmailSender($dbConfig, $smtpConfig);

// Retrieve form data securely
$formData = [
    'blood'    => isset($_POST['blood']) ? $_POST['blood'] : '',
    'address2' => isset($_POST['address2']) ? $_POST['address2'] : '',
    'date'     => isset($_POST['date']) ? $_POST['date'] : '',
    'time'     => isset($_POST['time']) ? $_POST['time'] : '',
    'venue'    => isset($_POST['venue']) ? $_POST['venue'] : '',
];

// Send donation emails
$donationEmailSender->sendDonationEmails($formData);
?>
