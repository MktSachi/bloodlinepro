<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

class EmailSender {
    public function sendConfirmationEmail($email, $donatingHospitalName, $requestingHospitalName, $bloodType, $quantity) {
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
            $mail->setFrom('bloodlinepro.lk@gmail.com', 'BloodlinePro');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Blood Request Notification from BloodlinePro';
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
                    <h1>Blood Request Notification</h1>
                    <p>Dear Team,</p>
                    <p>This is to inform you that <strong>'.$requestingHospitalName.'</strong> has requested blood of type <strong>'.$bloodType.'</strong> from your hospital, <strong>'.$donatingHospitalName.'</strong>.</p>
                    <p>Requested Quantity: <strong>'.$quantity.'</strong></p>
                    <p>Please review the request and take the necessary actions.</p>
                    <p>Thank you for your cooperation.</p>
                    <p>Best regards,<br>BloodlinePro Team</p>
                  </div>
                </body>
                </html>';
            // Send email
            $mail->send();
            echo 'Email sent successfully';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
