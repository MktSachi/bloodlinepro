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
            $mail->Body = '<h3>You can save 3 lives</h3><h4>Full name: '.$firstName.'</h4><h4>User name: '.$username.'</h4>';

            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}

?>