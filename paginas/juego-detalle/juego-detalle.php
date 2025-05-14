<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

if (!empty($_POST['id_videojuegos']) || !empty($_GET['id_videojuegos']) || isset($_POST['id_videojuegos'])) {
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
                <button type="submit" name="agregar_carrito" class="btn btn-warning bg-gradient"
                    id="agregar_carrito">Agregar al Carrito</button>


                <?php

                if (isset($_GET['videojuego_error'])) {
                    echo '<p style="color:red;">Error al insertar el videojuego</p>';
                } else if (isset($_GET['videojuego_agregado'])) {
                    echo '<p style="color:green;">Insertado el videojuego al carrito correctamente</p>';
                }

                ?>

                <?php

                if (isset($_POST['agregar_carrito']) && isset($_POST['id_videojuegos'])) {

                    $id_videojuegos = $_POST['id_videojuegos'];

                    $sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
                    $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);

                    if ($stmtIDusuario) {
                        mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
                        mysqli_stmt_execute($stmtIDusuario);
                    } else {
                        die("Error en la preparación de la consulta: " . mysqli_error($conexion));
                    }
                    $resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
                    $idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];


                    $sqlCheckCarrito = "SELECT cantidad FROM carrito WHERE id_usuarios = ? AND id_videojuegos = ?";
                    $stmtCheckCarrito = mysqli_prepare($conexion, $sqlCheckCarrito);
                    mysqli_stmt_bind_param($stmtCheckCarrito, 'ii', $idUsuario, $id_videojuegos);
                    mysqli_stmt_execute($stmtCheckCarrito);
                    $resultCheckCarrito = mysqli_stmt_get_result($stmtCheckCarrito);

                    if ($resultCheckCarrito && mysqli_num_rows($resultCheckCarrito) > 0) {
                        $rowCarrito = mysqli_fetch_assoc($resultCheckCarrito);
                        $nuevaCantidad = $rowCarrito['cantidad'] + 1;

                        $sqlUpdateCarrito = "UPDATE carrito SET cantidad = ? WHERE id_usuarios = ? AND id_videojuegos = ?";
                        $stmtUpdateCarrito = mysqli_prepare($conexion, $sqlUpdateCarrito);
                        mysqli_stmt_bind_param($stmtUpdateCarrito, 'iii', $nuevaCantidad, $idUsuario, $id_videojuegos);
                        if (mysqli_stmt_execute($stmtUpdateCarrito)) {
                            echo "<p style='color:green;'>Cantidad actualizada en el carrito.</p>";
                        } else {
                            echo "<p style='color:red;'>Error al actualizar la cantidad en el carrito.</p>";
                        }
                    } else {
                        $sqlCarrito = "INSERT INTO carrito (id_usuarios, id_videojuegos, cantidad) VALUES (?, ?, 1)";
                        $stmtCarrito = mysqli_prepare($conexion, $sqlCarrito);
                        mysqli_stmt_bind_param($stmtCarrito, 'ii', $idUsuario, $id_videojuegos);
                        if (mysqli_stmt_execute($stmtCarrito)) {
                            echo "<p style='color:green;'>Producto agregado al carrito.</p>";
                        } else {
                            echo "<p style='color:red;'>Error al agregar el producto al carrito.</p>";
                        }
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

                $sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
                $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
                mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
                mysqli_stmt_execute($stmtIDusuario);
                $resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
                $idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];


                $fecha = date('Y-m-d H:i:s');


                $sqlComprobarSiExiste = 'SELECT id_favoritos FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?';
                $stmtComprobarExiste = mysqli_prepare($conexion, $sqlComprobarSiExiste);
                mysqli_stmt_bind_param($stmtComprobarExiste, 'ii', $idUsuario, $id_videojuegos);
                mysqli_stmt_execute($stmtComprobarExiste);
                $resultComprobarExiste = mysqli_stmt_get_result($stmtComprobarExiste);

                if (mysqli_num_rows($resultComprobarExiste) > 0) {
                    $sqlBorrar = 'DELETE FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?';
                    $stmtBorrar = mysqli_prepare($conexion, $sqlBorrar);
                    mysqli_stmt_bind_param($stmtBorrar, 'ii', $idUsuario, $id_videojuegos);
                    if (mysqli_stmt_execute(statement: $stmtBorrar)) {
                        echo "<p style='color:red;'>Producto eliminado de favoritos.</p>";
                    } else {
                        echo "<p style='color:red;'>Error al eliminar el producto de favoritos.</p>";
                    }
                    return;
                } else {
                    $sqlFavoritos = "INSERT INTO favoritos (id_usuarios, id_videojuegos, fecha) VALUES (?, ?, ?)";
                    $stmtFavoritos = mysqli_prepare($conexion, $sqlFavoritos);
                    mysqli_stmt_bind_param($stmtFavoritos, 'iis', $idUsuario, $id, $fecha);
                    if (mysqli_stmt_execute($stmtFavoritos)) {
                        echo "<p style='color:green';>Producto agregado a favoritos.</p>";
                    } else {
                        echo "<p style='color:red';>Error al agregar el producto a favoritos.</p>";
                    }

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