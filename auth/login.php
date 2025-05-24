<?php
session_start();
include '../includes/db.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $contrasena = $_POST["contrasena"] ?? '';

    if (empty($nombre) || empty($contrasena)) {
        header("Location: login.php?error=Todos los campos son obligatorios.");
        exit();
    }


    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row['contraseña'])) {
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];
            header("Location: ../paginas/inicio/inicio.php");
            exit();
        } else {
            header("Location: login.php?error=Contraseña incorrecta.");
            exit();
        }
    } else {
        header("Location: login.php?error=Usuario no encontrado.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/auth/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet" />
    <title>Login - Gamergyx</title>
</head>

<body>

     <div class="container">
        <div class="space1"></div>
        <div class="space2"></div>
        <div class="space3"></div>
     </div>

    <form action="login.php" method="POST" id="form-login">
        <div id="info-left">
            <h1>Iniciar sesión</h1>

            <label for="nombre">Nombre de Usuario</label>
            <input type="text" name="nombre" required><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required><br>

            <button type="submit">Inicia sesión</button>
            <br><br>
            <a href="register.php">¿No tienes cuenta? Regístrate aquí</a>
            <br><br>
            <a href="./gestion_contrasenia/recuperar.php">¿Se te ha olvidado la contraseña?</a>
            <br><br>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red; margin-top: 1rem;"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['success'])): ?>
                <p style="color:green"><?php echo htmlspecialchars($_GET['success']); ?></p>
            <?php endif; ?>
        </div>

        <div id="imagen-right">
            <img src="https://zelda.nintendo.com/breath-of-the-wild/assets/media/wallpapers/tablet-1.jpg"
                alt="Fondo Zelda">
        </div>
    </form>

    <script src="../assets/auth/auth.js" defer></script>
    <script src="../paginas/menus/formulario.js" defer></script>

</body>

</html>