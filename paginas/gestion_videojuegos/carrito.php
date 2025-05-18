<?php

ob_start();

include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';


$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
$stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
mysqli_stmt_execute($stmtIDusuario);
$resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
$idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];

$sqlCarrito = "SELECT videojuegos.* FROM carrito 
               INNER JOIN videojuegos ON carrito.id_videojuegos = videojuegos.id_videojuegos
               WHERE carrito.id_usuarios = ?";
$prepareCarrito = mysqli_prepare($conexion, $sqlCarrito);
mysqli_stmt_bind_param($prepareCarrito, 'i', $idUsuario);
mysqli_stmt_execute($prepareCarrito);
$resultCarrito = mysqli_stmt_get_result($prepareCarrito);
?>

<main>
    <h1>Carrito de compras</h1>

    <section id="carrito-section">
        <?php
        if (mysqli_num_rows($resultCarrito) > 0) {
            while ($row = mysqli_fetch_assoc($resultCarrito)) {
                echo '<div class="item">';
                echo '<img src="' . htmlspecialchars($row['imagen'], ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '" class="imagen-section">';
                echo '<h3>' . htmlspecialchars($row['titulo'], ENT_QUOTES, 'UTF-8') . '</h3>';
                echo '<p>Precio: $' . htmlspecialchars($row['precio'], ENT_QUOTES, 'UTF-8') . '</p>';

                echo '<form method="POST" action="../juego-detalle/juego-detalle.php">';
                echo '<input type="hidden" name="id_videojuegos" value="' . htmlspecialchars($row['id_videojuegos'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" name="ver_detalles" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                echo '</form>';

                echo '<form method="POST" action="compra.php">';
                echo '<input type="hidden" name="id_videojuegos" value="' . htmlspecialchars($row['id_videojuegos'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<label for="cantidad_' . $row['id_videojuegos'] . '">Cantidad:</label>';
                echo '<input type="number" name="cantidad_videojuegos[' . htmlspecialchars($row['id_videojuegos'], ENT_QUOTES, 'UTF-8') . ']" min="1" required>';
                echo '<button type="submit" name="finalizar_compra" class="btn btn-warning bg-gradient">Finalizar Compra</button>';
                echo '</form>';
                echo '</div>';

                echo '<form method="POST" action="carrito.php">';
                echo '<input type="hidden" name="id_videojuego" value="' . htmlspecialchars($row['id_videojuegos'], ENT_QUOTES, 'UTF-8') . '">';
                echo '<button type="submit" name="eliminar_producto" class="btn btn-danger bg-gradient">Eliminar del Carrito</button>';
                echo '</form>';
            }
        } else {
            echo '<p>No hay productos en tu carrito.</p>';
        }
        ?>
    </section>

    <?php if (mysqli_num_rows($resultCarrito) > 0): ?>
        <section id="carrito_compra_section">
            <h2>Comprar todo el carrito</h2>
            <form method="POST" action="compra.php">
                <?php
                mysqli_data_seek($resultCarrito, 0);
                while ($row = mysqli_fetch_assoc($resultCarrito)) {
                    echo '<input type="hidden" name="comprar_todo_ids[]" value="' . htmlspecialchars($row['id_videojuegos'], ENT_QUOTES, 'UTF-8') . '">';
                    echo '<input type="hidden" name="stock_individual[' . $row['id_videojuegos'] . ']" value="' . htmlspecialchars($row['stock'], ENT_QUOTES, 'UTF-8') . '">';
                }
                ?>
                <label for="cantidad_veces">Veces que desea repetir el carrito completo:</label>
                <input type="number" name="cantidad_veces" min="1" value="1" required>
                <button type="submit" name="finalizar_compra_todo" class="btn btn-success bg-gradient">Comprar Todo</button>
                <?php if (isset($_GET['mensaje'])): ?>
                    <p style="color: red;"><?=$_GET['mensaje']?></p>
                <?php endif; ?>

                
            </form>
        </section>
    <?php endif; ?>

    <?php

    if (isset($_POST['eliminar_producto'])) {
        $id_videojuegos = $_POST['id_videojuego'];
        $sqlBorrarVideojuegoCarrito = 'DELETE FROM carrito WHERE id_videojuegos = ?';
        $stmtBorrarVideojuegoCarrito = mysqli_prepare($conexion, $sqlBorrarVideojuegoCarrito);
        mysqli_stmt_bind_param($stmtBorrarVideojuegoCarrito, 'i', $id_videojuegos);
        mysqli_stmt_execute($stmtBorrarVideojuegoCarrito);
        exit(header("Location:carrito.php"));

    }


    ?>

<?php 

include "../menus/footer.php";


?>

</main>