<?php
include '../includes/db.php';
include '../includes/sesion.php';


if (isset($_POST['id_videojuegos'])) {
    $id_videojuegos = $_POST['id_videojuegos'];
    $nombre = $_SESSION['nombre'];
    $numEstrellas = $_POST['estrellas'];

    $sql = "SELECT nombre, fotoPerfil FROM usuarios WHERE nombre = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $nombre);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        $nombre_usuario = $row['nombre'];
        $fotoPerfil = $row['fotoPerfil'];


        $sqlResenia = "INSERT INTO reseñas (id_videojuegos, usuario, fotoPerfil, comentarios, estrellas, fecha) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmtResenia = mysqli_prepare($conexion, $sqlResenia);
        mysqli_stmt_bind_param($stmtResenia, "isssi", $id_videojuegos, $nombre_usuario, $fotoPerfil, $_POST['reseña'], $numEstrellas);
        mysqli_stmt_execute($stmtResenia);

        if (mysqli_stmt_affected_rows($stmtResenia) > 0) {
            header("Location: ../paginas/juego-detalle/juego-detalle.php?id_videojuegos=$id_videojuegos&mensaje=reseña_guardada");
            exit();

        } else {
            header("Location: ../paginas/juego-detalle/juego-detalle.php?id_videojuegos=$id_videojuegos&mensaje=reseña_guardada");
            exit();
        }
    } else {
        echo "Error: Usuario no encontrado.";
        exit();
    }




} else {
    header("Location: ../paginas/inicio/inicio.php");
    exit();
}

?>