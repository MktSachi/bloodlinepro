<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class EmailSender {
    public function sendConfirmationEmail($emails, $hospital, $blood, $quantity, $description, $patientname) {
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

            // Sender
            $mail->setFrom('bloodlinepro.lk@gmail.com', 'bloodlinepro');

            // Recipients (healthcare professionals)
            foreach ($emails as $email) {
                $mail->addAddress($email);
            }

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
                    <p>Dear Healthcare Professional,</p>
                    <p>We urgently need blood for $patientname, who is currently undergoing treatment at $hospital. The details are as follows:</p>
                    <p>
                      <strong>Hospital:</strong> $hospital<br>
                      <strong>Blood Group:</strong> $blood<br>
                      <strong>Quantity:</strong> $quantity pints<br>
                      <strong>Description:</strong> $description
                    </p>
                    <p>Your prompt attention to this matter is greatly appreciated.</p>
                    <p>Best Regards,<br>bloodlinepro</p>
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

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodlinepro";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Test query to check if the table exists
$test_query = "SHOW TABLES LIKE 'healthcare_professionals'";
$result = $conn->query($test_query);

if ($result->num_rows == 0) {
    die("The healthcare_professionals table does not exist in the database.");
}

// Test query to check table structure
$structure_query = "DESCRIBE healthcare_professionals";
$structure_result = $conn->query($structure_query);

$has_email = false;
$has_hospitalid = false;

while ($row = $structure_result->fetch_assoc()) {
    if ($row['Field'] == 'email') $has_email = true;
    if ($row['Field'] == 'hospitalid') $has_hospitalid = true;
}

if (!$has_email || !$has_hospitalid) {
    die("The healthcare_professionals table is missing required columns (email and/or hospitalid).");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital = $_POST['hospital'];
    $blood = $_POST['blood'];
    $quantity = $_POST['quantity'];
    $patientname = $_POST['patientname'];
    $description = $_POST['description'];
    
    $errors = [];

    if (empty($hospital)) $errors[] = "Hospital is required.";
    if (empty($blood)) $errors[] = "Blood group is required.";
    if (empty($quantity)) $errors[] = "Quantity is required.";
    if (empty($patientname)) $errors[] = "Patient name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    
    if (count($errors) == 0) {
        $stmt = $conn->prepare("INSERT INTO blood_requests (hospital, email, blood_group, quantity, description) VALUES (?, ?, ?, ?, ?)");
        
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssiss", $hospital, $email, $blood, $quantity, $description);

        if ($stmt->execute()) {
            // Get the hospitalid based on the hospital name
            $hospital_query = "SELECT id FROM hospitals WHERE name = ?";
            $hospital_stmt = $conn->prepare($hospital_query);
            
            if ($hospital_stmt === false) {
                die("Prepare failed for hospital query: " . $conn->error);
            }
            
            $hospital_stmt->bind_param("s", $hospital);
            
            if (!$hospital_stmt->execute()) {
                die("Execute failed for hospital query: " . $hospital_stmt->error);
            }
            
            $hospital_result = $hospital_stmt->get_result();
            $hospital_row = $hospital_result->fetch_assoc();
            $hospitalid = $hospital_row['id'];
            
            $hospital_stmt->close();

            // Now get the emails of healthcare professionals for this hospital
            $email_query = "SELECT email FROM healthcare_professionals WHERE hospitalid = ?";
            $email_stmt = $conn->prepare($email_query);
            
            if ($email_stmt === false) {
                die("Prepare failed for email query: " . $conn->error);
            }
            
            $email_stmt->bind_param("i", $hospitalid);
            
            if (!$email_stmt->execute()) {
                die("Execute failed for email query: " . $email_stmt->error);
            }
            
            $email_result = $email_stmt->get_result();
            
            $recipients = [];
            while ($row = $email_result->fetch_assoc()) {
                $recipients[] = $row['email'];
            }
            
            $email_stmt->close();

            if (count($recipients) > 0) {
                $emailSender = new EmailSender();
                $emailSender->sendConfirmationEmail($recipients, $hospital, $blood, $quantity, $description, $patientname);
            } else {
                echo "No healthcare professionals found for the selected hospital.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
