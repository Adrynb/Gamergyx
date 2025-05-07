<?php

include '../../../includes/db.php';
session_start();

if ($_SESSION["rol"] != "admin") {
    header("Location: ../../inicio/inicio.php");
    exit();
}

if (isset($_GET["id"])) {

    $titulo = urldecode($_GET["titulo"]);
    $descripcion = urldecode($_GET["contenido"]);
    $fecha = urldecode($_GET["fecha"]);
    $fuente = urldecode($_GET["fuente"]);
    $enlace = urldecode($_GET["enlace"]);
} else {
    $id_noticia = $titulo = $descripcion = $fecha = $fuente = $enlace = '';
}

if (isset($_POST["editarNoticia"])) {

    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $imagen = $_FILES['imagen']['name'];
    $fuente = $_POST['fuente'];
    $enlace = $_POST['enlace'];
    $id_noticia = $_GET["id"];

    $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
    $maximoTamanio = 2 * 1024 * 1024;

    if (!in_array($_FILES['imagen']['type'], $formatosPermitidos)) {
        die("Error: El formato de la imagen no es compatible. Solo se permiten JPEG, PNG y WEBP.");
    }

    if ($_FILES['imagen']['size'] > $maximoTamanio) {
        die("Error: El tamaño de la imagen excede el límite de 2 MB.");
    }

    $uploadDir = '../../../assets/noticias/';
    $uploadFile = $uploadDir . basename($imagen);

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
        die("Error: No se pudo subir la imagen.");
    }

    $sql = "UPDATE noticias SET 
                titulo = '$titulo', 
                contenido = '$descripcion',
                portada = '$imagen', 
                fecha = '$fecha', 
                fuente = '$fuente', 
                enlace = '$enlace' 
            WHERE id_noticias = '$id_noticia'";

    $result = mysqli_query($conexion, $sql);

    if ($result) {
        header("Location: ../noticias.php?mensaje=noticia_editada");
        exit();
    } else {
        die("Error al actualizar: " . mysqli_error($conexion));
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
</head>

<body>
    <form action="editarNoticia.php" method="POST" id="formulario" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo) ?>" required><br><br>

        <label for="contenido">Descripción:</label><br>
        <textarea id="contenido" name="descripcion" rows="4" cols="50"
            required><?= htmlspecialchars($descripcion) ?></textarea><br><br>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha) ?>" required><br><br>

        <label for="imagen">Portada:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

        <label for="fuente">Fuente:</label>
        <input type="text" id="fuente" name="fuente" value="<?= htmlspecialchars($fuente) ?>" required><br><br>

        <label for="enlace">Enlace Web:</label>
        <input type="text" id="enlace" name="enlace" value="<?= htmlspecialchars($enlace) ?>" required><br><br>

        <input type="submit" value="Editar Noticia" id="editarNoticia" name="editarNoticia">

        <p id="mensajeError"></p>

        <script src="insertarNoticia.js" defer></script>

    </form>
</body>

</html>