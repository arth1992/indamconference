<?php 
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
include('config.php');
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 3;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $_ENV['MAIL_HOST'];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = 'AUTH LOGIN';                                   //Enable SMTP authentication
    $mail->Username   = $_ENV['MAIL_USERNAME'];                     //SMTP username
    $mail->Password   = $_ENV['MAIL_PASSWORD'];                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    $mail->Timeout    = 300;
    //Recipients
    $mail->setFrom('info@indam.in', 'Mailer');
    $mail->addAddress('mr.arthparikh@gmail.com', 'Joe User');     //Add a recipient
   
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}