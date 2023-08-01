<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$email = 'laherikc@gmail.com';
$password = 'eiuudqdokgoyvxbm';

$code = rand(100000, 999999);
if(isset($curr_email))
{
    $to = $curr_email;

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = $password;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($email, 'Kartik Laheri');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Your Login Code';
        $mail->Body    = 'Your login code is: ' . $code;

        $mail->send();
        // echo 'Login code sent to your email address.';
    } catch (Exception $e) {
        echo 'Error sending email: ' . $mail->ErrorInfo;
    }
}
?>