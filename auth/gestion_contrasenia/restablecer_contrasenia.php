<?php
include '../../includes/db.php';

error_log("Inicio de restablecer_contrasenia.php con token: " . (isset($_GET['token']) ? $_GET['token'] : (isset($_POST['token']) ? $_POST['token'] : 'sin token')));

$token = null;
if (isset($_GET['token'])) {
    $token = urldecode($_GET['token']);
} elseif (isset($_POST['token'])) {
    $token = urldecode($_POST['token']);
}

if ($token === null) {
    header("Location: ../login.php");
    exit();
}

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$sqlToken = 'SELECT email, fecha_expiracion FROM recuperar_contrasenia WHERE token = ?';
$stmtToken = mysqli_prepare($conexion, $sqlToken);
if (!$stmtToken) {
    die('Error al preparar la consulta: ' . mysqli_error($conexion));
}
mysqli_stmt_bind_param($stmtToken, 's', $token);
mysqli_stmt_execute($stmtToken);
if (mysqli_stmt_error($stmtToken)) {
    die("Error en la ejecución de la consulta: " . mysqli_stmt_error($stmtToken));
}
$resultToken = mysqli_stmt_get_result($stmtToken);
if (!$resultToken) {
    die("Error al obtener el resultado: " . mysqli_error($conexion));
}

if (mysqli_num_rows($resultToken) > 0) {
    $row = mysqli_fetch_assoc($resultToken);
    $email = $row['email'];
    $fecha_duracion = $row['fecha_expiracion'];
    error_log("Email: " . $email . ", Fecha de expiración: " . $fecha_duracion);

    if (strtotime($fecha_duracion) > time()) {
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../../assets/paginas/recuperar_contraseña/restablecer.css">
            <title>Document</title>
        </head>

        <body>

            <div class="container">
                <div class="space1"></div>
                <div class="space2"></div>
                <div class="space3"></div>
            </div>

            <h1>Escriba su nueva contraseña</h1>
            <form action="restablecer_contrasenia.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <label for="Contraseña">Nueva Contraseña: </label><br>
                <input type="password" name="nueva_contrasenia"><br>
                <label for="Confirmar_contrasenia">Confirmar Contraseña: </label><br>
                <input type="password" name="confirmar_contrasenia"><br><br>
                <button type="submit" name="recuperar">Recuperar Contraseña</button><br>
                <button><a href="../login.php" style="text-decoration: none; color: white;">Volver al inicio</a></button>
        </body>

        <script src="../../assets/animations/starsAnimation.js" defer></script>

        </html>



        <?php if (isset($_GET['error_contrasenia'])): ?>
            <p style="color: red;">Error. Las contraseñas no son las mismas</p>
        <?php endif; ?>

        <?php if (isset($_GET['error_actualizacion'])): ?>
            <p style="color: red;">Error. No se pudo actualizar la contraseña, intentelo de nuevo más tarde.</p>
        <?php endif; ?>
        </form>
        <?php

        if (isset($_POST['recuperar'])) {
            $nueva_contrasenia = $_POST['nueva_contrasenia'];
            $confirmarContrasenia = $_POST['confirmar_contrasenia'];
            error_log("Nueva contraseña: " . $nueva_contrasenia . ", Confirmar contraseña: " . $confirmarContrasenia);

            if ($nueva_contrasenia != $confirmarContrasenia) {
                header('Location: restablecer_contrasenia.php?error_contrasenia&token=' . urlencode($token));
                exit();
            }

            $sqlActualizarContrasenia = 'UPDATE usuarios SET contraseña = ? WHERE email = ?';
            $stmtActualizar = mysqli_prepare($conexion, $sqlActualizarContrasenia);
            $hashedPassword = password_hash($nueva_contrasenia, PASSWORD_BCRYPT);
            mysqli_stmt_bind_param($stmtActualizar, 'ss', $hashedPassword, $email);
            mysqli_stmt_execute($stmtActualizar);

            if (mysqli_stmt_error($stmtActualizar)) {
                error_log("Error al actualizar la contraseña: " . mysqli_stmt_error($stmtActualizar));
                header('Location: restablecer_contrasenia.php?error_actualizacion&token=' . urlencode($token));
                exit();
            }

            error_log("Filas actualizadas: " . mysqli_stmt_affected_rows($stmtActualizar));
            if (mysqli_stmt_affected_rows($stmtActualizar) > 0) {
                $sqlEliminarToken = 'DELETE FROM recuperar_contrasenia WHERE token = ?';
                $stmtEliminarToken = mysqli_prepare($conexion, $sqlEliminarToken);
                mysqli_stmt_bind_param($stmtEliminarToken, 's', $token);
                mysqli_stmt_execute($stmtEliminarToken);

                if (mysqli_stmt_error($stmtEliminarToken)) {
                    error_log("Error al eliminar el token: " . mysqli_stmt_error($stmtEliminarToken));
                    header('Location: restablecer_contrasenia.php?error_eliminar_token&token=' . urlencode($token));
                    exit();
                }

                error_log("Filas eliminadas: " . mysqli_stmt_affected_rows($stmtEliminarToken));
                if (mysqli_stmt_affected_rows($stmtEliminarToken) > 0) {
                    header('Location: ../login.php?success=Actualizada la contraseña correctamente');
                    exit();
                } else {
                    error_log("No se eliminó el token de recuperar_contrasenia");
                    header('Location: restablecer_contrasenia.php?error_eliminar_token&token=' . urlencode($token));
                    exit();
                }
            } else {
                header('Location: restablecer_contrasenia.php?error_actualizacion&token=' . urlencode($token));
                exit();
            }
        }
    } else {
        header('Location: ../login.php?error=Tiempo de espera acabado. Vuelva hacerlo de nuevo');
        exit();
    }
} else {
    header("Location: ../login.php");
    exit();
}
?>