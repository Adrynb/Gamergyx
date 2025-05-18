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

$sqlPedidos = 'SELECT * FROM pedidos WHERE id_usuarios = ?';
$stmtPedidos = mysqli_prepare($conexion, $sqlPedidos);
mysqli_stmt_bind_param($stmtPedidos, 'i', $idUsuario);
mysqli_stmt_execute($stmtPedidos);
$resultPedidos = mysqli_stmt_get_result($stmtPedidos);
?>

<h1>Bienvenido a tus pedidos, <?= htmlspecialchars($_SESSION['nombre']) ?></h1>
<div style="display: flex; justify-content: center; margin-top: 30px;">
    <form action="detalles_pedidos.php" method="POST">
        <table border="1" cellpadding="8" cellspacing="0" style="color: white;">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>ID Usuario</th>
                    <th>ID Videojuego</th>
                    <th>Fecha</th>
                    <th>Código Videojuego</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultPedidos) > 0): ?>
                    <?php while ($pedido = mysqli_fetch_assoc($resultPedidos)): ?>
                        <tr>
                            <td><?= htmlspecialchars($pedido['id_pedidos']) ?></td>
                            <td><?= htmlspecialchars($pedido['id_usuarios']) ?></td>
                            <td><?= htmlspecialchars($pedido['id_videojuegos']) ?></td>
                            <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                            <td><?= htmlspecialchars($pedido['codigo_videojuego']) ?></td>
                            <td>
                                <button type="submit" name="id_pedidos" value="<?= htmlspecialchars($pedido['id_pedidos']) ?>">Ver Detalles</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No tienes pedidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
</div>

<?php
include '../menus/footer.php';
?>