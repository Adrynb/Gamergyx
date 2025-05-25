<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$nombre = $_SESSION['nombre'];

$sqlFP = "SELECT fotoPerfil FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlFP);
mysqli_stmt_bind_param($stmt, "s", $nombre);
mysqli_stmt_execute($stmt);
$resultFP = mysqli_stmt_get_result($stmt);

if ($resultFP && $row = mysqli_fetch_assoc($resultFP)) {
    $fotoPerfil = '../../assets/images/perfiles/' . $row['fotoPerfil'];
} else {
    $fotoPerfil = '../../assets/images/logos/usuario_icon.png';
}

if (isset($_POST['guardar_cambios'])) {
    $nombre = $_POST['nombre'];
    $email = trim($_POST['email']);
    $contraseniaAntigua = $_POST['contrasenia_antigua'];
    $confirmarContrasenia = $_POST['confirmar_contrasenia'];
    $nuevaContrasenia = $_POST['nueva_contrasenia'];
    $fotoPerfilSubida = $_FILES['foto_perfil']['name'];

    if (empty($email) || $email = "") {
        $sqlEmail = "SELECT email FROM usuarios WHERE nombre = ?";
        $stmtEmail = mysqli_prepare($conexion, $sqlEmail);
        mysqli_stmt_bind_param($stmtEmail, "s", $_SESSION['nombre']);
        mysqli_stmt_execute($stmtEmail);
        $resultEmail = mysqli_stmt_get_result($stmtEmail);

        if ($resultEmail && $rowEmail = mysqli_fetch_assoc($resultEmail)) {
            $email = $rowEmail['email'];
        } else {
            $email = '';
        }
        mysqli_stmt_close($stmtEmail);
    }

    if ($fotoPerfilSubida) {
        $tipo = $_FILES['foto_perfil']['type'];
        $tam = $_FILES['foto_perfil']['size'];
        $tmp = $_FILES['foto_perfil']['tmp_name'];

        $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $maximoTamanio = 2 * 1024 * 1024;

        if (!in_array($tipo, $formatosPermitidos)) {
            header("Location: editar_perfil.php?error=formato_imagen_invalido");
            exit();
        }

        if ($tam > $maximoTamanio) {
            header("Location: editar_perfil.php?error=tamanio_imagen_excedido");
            exit();
        }

        $uploadDir = '../../assets/images/perfiles/';
        move_uploaded_file($tmp, $uploadDir . basename($fotoPerfilSubida));
    } else {
        $fotoPerfilSubida = null;
    }

    $sqlPassword = "SELECT contraseña FROM usuarios WHERE nombre = ?";
    $stmtPassword = mysqli_prepare($conexion, $sqlPassword);
    mysqli_stmt_bind_param($stmtPassword, "s", $_SESSION['nombre']);
    mysqli_stmt_execute($stmtPassword);
    $resultPassword = mysqli_stmt_get_result($stmtPassword);
    $passwordHash = mysqli_fetch_assoc($resultPassword)['contraseña'];
    mysqli_stmt_close($stmtPassword);

    if (empty($contraseniaAntigua) && empty($nuevaContrasenia) && empty($confirmarContrasenia)) {
        $sql = "UPDATE usuarios SET nombre = ?, email = ?, fotoPerfil = ? WHERE nombre = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $nombre, $email, $fotoPerfilSubida, $_SESSION['nombre']);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $_SESSION['nombre'] = $nombre;
            header("Location: ../inicio/inicio.php");
            exit();
        } else {
            header("Location: editar_perfil.php?error=fallo_actualizacion");
            exit();
        }
    }

    if (!empty($contraseniaAntigua) || !empty($nuevaContrasenia) || !empty($confirmarContrasenia)) {
        if (!password_verify($contraseniaAntigua, $passwordHash)) {
            header("Location: editar_perfil.php?error=contraseña_incorrecta");
            exit();
        }

        if ($nuevaContrasenia !== $confirmarContrasenia) {
            header("Location: editar_perfil.php?error=contraseñaNueva_no_coincide");
            exit();
        }

        $nuevaContraseniaHash = password_hash($nuevaContrasenia, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios SET nombre = ?, email = ?, contraseña = ?, fotoPerfil = ? WHERE nombre = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'sssss', $nombre, $email, $nuevaContraseniaHash, $fotoPerfilSubida, $_SESSION['nombre']);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $_SESSION['nombre'] = $nombre;
            header("Location: ../inicio/inicio.php");
            exit();
        } else {
            header("Location: editar_perfil.php?error=fallo_actualizacion");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../assets/paginas/editar_perfil.css">
</head>

<body>



    <img src="<?= $fotoPerfil ?>" id="fotoPerfil">
    <h2>Editar Perfil</h2>

    <form action="editar_perfil.php" method="post" enctype="multipart/form-data" id="editar_form">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($_SESSION['nombre']) ?>"
            required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>

        <label for="contraseña">Contraseña antigua:</label>
        <input type="password" id="contrasenia_antigua" name="contrasenia_antigua"><br><br>

        <label for="nueva_contrasenia">Nueva contraseña:</label>
        <input type="password" id="nueva_contrasenia" name="nueva_contrasenia"><br><br>

        <label for="confirmarContrasenia">Confirmar contrasenia antigua:</label>
        <input type="password" id="confirmar_contrasenia" name="confirmar_contrasenia"><br><br>

        <label for="foto_perfil">Foto de perfil:</label>
        <input type="file" id="foto_perfil" name="foto_perfil"><br><br>

        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] == 'contraseña_incorrecta'): ?>
                <p style="color: red;">La contraseña antigua es incorrecta.</p>
            <?php elseif ($_GET['error'] == 'contraseñaNueva_no_coincide'): ?>
                <p style="color: red;">Las contraseñas nuevas no coinciden.</p>
            <?php elseif ($_GET['error'] == 'formato_imagen_invalido'): ?>
                <p style="color: red;">Formato de imagen no válido. Solo JPEG, PNG y WEBP.</p>
            <?php elseif ($_GET['error'] == 'tamanio_imagen_excedido'): ?>
                <p style="color: red;">La imagen excede los 2 MB permitidos.</p>
            <?php elseif ($_GET['error'] == 'fallo_actualizacion'): ?>
                <p style="color: red;">Hubo un problema al actualizar tu perfil. Intenta de nuevo.</p>
            <?php endif; ?>
        <?php endif; ?>


        <button type="submit" name="guardar_cambios">Guardar Cambios</button>
        <button><a href="../inicio/inicio.php">Cancelar</a></button>



    </form>

    <script src="../../assets/animations/starsAnimation.js" defer></script>


</body>

</html>