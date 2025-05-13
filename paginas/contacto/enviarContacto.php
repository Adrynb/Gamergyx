<?php

if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['mensaje'])) {

    include '../../includes/db.php';
    include '../../includes/enviarCorreo.php';

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ./contacto.php?error=El correo ingresado no es válido.");
        exit();
    }


    $asunto = "Nuevo mensaje de contacto de $nombre";
    $cuerpoMensaje = "
        <strong>Nombre:</strong> $nombre<br>
        <strong>Email:</strong> $email<br>
        <strong>Mensaje:</strong> $mensaje
    ";

    $resultado = enviarCorreo('adriannavarrobuceta@gmail.com', $asunto, $cuerpoMensaje);
    if ($resultado === true) {
        header("Location: ./contacto.php?mensaje_enviado=¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.");
        exit();
    } else {
        echo "<pre>Error al enviar: $resultado</pre>";
        exit();
    }

} else {
    echo "Por favor completa todos los campos del formulario.";
}
?>