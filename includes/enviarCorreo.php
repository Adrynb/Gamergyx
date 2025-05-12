<?php

require __DIR__ . '../libs/PHPMailer/src/PHPMailer.php';
require __DIR__ . '../libs/PHPMailer/src/SMTP.php';
require __DIR__ . '../libs/PHPMailer/src/Exception.php';





function enviarCorreo($para, $asunto, $mensaje)
{

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->SMTPAuth = true;
        $mail->Username = 'adriannavarrobuceta@gmail.com';
        $mail->Password = 'P!T!T!"ardin!"nav!"vucta@2004!"';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('no-reply@tusitio.com', 'Tu Sitio');
        $mail->addAddress($para);

        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        $mail->send();
        return true;

    } catch (Exception $e) {
        return "Error: " . $mail->ErrorInfo;
    }

}

?>