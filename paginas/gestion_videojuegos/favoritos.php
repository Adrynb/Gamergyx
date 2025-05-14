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
    }
    if (mysqli_num_rows($resultFavoritos) == 0) {
        echo '<p>No tienes videojuegos favoritos.</p>';
    }

    ?>

</main>