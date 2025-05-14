<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

if (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] === 'monedero_virtual') {
  
    $sqlIDusuario = 'SELECT id_usuarios FROM usuarios WHERE nombre = ?';
    $stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
    mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
    mysqli_execute($stmtIDusuario);
    $resultadoIDusuario = mysqli_stmt_get_result($stmtIDusuario);
    $idUsuario = mysqli_fetch_assoc($resultadoIDusuario)['id_usuarios'];

    $sqlMonedero = 'SELECT monedero_virtual FROM usuarios WHERE id_usuarios = ?';
    $stmtMonedero = mysqli_prepare($conexion, $sqlMonedero);
    mysqli_stmt_bind_param($stmtMonedero, 'i', $idUsuario);
    mysqli_execute($stmtMonedero);
    $resultadoDinero = mysqli_stmt_get_result($stmtMonedero);
    $dineroMonedero = mysqli_fetch_assoc($resultadoDinero)['monedero_virtual'];

    $total = floatval($_POST['total']);

    if ($total > $dineroMonedero) {
        $_SESSION['compra_fallida'] = [
            'items' => $_POST['items'],
            'total' => $total,
            'error' => "No se pudo finalizar la compra, tu saldo es insuficiente"
        ];
        header('Location: compra.php');
        exit();
    }

    
    if (isset($_POST['items'])) {
        $items = $_POST['items'];
        $fechaActual = date('Y-m-d H:i:s');
        $todoCorrecto = true;

        foreach ($items as $item) {
            $idJuego = intval($item['id']);

            $borrarCarritoSQL = 'DELETE FROM carrito WHERE id_videojuego = ? AND id_usuario = ?';
            $stmtBorrarCarrito = mysqli_prepare($conexion, $borrarCarritoSQL);
            mysqli_stmt_bind_param($stmtBorrarCarrito, 'ii', $idJuego, $idUsuario);
            mysqli_execute($stmtBorrarCarrito);

            $finalizarPedido = 'INSERT INTO detalle_pedidos (id_usuarios, id_videojuegos, fecha) VALUES (?, ?, ?)';
            $stmtFinalizarPedido = mysqli_prepare($conexion, $finalizarPedido);
            mysqli_stmt_bind_param($stmtFinalizarPedido, 'iis', $idUsuario, $idJuego, $fechaActual);
            mysqli_execute($stmtFinalizarPedido);


            if (mysqli_stmt_affected_rows($stmtFinalizarPedido) <= 0) {
                $todoCorrecto = false;
                break;
            }
        }

        if ($todoCorrecto) {
            $nuevoSaldo = $dineroMonedero - $total;
            $updateSaldoSQL = 'UPDATE usuarios SET monedero_virtual = ? WHERE id_usuarios = ?';
            $stmtUpdateSaldo = mysqli_prepare($conexion, $updateSaldoSQL);
            mysqli_stmt_bind_param($stmtUpdateSaldo, 'di', $nuevoSaldo, $idUsuario);
            mysqli_execute($stmtUpdateSaldo);

            header('Location: obtener_clave.php?mensaje=correcto');
            exit();
        } else {
            header('Location: obtener_clave.php?mensaje=incorrecto');
            exit();
        }
    }
}
?>
