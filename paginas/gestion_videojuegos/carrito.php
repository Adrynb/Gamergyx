<?php

include("../menus/header.php");


if (isset($_POST["id_videojuegos"])) {
    $id_videojuegos = $_POST["id_videojuegos"];

    $sqlIDusuario = "SELECT id_usuario FROM usuarios WHERE nombre = ?";
    $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
    mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
    mysqli_stmt_execute($stmtIDusuario);
    $resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
    $idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuario'];

    $sqlCarrito = "SELECT videojuegos.* FROM carrito 
               INNER JOIN videojuegos ON carrito.id_videojuego = videojuegos.id 
               WHERE carrito.id_usuario = ?";

    $prepareCarrito = mysqli_prepare($conexion, $sqlCarrito);
    mysqli_stmt_bind_param($prepareCarrito, 'i', $idUsuario);
    mysqli_stmt_execute($prepareCarrito);
    $resultCarrito = mysqli_stmt_get_result($prepareCarrito);
} else {
    header("Location: ../inicio/inicio.php");
    exit();
}

?>



<main>

    <h1>Carrito de compras</h1>

    <section id="carrito-section">

        <?php
        if (mysqli_num_rows($resultCarrito) > 0) {
            while ($row = mysqli_fetch_assoc($resultCarrito)) {
                echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                echo '<div class="item">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<p>Precio: $' . $row['precio'] . '</p>';
                echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                echo '</div>';
                echo '</form>';
            }
        } else {
            echo '<p>No hay productos en tu carrito.</p>';
        }
        ?>
    </section>


</main>