<?php
include '../../../includes/db.php';
include '../../../includes/sesion.php';
include '../../menus/header.php';


if ($_SESSION["rol"] != "admin") {
    header("Location: ../../inicio/inicio.php");
    exit();
}

if (isset($_GET["id"])) {
    $id_noticia = $_GET["id"];
    $titulo = urldecode($_GET["titulo"]);
    $descripcion = urldecode($_GET["contenido"]);
    $fecha = urldecode($_GET["fecha"]);
    $fuente = urldecode($_GET["fuente"]);
    $enlace = urldecode($_GET["enlace"]);
    $imagen_actual = urldecode($_GET["portada"]);
} else {
    $id_noticia = $titulo = $descripcion = $fecha = $fuente = $enlace = $imagen_actual = '';
}

if (isset($_POST["editarNoticia"])) {
    $id_noticia = intval($_POST['id_noticia']);
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    $fuente = $_POST['fuente'];
    $enlace = $_POST['enlace'];
    $imagen_actual = $_POST['imagen'];

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        $tipo = $_FILES['imagen']['type'];
        $tam = $_FILES['imagen']['size'];
        $tmp = $_FILES['imagen']['tmp_name'];

        $formatosPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        $maximoTamanio = 2 * 1024 * 1024;

        if (!in_array($tipo, $formatosPermitidos)) {
            die("Error: El formato de la imagen no es compatible. Solo se permiten JPEG, PNG y WEBP.");
        }

        if ($tam > $maximoTamanio) {
            die("Error: El tamaño de la imagen excede el límite de 2 MB.");
        }

        $uploadDir = '../../../assets/noticias/';
        $uploadFile = $uploadDir . basename($imagen);

        if (!move_uploaded_file($tmp, $uploadFile)) {
            die("Error: No se pudo subir la imagen.");
        }
    } else {

        $imagen = $imagen_actual;
    }

    $sql = "UPDATE noticias SET 
                titulo = ?, 
                contenido = ?, 
                portada = ?, 
                fecha = ?, 
                fuente = ?, 
                enlace = ? 
            WHERE id_noticias = ?";

    $stmt = mysqli_prepare($conexion, $sql);

    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
    }

    mysqli_stmt_bind_param($stmt, "ssssssi", $titulo, $descripcion, $imagen, $fecha, $fuente, $enlace, $id_noticia);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: ../noticias.php?mensaje=noticia_editada");
        exit();
    } else {
        mysqli_stmt_close($stmt);
        die("Error al actualizar: " . mysqli_stmt_error($stmt));
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../../menus/formulario.js" defer></script>
    <title>Editar Noticia</title>
</head>

<body>
    <form action="editarNoticia.php" method="POST" id="formulario" enctype="multipart/form-data">
        <input type="hidden" name="id_noticia" value="<?= htmlspecialchars($id_noticia) ?>">
        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($imagen_actual) ?>">

        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($titulo) ?>" required><br><br>
        <span class="errores" style="color:red;"></span>

        <label for="contenido">Descripción:</label><br>
        <textarea id="contenido" name="descripcion" rows="4" cols="50"
            required><?= htmlspecialchars($descripcion) ?></textarea><br><br>
        <span class="errores" style="color:red;"></span>

        <label for="enlace">Enlace Web:</label>
        <input type="text" id="enlace" name="enlace" value="<?= htmlspecialchars($enlace) ?>" required><br><br>
        <span class="errores" style="color:red;"></span>

        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha) ?>" required><br><br>

        <label for="imagen">Portada:</label>
        <?php if (!empty($imagen_actual)): ?>
            <br><img src="../../../assets/noticias/<?= htmlspecialchars($imagen_actual) ?>" alt="Portada actual"
                style="max-width: 200px;"><br>
        <?php endif; ?>
        <input type="file" id="imagen" name="imagen"><br><br>

        <label for="fuente">Fuente:</label>
        <input type="text" id="fuente" name="fuente" value="<?= htmlspecialchars($fuente) ?>" required><br><br>




        <input type="submit" value="Editar Noticia" id="editarNoticia" name="editarNoticia">



        <button><a href="../noticias.php">Volver a noticias</a></button>

    </form>
</body>

</html>