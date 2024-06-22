<?php


        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
class EmailSender {
    public function sendConfirmationEmail($email, $firstName, $username) {
        


        
        require '../PHPMailer/src/Exception.php';
        require '../PHPMailer/src/PHPMailer.php';
        require '../PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);


        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = 'smtp.gmail.com';
            $mail->Username = 'bloodlinepro.lk@gmail.com';
            $mail->Password = 'czqktgongmcdolnn';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('bloodlinepro.lk@gmail.com', 'bloodlinepro');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'You can donate now';
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
                  color:rgb(131, 26, 26);
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
                <h1>Welcome to BloodlinePro!</h1>
                <p>Dear '.$firstName.',</p>
                <p>Thank you for registering with BloodlinePro.</p>
                <p>Please keep this information secure. You can now log in to your account.</p>
                <p>We are excited to have you onboard!</p>
                
                  <a href="http://localhost/bloodlinepro/login_window/login.php" class="login-button">Login Now</a>
                
              </div>
            </body>
            </html>';
            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}

?>