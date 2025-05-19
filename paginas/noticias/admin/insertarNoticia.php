<?php
include '../../../includes/db.php';
include '../../../includes/sesion.php';
include '../../menus/header.php';


if ($_SESSION["rol"] != "admin") {
    header("Location: ../../../index.php");
    exit();
}

if (isset($_POST["formulario"])) {

    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $imagen = $_FILES['imagen']['name'];
    $fuente = $_POST['fuente'];
    $enlace = $_POST['enlace'];

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

    if (isset($_POST['insertarNoticia'])) {

        $query = "INSERT INTO noticias (titulo, contenido, fecha, portada, fuente, enlace) 
        VALUES ('$titulo', '$descripcion', '$fecha', '$imagen', '$fuente', '$enlace')";
        $result = mysqli_query($conexion, $query);

        if ($result) {
            header("Location: ../noticias.php?mensaje=noticia_insertada");
            echo "<script>alert('Noticia insertada correctamente.');</script>";
            exit();
        } else {
            echo "<script>alert('Error: No se pudo insertar la noticia.');</script>";
            die("Error: No se pudo insertar la noticia.");

        }
    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../menus/formulario.js" defer></script>
    <link rel="stylesheet" href="../../../assets/header-footer/header.css">
    <title>Insertar Noticia</title>
</head>

<body>
    <form action="insertarNoticia.php" method="POST" id="formulario" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="contenido">Descripción:</label><br>
        <textarea id="contenido" name="descripcion" rows="4" cols="50" required></textarea><br><br>
        
        <label for="enlace">Enlace Web:</label>
        <input type="text" id="enlace" name="enlace" required><br><br>
          <span class="errores" style="color:red;"></span>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="imagen">Portada:</label>
        <input type="file" id="imagen" name="imagen" accept="image/*" required><br><br>

        <label for="fuente">Fuente:</label>
        <input type="text" id="fuente" name="fuente" required><br><br>


        <input type="hidden" name="formulario" value="1">
        <input type="submit" id="insertarNoticia" name="insertarNoticia" value="Insertar Noticia">

      

        <a href="../noticias.php">Volver a noticias</a>

    </form>



</body>

</html>