<?php
include '../../includes/db.php';
require '../../includes/enviarCorreo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $correo_recuperar = $_POST['correo'] ?? '';

    function redirigirConError($mensaje)
    {
        header("Location: recuperar.php?error=" . urlencode($mensaje));
        exit;
    }

    if (!filter_var($correo_recuperar, FILTER_VALIDATE_EMAIL)) {
        redirigirConError("Formato de correo inválido");
    }

    $email = mysqli_real_escape_string($conexion, $correo_recuperar);

    $sqlIDusuario = 'SELECT id_usuarios FROM usuarios WHERE email = ?';
    $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
    mysqli_stmt_bind_param($stmtIDusuario, 's', $email);
    mysqli_stmt_execute($stmtIDusuario);
    $resultadoIDusuario = mysqli_stmt_get_result($stmtIDusuario);

    if (mysqli_num_rows($resultadoIDusuario) > 0) {
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $idUsuario = mysqli_fetch_assoc($resultadoIDusuario)['id_usuarios'];

        $sqlToken = "INSERT INTO recuperar_contrasenia (id_usuarios, email, token, fecha_expiracion) VALUES (?, ?, ?, ?)";
        $stmtToken = mysqli_prepare($conexion, $sqlToken);
        mysqli_stmt_bind_param($stmtToken, 'isss', $idUsuario, $email, $token, $expiracion);

        if (mysqli_stmt_execute($stmtToken)) {
            $enlace = "http://ec2-44-213-37-94.compute-1.amazonaws.com/gamergyx/auth/gestion_contrasenia/restablecer_contrasenia.php?token=" . urlencode($token);


            $asunto = 'Recuperación de contraseña';
            $mensaje = "Haga clic en este enlace para recuperar la contraseña: <a href='$enlace'>$enlace</a>";

            $resultadoCorreo = enviarCorreo($email, $asunto, $mensaje);

            if ($resultadoCorreo === true) {
                header('Location: recuperar.php?success="Correo envíado correctamente"');
            } else {
                redirigirConError("Error al enviar el correo");
            }
        } else {
            redirigirConError("Error al generar el token");
        }
    } else {
        redirigirConError("El correo electrónico no existe");
    }
} else {
    header("Location: ../login.php");
    exit();
}
