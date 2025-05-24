<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

if (!empty($_POST['id_videojuegos']) || !empty($_GET['id_videojuegos'])) {
    $id = !empty($_POST['id_videojuegos']) ? $_POST['id_videojuegos'] : $_GET['id_videojuegos'];

    $sql = "SELECT VIDEOJUEGOS.*, generos.nombre AS genero, plataformas.nombre AS plataforma 
            FROM VIDEOJUEGOS 
            INNER JOIN generos ON VIDEOJUEGOS.id_generos = generos.id_generos 
            INNER JOIN plataformas ON VIDEOJUEGOS.id_plataforma = plataformas.id_plataformas 
            WHERE id_videojuegos = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = $stmt->get_result();


    $sql = "SELECT fotoPerfil FROM usuarios WHERE nombre = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['nombre']);
    mysqli_stmt_execute($stmt);
    $resultFP = $stmt->get_result();

    if ($resultFP && $rowFP = mysqli_fetch_assoc($resultFP)) {
        $fotoPerfil = $rowFP['fotoPerfil'];
    } else {
        $fotoPerfil = '';
    }

    if ($row = $result->fetch_assoc()) {
        $id_videojuegos = $row['id_videojuegos'];
        $titulo = $row['titulo'];
        $descripcion = $row['descripcion'] ?: 'Lorem ipsum dolor sit amet...';
        $fecha_lanzamiento = $row['fecha_lanzamiento'];
        $genero = $row['genero'];
        $plataforma = $row['plataforma'];
        $precio = $row['precio'];
        $stock = $row['stock'];
        $imagen = $row['imagen'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Videojuegos</title>
    <link rel="stylesheet" href="../../assets/paginas/juego-detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <main>
        <section id="contenedor_videojuego">
            <article id="imagen_article">
                <img src="<?php echo $imagen; ?>" alt="<?php echo $titulo; ?>" id="imagen-videojuego">
            </article>

            <article id="detalle_videojuego">
                <h1><?php echo $titulo; ?></h1>
                <p><strong>Descripción</strong>: <?php echo $descripcion; ?></p>
                <p><strong>Precio</strong>: $<?php echo $precio; ?></p>
                <p><strong>Fecha de Lanzamiento</strong>: <?php echo $fecha_lanzamiento; ?></p>
                <p><strong>Género</strong>: <?php echo $genero; ?></p>
                <p><strong>Plataforma</strong>: <?php echo $plataforma; ?></p>

                <!-- BOTONES -->
                <div class="botones-acciones">
                    <form method="POST" action="juego-detalle.php#botones_videojuego">
                        <input type="hidden" name="id_videojuegos" value="<?php echo $id; ?>">
                        <button type="submit" name="agregar_carrito" title="Agregar al Carrito">
                            <i class="fa fa-shopping-cart"></i>
                        </button>
                    </form>

                    <form method="POST" action="juego-detalle.php#botones_videojuego">
                        <input type="hidden" name="id_videojuegos" value="<?php echo $id; ?>">
                        <button type="submit" name="agregar_favoritos" title="Agregar a Favoritos">
                            <i class="fa fa-heart"></i>
                        </button>
                    </form>
                </div>

            </article>
        </section>

        <!-- SECCIÓN RESEÑAS DEBAJO -->

        <h2 id="titulo">Reseñas</h2>

        <section id="reseñas_videojuego">
            <form method="POST" action="../../reseñas/reseñas.php">
                <div class="foto-perfil-container">
                    <img src="../../assets/images/perfiles/<?php echo $fotoPerfil; ?>" alt="Foto de perfil"
                        class="foto-perfil">
                    <input type="hidden" name="id_videojuegos" value="<?php echo $id_videojuegos; ?>">
                    <textarea name="reseña" rows="4" cols="50" placeholder="Escribe tu comentario aquí..."></textarea>
                </div>
                <div class="rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="estrellas<?php echo $i; ?>" name="estrellas" value="<?php echo $i; ?>" <?php echo $i === 5 ? 'required' : ''; ?>>
                        <label for="estrellas<?php echo $i; ?>"><i class="fa fa-star"></i></label>
                    <?php endfor; ?>
                    <button type="submit" id="button_reseñas">
                        <i class="fa fa-comment"> Publicar</i>
                    </button>
                </div>  
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
                        echo "<div class='estrellas'>";
                        $estrellas = (int)$rowResenas['estrellas'];
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $estrellas) {
                                echo "<i class='fa fa-star' style='color: gold;'></i>";
                            } else {
                                echo "<i class='fa fa-star' style='color: #ccc;'></i>";
                            }
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No hay reseñas disponibles.</p>";
                }
                ?>
            </div>
        </section>
    </main>


    <?php

    if (isset($_POST['agregar_carrito']) && isset($_POST['id_videojuegos'])) {
        $id_videojuegos = $_POST['id_videojuegos'];
        $stmt = mysqli_prepare($conexion, "SELECT id_usuarios FROM usuarios WHERE nombre = ?");
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['nombre']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $idUsuario = mysqli_fetch_assoc($result)['id_usuarios'];

        $stmt = mysqli_prepare($conexion, "SELECT cantidad FROM carrito WHERE id_usuarios = ? AND id_videojuegos = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $nuevaCantidad = $row['cantidad'] + 1;
            $stmt = mysqli_prepare($conexion, "UPDATE carrito SET cantidad = ? WHERE id_usuarios = ? AND id_videojuegos = ?");
            mysqli_stmt_bind_param($stmt, 'iii', $nuevaCantidad, $idUsuario, $id_videojuegos);
            mysqli_stmt_execute($stmt);
        } else {
            $stmt = mysqli_prepare($conexion, "INSERT INTO carrito (id_usuarios, id_videojuegos, cantidad) VALUES (?, ?, 1)");
            mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
            mysqli_stmt_execute($stmt);
        }
    }


    if (isset($_POST['agregar_favoritos']) && isset($_POST['id_videojuegos'])) {
        $id_videojuegos = $_POST['id_videojuegos'];
        $fecha = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare($conexion, "SELECT id_usuarios FROM usuarios WHERE nombre = ?");
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['nombre']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $idUsuario = mysqli_fetch_assoc($result)['id_usuarios'];

        $stmt = mysqli_prepare($conexion, "SELECT id_favoritos FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $stmt = mysqli_prepare($conexion, "DELETE FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?");
            mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
            mysqli_stmt_execute($stmt);
        } else {
            $stmt = mysqli_prepare($conexion, "INSERT INTO favoritos (id_usuarios, id_videojuegos, fecha) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, 'iis', $idUsuario, $id_videojuegos, $fecha);
            mysqli_stmt_execute($stmt);
        }
    }
    ?>

    <?= include '../menus/footer.php'; ?>
</body>

</html>