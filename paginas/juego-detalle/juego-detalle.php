<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$isCompra = false;
$isFavorito = false;
$fotoPerfil = '';
$id_videojuegos = null;


if (isset($_POST['id_videojuegos']) || isset($_GET['id_videojuegos'])) {
    $id_videojuegos = $_POST['id_videojuegos'] ?? $_GET['id_videojuegos'];


    $stmt = mysqli_prepare($conexion, "SELECT id_usuarios, fotoPerfil FROM usuarios WHERE nombre = ?");
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['nombre']);
    mysqli_stmt_execute($stmt);
    $resultUser = mysqli_stmt_get_result($stmt);
    $userData = mysqli_fetch_assoc($resultUser);
    $idUsuario = $userData['id_usuarios'];
    $fotoPerfil = $userData['fotoPerfil'];

    if (isset($_POST['agregar_carrito'])) {
        $stmt = mysqli_prepare($conexion, "SELECT cantidad FROM carrito WHERE id_usuarios = ? AND id_videojuegos = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_fetch_assoc($result)) {
            $stmt = mysqli_prepare($conexion, "DELETE FROM carrito WHERE id_usuarios = ? AND id_videojuegos = ?");
            $isCompra = false;
        } else {
            $stmt = mysqli_prepare($conexion, "INSERT INTO carrito (id_usuarios, id_videojuegos, cantidad) VALUES (?, ?, 1)");
            $isCompra = true;
        }
        mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
        mysqli_stmt_execute($stmt);
    }
    if (isset($_POST['agregar_favoritos'])) {
        $fecha = date('Y-m-d H:i:s');
        $stmt = mysqli_prepare($conexion, "SELECT id_favoritos FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?");
        mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $stmt = mysqli_prepare($conexion, "DELETE FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?");
            mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
            $isFavorito = false;
            mysqli_stmt_execute($stmt);
        } else {
            $stmt = mysqli_prepare($conexion, "INSERT INTO favoritos (id_usuarios, id_videojuegos, fecha) VALUES (?, ?, NOW())");
            mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
            $isFavorito = true;
            mysqli_stmt_execute($stmt);
        }
    }


    $stmt = mysqli_prepare($conexion, "SELECT 1 FROM carrito WHERE id_usuarios = ? AND id_videojuegos = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
    mysqli_stmt_execute($stmt);
    $isCompra = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ? true : false;

    $stmt = mysqli_prepare($conexion, "SELECT 1 FROM favoritos WHERE id_usuarios = ? AND id_videojuegos = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $idUsuario, $id_videojuegos);
    mysqli_stmt_execute($stmt);
    $isFavorito = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ? true : false;


    $sql = "SELECT v.*, g.nombre AS genero, p.nombre AS plataforma
            FROM videojuegos v
            INNER JOIN generos g ON v.id_generos = g.id_generos
            INNER JOIN plataformas p ON v.id_plataforma = p.id_plataformas
            WHERE id_videojuegos = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id_videojuegos);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $videojuego = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalle Videojuego</title>
    <link rel="stylesheet" href="../../assets/paginas/juego-detalle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <main>
        <section id="contenedor_videojuego">
            <article id="imagen_article">
                <img src="<?= $videojuego['imagen']; ?>" alt="<?= $videojuego['titulo']; ?>" id="imagen-videojuego">
            </article>
            <article id="detalle_videojuego">
                <h1><?= $videojuego['titulo']; ?></h1>
                <p><strong>Descripción:</strong> <?= $videojuego['descripcion'] ?: 'Lorem ipsum dolor sit amet...'; ?>
                </p>
                <p><strong>Precio:</strong> $<?= $videojuego['precio']; ?></p>
                <p><strong>Fecha de Lanzamiento:</strong> <?= $videojuego['fecha_lanzamiento']; ?></p>
                <p><strong>Género:</strong> <?= $videojuego['genero']; ?></p>
                <p><strong>Plataforma:</strong> <?= $videojuego['plataforma']; ?></p>

                <div class="botones-acciones">
                    <form method="POST">
                        <input type="hidden" name="id_videojuegos" value="<?= $id_videojuegos; ?>">
                        <button type="submit" name="agregar_carrito" title="Agregar al Carrito">
                            <i class="fa fa-shopping-cart <?= $isCompra ? 'icono-activo' : ''; ?>"></i>
                        </button>
                    </form>

                    <form method="POST">
                        <input type="hidden" name="id_videojuegos" value="<?= $id_videojuegos; ?>">
                        <button type="submit" name="agregar_favoritos" title="Agregar a Favoritos">
                            <i class="fa fa-heart <?= $isFavorito ? 'icono-activo' : ''; ?>"></i>
                        </button>
                    </form>
                </div>
            </article>
        </section>

        <!-- Reseñas -->
        <h2 id="titulo">Reseñas</h2>
        <section id="reseñas_videojuego">
            <form method="POST" action="../../reseñas/reseñas.php">
                <div class="foto-perfil-container">
                    <img src="../../assets/images/perfiles/<?= $fotoPerfil; ?>" alt="Foto de perfil"
                        class="foto-perfil">
                    <input type="hidden" name="id_videojuegos" value="<?= $id_videojuegos; ?>">
                    <textarea name="reseña" rows="4" cols="50" placeholder="Escribe tu comentario aquí..."></textarea>
                </div>
                <div class="rating">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" id="estrellas<?= $i; ?>" name="estrellas" value="<?= $i; ?>" <?= $i === 5 ? 'required' : ''; ?>>
                        <label for="estrellas<?= $i; ?>"><i class="fa fa-star"></i></label>
                    <?php endfor; ?>
                    <button type="submit" id="button_reseñas">
                        <i class="fa fa-comment"> Publicar</i>
                    </button>
                </div>
            </form>

            <div id="reseñas">
                <?php
                $stmtResenas = mysqli_prepare($conexion, "SELECT * FROM reseñas WHERE id_videojuegos = ?");
                mysqli_stmt_bind_param($stmtResenas, 'i', $id_videojuegos);
                mysqli_stmt_execute($stmtResenas);
                $resultResenas = mysqli_stmt_get_result($stmtResenas);

                if ($resultResenas && mysqli_num_rows($resultResenas) > 0) {
                    while ($rowResenas = mysqli_fetch_assoc($resultResenas)) {
                        echo "<div class='comentario'>";
                        echo "<img src='../../assets/images/perfiles/{$rowResenas['fotoPerfil']}' alt='Foto de perfil' class='foto-perfil'>";
                        echo "<p><strong>{$rowResenas['usuario']}</strong>: {$rowResenas['comentarios']}</p>";
                        echo "<div class='estrellas'>";
                        for ($i = 1; $i <= 5; $i++) {
                            $color = $i <= (int) $rowResenas['estrellas'] ? 'gold' : '#ccc';
                            echo "<i class='fa fa-star' style='color: $color;'></i>";
                        }
                        echo "</div></div>";
                    }
                } else {
                    echo "<p>No hay reseñas disponibles.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <?php include '../menus/footer.php'; ?>
</body>

</html>