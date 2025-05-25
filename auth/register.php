<?php
include '../includes/db.php';


function hashed_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST['nombre']) || empty($_POST['contraseña']) || empty($_POST['email'])) {
        $error = "Todos los campos son obligatorios.";
    } else {
        $nombre = $_POST['nombre'];
        $contraseña = $_POST['contraseña'];
        $email = $_POST['email'];

        $query = "SELECT * FROM usuarios WHERE nombre='$nombre'";
        $result = mysqli_query($conexion, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "El nombre de usuario ya existe.";
        } else {
            $hashed = hashed_password($contraseña);
            $sql = "INSERT INTO usuarios (nombre, descripcion, contraseña, rol, fotoPerfil, email)
                       VALUES ('$nombre', '', '$hashed', 'usuario', '', '$email')";

            if (mysqli_query($conexion, $sql)) {
                echo "<script>alert('Usuario registrado con éxito.');</script>";
                header("Location: login.php");
                exit();
            } else {
                $error = "Error al registrar: " . mysqli_error($conexion);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/paginas/auth/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet" />
    <title>Registro - Gamergyx</title>
</head>

<body>

     <div class="container">
        <div class="space1"></div>
        <div class="space2"></div>
        <div class="space3"></div>
     </div>

    <form action="register.php" method="POST" id="formulario" enctype="multipart/form-data">
        <div id="info-left">
            <h1>Registro de Usuario</h1>
            <label for="nombre">Nombre de Usuario</label>
            <input type="text" id="nombre" name="nombre">
            <span class="errores" style="color:red;"></span><br>

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email">
            <span class="errores" style="color:red;"></span><br>

            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña">
            <span class="errores" style="color:red;"></span><br>

            <button type="submit">Registrarse</button>
            <br><br>
            <a href="./login.php">¿Ya te has registrado?</a>
            <br><br>

            <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>

        <div id="imagen-right">
            <img src="https://zelda.nintendo.com/breath-of-the-wild/assets/media/wallpapers/tablet-1.jpg" alt="">
        </div>
    </form>
    
    <script src="../assets/animations/starsAnimation.js" defer></script>
    <script src="../paginas/menus/formulario.js" defer></script>

</body>
</html>