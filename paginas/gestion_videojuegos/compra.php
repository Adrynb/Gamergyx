<?php

ob_start();

include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$items = [];
$total = 0;

if (isset($_POST['comprar_todo_ids']) || isset($_POST['id_videojuegos'])) {



    if (isset($_SESSION['compra_fallida'])) {
        $datosRecuperados = $_SESSION['compra_fallida'];
        unset($_SESSION['compra_fallida']);
    }


    $esCompraMultiple = isset($_POST['comprar_todo_ids']);
    $videojuegos = [];

    if ($esCompraMultiple) {
        $veces = intval($_POST['cantidad_veces']) ?? 1;
        $stocks = $_POST['stock_individual'] ?? [];

        foreach ($_POST['comprar_todo_ids'] as $id) {
            $id = intval($id);
            $veces = intval($_POST['cantidad_veces'] ?? 1);

            $sql = "SELECT id_videojuegos, titulo, precio FROM videojuegos WHERE id_videojuegos = ?";
            $stmt = mysqli_prepare($conexion, $sql);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $subtotal = $row['precio'] * $veces;
                $total += $subtotal;

                $items[] = [
                    'id_videojuegos' => $row['id_videojuegos'],
                    'titulo' => $row['titulo'],
                    'precio' => $row['precio'],
                    'cantidad' => $veces,
                    'subtotal' => $subtotal
                ];
            }
        }


    } else {

        $id = intval($_POST['id_videojuegos']);
        $cantidadArray = $_POST['cantidad_videojuegos'] ?? [];
        $cantidad = isset($cantidadArray[$id]) ? intval($cantidadArray[$id]) : 1;

        $sql = "SELECT id_videojuegos, titulo, precio FROM videojuegos WHERE id_videojuegos = ?";
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $subtotal = $row['precio'] * $cantidad;
            $total += $subtotal;

            $items[] = [
                'id_videojuegos' => $row['id_videojuegos'],
                'titulo' => $row['titulo'],
                'precio' => $row['precio'],
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ];
        }
    }
} else {

    header("Location: ./carrito.php");
    exit();


}


?>


<main>
    <h1>Bienvenido, <?= $_SESSION['nombre'] ?></h1>

    <section id="detalles-videojuego">

        <h2>Resumen</h2>
        <?php foreach ($items as $item): ?>
            <h3><?= htmlspecialchars($item['titulo'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p><b>Precio: </b> $<?= number_format($item['precio'], 2) ?></p>
            <p><b>Cantidad: </b> <?= $item['cantidad'] ?></p>
            <p><b>Subtotal: </b> $<?= number_format($item['subtotal'], 2) ?></p>
            <hr>
        <?php endforeach; ?>


    </section>

    <section id="metodos-pago">
        <h2>¿Qué método de pago deseas utilizar?</h2>

        <form action="procesar_pago.php" method="POST">
            <label for="metodo_pago">Selecciona un método de pago:</label>
            <select name="metodo_pago" id="metodo_pago" required>
                <option value="tarjeta_credito">Tarjeta de Crédito</option>
                <option value="paypal">PayPal</option>
                <option value="transferencia_bancaria">Transferencia Bancaria</option>
                <option value="monedero_virtual">Monedero virtual</option>
            </select>
            <br><br>

            <?php foreach ($items as $item): ?>
                <input type="hidden" name="items[<?= $item['id_videojuegos'] ?>][id_videojuegos]"
                    value="<?= $item['id_videojuegos'] ?>">
                <input type="hidden" name="items[<?= $item['id_videojuegos'] ?>][titulo]"
                    value="<?= htmlspecialchars($item['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="items[<?= $item['id_videojuegos'] ?>][cantidad]"
                    value="<?= $item['cantidad'] ?>">
                <input type="hidden" name="items[<?= $item['id_videojuegos'] ?>][subtotal]"
                    value="<?= $item['subtotal'] ?>">
            <?php endforeach; ?>

            <input type="hidden" name="total" value="<?= $total ?>">
            <input type="hidden" name="esCompraMultiple" value="<?= $esCompraMultiple ? '1' : '0' ?>">

            <?php if (isset($_GET['mensaje'])): ?>
                <p style="color:red"><?= $_GET['mensaje'] ?></p>
            <?php endif; ?>

            <button type="submit">Proceder al Pago</button>
        </form>
    </section>


    <?php

    include "../menus/footer.php";

    ?>

</main>