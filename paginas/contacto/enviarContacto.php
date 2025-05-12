<?php 

if(isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['mensaje'])) {
    
    include '../../includes/db.php';
    include '../../includes/enviarCorreo.php';

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    $asunto = "Nuevo mensaje de contacto de $nombre";
    $cuerpoMensaje = "<strong>Nombre:</strong> $nombre<br><strong>Email:</strong> $email<br><strong>Mensaje:</strong> $mensaje";

    if(enviarCorreo($email, $asunto, $cuerpoMensaje)) {
        header("Location: ./contacto.php?mensaje_enviado=¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.");
        exit();
    } else {
        header("Location: ./contacto.php?error=Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.");
        exit();
    }
} else {
    echo "Por favor completa todos los campos del formulario.";
}

?>