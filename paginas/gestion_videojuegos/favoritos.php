<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
$stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
mysqli_stmt_execute($stmtIDusuario);
$resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);

$idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];


$sqlFavoritos = "SELECT videojuegos.* FROM favoritos 
                INNER JOIN videojuegos ON favoritos.id_videojuegos = videojuegos.id_videojuegos 
                WHERE favoritos.id_usuarios = ?";
$prepareFavoritos = mysqli_prepare($conexion, $sqlFavoritos);
mysqli_stmt_bind_param($prepareFavoritos, 'i', $idUsuario);
mysqli_stmt_execute($prepareFavoritos);
$resultFavoritos = mysqli_stmt_get_result($prepareFavoritos);


?>

<main>
    <h1>Tus videojuegos favoritos</h1>

    <?php

    while ($columna = mysqli_fetch_assoc($resultFavoritos)) {
        echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
        echo '<div class="item">';
        echo '<img src="' . $columna['imagen'] . '" alt="' . $columna['titulo'] . '" class="imagen-section">';
        echo '<h3>' . $columna['titulo'] . '</h3>';
        echo '<p>Precio: $' . $columna['precio'] . '</p>';
        echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
        echo '<input type="hidden" name="id_videojuegos" value="' . $columna['id_videojuegos'] . '">';
        echo '</div>';
        echo '</form>';

        echo '<form method="POST" action="favoritos.php" class="item-form">';
        echo '<button type="submit" class="btn btn-warning bg-gradient" name="agregar_carrito">Agregar al carrito</button>';
        echo '<input type="hidden" name="id_videojuegos" value="' . $columna['id_videojuegos'] . '">';
        echo '<div class="item">';

    }
    if (mysqli_num_rows($resultFavoritos) == 0) {
        echo '<p>No tienes videojuegos favoritos.</p>';
    }

    ?>

</main>

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