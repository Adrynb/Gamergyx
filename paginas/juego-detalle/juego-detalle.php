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
            <form method="POST" action="juego-detalle.php#botones_videojuego">
                <input type="hidden" name="id_videojuegos" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-warning bg-gradient" id="agregar_carrito">Agregar al
                    Carrito</button>

                <?php

                if (isset($_POST['agregar_carrito']) && isset($_POST['id_videojuegos'])) {

                    $id_videojuegos = $_POST['id_videojuegos'];

                    $sqlIDusuario = "SELECT id_usuario FROM usuarios WHERE nombre = ?";
                    $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
                    mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
                    mysqli_stmt_execute($stmtIDusuario);
                    $resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
                    $idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuario'];

                    $sqlCarrito = "INSERT INTO carrito (id_usuario, id_videojuegos) VALUES (?, ?)";
                    $stmtCarrito = mysqli_prepare($conexion, $sqlCarrito);
                    mysqli_stmt_bind_param($stmtCarrito, 'ii', $idUsuario, $id);
                    if (mysqli_stmt_execute($stmtCarrito)) {
                        header("Location: juego-detalle.php?id_videojuegos=$id&videojuego_agregado=Videojuego agregado exitosamente en el carrito");
                        exit();  
                    } else {
                        header("Location: juego-detalle.php?id_videojuegos=$id&videojuego_error=Videojuego no insertado correctamente en el carrito");
                        exit();  
                    }
                    


                }

                ?>

            </form>
            <form method="POST" action="juego-detalle.php#botones_videojuego">
                <input type="hidden" name="id_videojuegos" value="<?php echo $id; ?>">
                <button type="submit" name="agregar_favoritos" class="btn btn-success bg-gradient"
                    id="agregar_favoritos">
                    <img src="../../assets/images/logo/corazon.png" alt="Añadir a favoritos">
                </button>
            </form>


            <?php

            if (isset($_POST['agregar_favoritos']) && isset($_POST['id_videojuegos'])) {
                $id_videojuegos = $_POST['id_videojuegos'];

                $sqlIDusuario = "SELECT id_usuario FROM usuarios WHERE nombre = ?";
                $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
                mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
                mysqli_stmt_execute($stmtIDusuario);
                $resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
                $idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuario'];
                $fecha = date('Y-m-d H:i:s');

                $sqlFavoritos = "INSERT INTO favoritos (id_usuario, id_videojuegos, fecha) VALUES (?, ?, ?)";
                $stmtFavoritos = mysqli_prepare($conexion, $sqlFavoritos);
                mysqli_stmt_bind_param($stmtFavoritos, 'iis', $idUsuario, $id, $fecha);
                if (mysqli_stmt_execute($stmtFavoritos)) {
                    echo "<p style='color:green';>Producto agregado a favoritos.</p>";
                } else {
                    echo "<p style='color:red';>Error al agregar el producto a favoritos.</p>";
                }

            }

            ?>

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