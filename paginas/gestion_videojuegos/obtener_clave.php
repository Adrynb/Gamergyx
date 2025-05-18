<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$sqlIDusuario = 'SELECT id_usuarios FROM usuarios WHERE nombre = ?';
$stmtIDUsuario = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmtIDUsuario, 's', $_SESSION['nombre']);
mysqli_stmt_execute($stmtIDUsuario);
$resultUsuario = mysqli_stmt_get_result($stmtIDUsuario);
$idUsuario = mysqli_fetch_assoc($resultUsuario)['id_usuarios'];
?>

<main>
    <?php if (isset($_GET['ids_juegos'])): ?>
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == "correcto"): ?>
            <h1>¡Felicidades! Aquí están sus códigos</h1>
            <?php
            $ids_juegos = explode(',', $_GET['ids_juegos']);  
            $fechaActual = date('Y-m-d H:i:s');


            foreach ($ids_juegos as $id_juego) {
                $id_juego = intval($id_juego); 
                $codigo = bin2hex(random_bytes(8));
                echo "<p>Su código para el juego ID $id_juego es: <strong>$codigo</strong></p><br />";

                $borrarCarritoSQL = 'DELETE FROM carrito WHERE id_videojuegos = ? AND id_usuarios = ?';
                $stmtBorrarCarrito = mysqli_prepare($conexion, $borrarCarritoSQL);
                mysqli_stmt_bind_param($stmtBorrarCarrito, 'ii', $id_juego, $idUsuario);
                mysqli_stmt_execute($stmtBorrarCarrito);

                $finalizarPedido = 'INSERT INTO pedidos (id_usuarios, id_videojuegos, fecha, codigo_videojuego) VALUES (?, ?, ?, ?)';
                $stmtFinalizarPedido = mysqli_prepare($conexion, $finalizarPedido);
                mysqli_stmt_bind_param($stmtFinalizarPedido, 'iiss', $idUsuario, $id_juego, $fechaActual, $codigo);
                mysqli_stmt_execute($stmtFinalizarPedido);
            }

            ?>
        <?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] == "incorrecto"): ?>
            <h1>Ooops... Algo salió mal.</h1>
        <?php else: ?>
            <h1>No hay ningún mensaje para mostrar.</h1>
        <?php endif; ?>
    <?php else: ?>
        <h1>Ooops... Algo salió mal.</h1>
    <?php endif; ?>
</main>