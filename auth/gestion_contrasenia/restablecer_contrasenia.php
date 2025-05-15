<?php

include '../../includes/db.php';

if (isset($_GET['token'])) {

    $token = $_GET['token'];

    

    $sqlToken = 'SELECT email, fecha_expiracion FROM recuperar_contrasenia WHERE token = ?';
    $stmtToken = mysqli_prepare($conexion, $sqlToken);
    if (!$stmtToken) {
        die('Error al preparar la consulta: ' . mysqli_error($conexion));
    }
    mysqli_stmt_bind_param($stmtToken, 's', $token);
    mysqli_stmt_execute($stmtToken);
    $resultToken = mysqli_stmt_get_result($stmtToken);


    if (mysqli_num_rows($resultToken) > 0) {
        $row = mysqli_fetch_assoc($resultToken);
        $email = $row['email'];
        $fecha_duracion = $row['fecha_expiracion'];
        if (strtotime($fecha_duracion) > time()) {
            ?>

            <h1>Escriba su nueva contraseña</h1>

            <form action="restablecer_contrasenia.php" method="POST">
                <label for="Contraseña">Nueva Contraseña: </label><br>
                <input type="password" name="nueva_contrasenia"><br>
                <label for="Confirmar_contrasenia">Confirmar Contraseña: </label><br>
                <input type="password" name="confirmar_contrasenia"><br><br>
                <button type="submit" name="recuperar">Recuperar Contraseña</button>

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

                if ($nueva_contrasenia != $confirmarContrasenia) {
                    header('Location: restablecer_contrasenia.php?error_contrasenia');
                    exit();
                }

                $sqlActualizarContrasenia = 'UPDATE usuarios SET contraseña = ? WHERE email = ?';
                $stmtActualizar = mysqli_prepare($conexion, $sqlActualizarContrasenia);
                $hashedPassword = password_hash($nueva_contrasenia, PASSWORD_BCRYPT);
                mysqli_stmt_bind_param($stmtActualizar, 'ss', $hashedPassword, $email);
                mysqli_stmt_execute($stmtActualizar);

                if (mysqli_stmt_affected_rows($stmtActualizar) > 0) {
                    $sqlEliminarToken = 'DELETE FROM recuperar_contrasenia WHERE token = ?';
                    $stmtEliminarToken = mysqli_prepare($conexion, $sqlEliminarToken);
                    mysqli_stmt_bind_param($stmtEliminarToken, 's', $token);
                    mysqli_stmt_execute($stmtEliminarToken);

                    header('Location: ../login.php?success');
                    exit();
                } else {
                    header('Location: restablecer_contrasenia.php?error_actualizacion');
                    exit();
                }
            }

        } else {
            header('Location: ../login.php?error="Tiempo de espera acabado. Vuelva hacerlo de nuevo"');
            exit();
        }

    }
    else{
        echo 'dios';

    }
} else {
    header("Location: ../login.php");
    exit();
}



?>