<?php

include '../menus/header.php';
include '../../includes/db.php';

if (isset($_POST['id_videojuegos']) || isset($_GET['id_videojuegos'])) {
    $id = $_POST['id_videojuegos'];
    if (empty($id)) {
        $id = $_GET['id_videojuegos'];
    }

    $sql = "SELECT VIDEOJUEGOS.*, generos.nombre AS genero, plataformas.nombre AS plataforma 
            FROM VIDEOJUEGOS 
            INNER JOIN generos ON VIDEOJUEGOS.id_generos = generos.id_generos 
            INNER JOIN plataformas ON VIDEOJUEGOS.id_plataforma = plataformas.id_plataformas 
            WHERE id_videojuegos = ?";
    $stmtVideojuegos = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmtVideojuegos, 'i', $id);

    if ($stmtVideojuegos->execute()) {
        $resultVideojuegos = $stmtVideojuegos->get_result();
        while ($rowVideojuegos = $resultVideojuegos->fetch_assoc()) {
            $id_videojuegos = $rowVideojuegos['id_videojuegos'];
            $titulo = $rowVideojuegos['titulo'];
            $descripcion = !empty($rowVideojuegos['descripcion']) ? $rowVideojuegos['descripcion'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
            $fecha_lanzamiento = $rowVideojuegos['fecha_lanzamiento'];
            $genero = $rowVideojuegos['genero'];
            $plataforma = $rowVideojuegos['plataforma'];
            $precio = $rowVideojuegos['precio'];
            $stock = $rowVideojuegos['stock'];
            $imagen = $rowVideojuegos['imagen'];
        }
    } else {
        echo "Error en la consulta: " . mysqli_error($conexion);

    }

} else {
    header("Location: ../inicio/inicio.php");
    exit();
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Videojuegos</title>
</head>

<body>
    <main>
        <section id='contenedor_videojuego'>
            <article id="imagen_videojuego">
                <img src="<?php echo $imagen; ?>" alt="<?php echo $titulo; ?>" class="imagen-videojuego">
            </article>
            <article id="detalle_videojuego">
                <h1><?php echo $titulo; ?></h1>
                <p><strong>Descripción</strong> <?php echo $descripcion; ?></p>
                <p><strong>Precio</strong> $<?php echo $precio; ?></p>
                <p><strong>Fecha de Lanzamiento</strong> <?php echo $fecha_lanzamiento; ?></p>
                <p><strong>Género</strong> <?php echo $genero; ?></p>
                <p><strong>Plataforma</strong> <?php echo $plataforma; ?></p>
            </article>
        </section>

        <section id="botones_videojuego">
            <form method="POST" action="../carrito/carrito.php">
                <input type="hidden" name="id_videojuegos" value="<?php echo $id_videojuegos; ?>">
                <button type="submit" class="btn btn-warning bg-gradient">Agregar al Carrito</button>
            </form>
            <form method="POST" action="../comprar/comprar.php">
                <input type="hidden" name="id_videojuegos" value="<?php echo $id_videojuegos; ?>">
                <button type="submit" class="btn btn-success bg-gradient">Comprar Ahora</button>
            </form>
        </section>

        <section id="reseñas_videojuego">
            <h2>Reseñas</h2>
            <form method="POST" action="../../reseñas/reseñas.php">
                <input type="hidden" name="id_videojuegos" value="<?php echo $id_videojuegos; ?>">
                <textarea name="reseña" rows="4" cols="50" placeholder="Escribe tu comentario aquí..."></textarea>
                <button type="submit" class="btn btn-primary bg-gradient">Enviar Reseña</button>
            </form>
            <div id="reseñas">
                <?php
                $sqlResenas = "SELECT * FROM reseñas WHERE id_videojuegos = ?";
                $stmtResenas = mysqli_prepare($conexion, $sqlResenas);
                mysqli_stmt_bind_param($stmtResenas, 'i', $id_videojuegos);
                mysqli_stmt_execute($stmtResenas);
                $resultResenas = mysqli_stmt_get_result($stmtResenas);

                if ($resultResenas && mysqli_num_rows($resultResenas) > 0) {
                    while ($rowResenas = mysqli_fetch_assoc($resultResenas)) {
                        echo "<div class='comentario'>";
                        echo "<img src='../../assets/images/perfiles/" . $rowResenas['fotoPerfil'] . "' alt='Foto de perfil' class='foto-perfil'>";
                        echo "<p><strong>" . $rowResenas['usuario'] . "</strong>: " . $rowResenas['comentarios'] . "</p>";
                        echo "</div>";
                    }


                } else {
                    echo "<p>No hay reseñas disponibles.</p>";
                }
                ?>
        </section>

    </main>

    <?= include '../menus/footer.php'; ?>


</body>

</html>