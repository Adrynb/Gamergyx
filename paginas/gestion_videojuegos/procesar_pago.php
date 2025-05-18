<?php
ob_start();
include '../../includes/db.php';
include '../../includes/sesion.php';

if (!isset($_SESSION['nombre'])) {
    header('Location: ../../login.php');
    exit;
}

$sqlIDusuario = "SELECT id_usuarios, monedero_virtual FROM usuarios WHERE nombre = ?";
$stmtIDusuario = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmtIDusuario, 's', $_SESSION['nombre']);
mysqli_stmt_execute($stmtIDusuario);
$resultIDusuario = mysqli_stmt_get_result($stmtIDusuario);
$userData = mysqli_fetch_assoc($resultIDusuario);
$idUsuario = $userData['id_usuarios'];
$dineroMonedero = floatval($userData['monedero_virtual']);




if (isset($_POST['items']) && is_array($_POST['items']) && isset($_POST['total'])) {
    $items = $_POST['items'];
    $idsJuegos = [];
    $total = $_POST['total'];

    foreach ($items as $item) {
    if (isset($item['id_videojuegos'])) {
        $idsJuegos[] = intval($item['id_videojuegos']);
    }
}



    if ($dineroMonedero < $total) {
        header('Location: carrito.php?mensaje=El saldo es insuficiente');
        exit;
    }


    $nuevoSaldo = $dineroMonedero - $total;
    $updateSaldoSQL = 'UPDATE usuarios SET monedero_virtual = ? WHERE id_usuarios = ?';
    $stmtUpdateSaldo = mysqli_prepare($conexion, $updateSaldoSQL);
    mysqli_stmt_bind_param($stmtUpdateSaldo, 'di', $nuevoSaldo, $idUsuario);
    mysqli_stmt_execute($stmtUpdateSaldo);

    if (mysqli_stmt_affected_rows($stmtUpdateSaldo) > 0) {
        $idsJuegosStr = implode(',', $idsJuegos);
        header("Location: obtener_clave.php?mensaje=correcto&ids_juegos=$idsJuegosStr");
        exit;
    } else {
       header('Location: carrito.php?mensaje=Error del saldo al actualizar.');
        exit;
    }
} else {
    header("Location: carrito.php?metodo_no_agregado&id=$idUsuario");
    exit;
}
?>
