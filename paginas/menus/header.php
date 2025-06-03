<?php
ob_start();
include_once __DIR__ . '/../../includes/db.php';
include_once __DIR__ . '/../../includes/sesion.php';



$nombre = $_SESSION['nombre'];

$sqlFP = "SELECT fotoPerfil FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlFP);
mysqli_stmt_bind_param($stmt, "s", $nombre);
mysqli_stmt_execute($stmt);
$resultFP = mysqli_stmt_get_result($stmt);

$sqlMonedero = "SELECT monedero_virtual FROM usuarios WHERE nombre = ?";
$stmtMonedero = mysqli_prepare($conexion, $sqlMonedero);
mysqli_stmt_bind_param($stmtMonedero, "s", $nombre);
mysqli_stmt_execute($stmtMonedero);
$resultMonedero = mysqli_stmt_get_result($stmtMonedero);

if ($resultFP && $row = mysqli_fetch_assoc($resultFP)) {
    $fotoPerfil = $row['fotoPerfil'];
} else {
    $fotoPerfil = '';
}

if ($resultMonedero && $rowMonedero = mysqli_fetch_assoc($resultMonedero)){
    $monedero_virtual = $rowMonedero['monedero_virtual'];
} else {
    $monedero_virtual = 0.00;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/header-footer/header.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/header-footer/footer.css" />
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/logos/g.png">

    <meta name="description" content="Tienda de videojuegos, compra y venta de videojuegos, consolas y accesorios." />
    <meta name="keywords" content="videojuegos, consolas, accesorios, compra, venta, tienda, videojuegos en línea" />
    <meta name="author" content="Adrián Navarro Buceta" />
    <link
      href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
      crossorigin="anonymous"
      defer
    ></script>

    <title>INICIO - Tienda Videojuegos</title>
</head>

<body>
    <header>
        <h2 id="titulo_gamergyx">
            Gamer<span>gyx</span>
        </h2>

        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>paginas/inicio/inicio.php">Inicio</a></li>
                <li><a href="<?= BASE_URL ?>paginas/noticias/noticias.php">Noticias</a></li>
                <li>
                    <a href="#">Plataformas</a>
                    <ul>
                        <li><a href="<?= BASE_URL ?>paginas/plataformas/nintendo.php">Nintendo Switch</a></li>
                        <li><a href="<?= BASE_URL ?>paginas/plataformas/playstation.php">PlayStation</a></li>
                        <li><a href="<?= BASE_URL ?>paginas/plataformas/xbox.php">Xbox</a></li>
                        <li><a href="<?= BASE_URL ?>paginas/plataformas/pc.php">PC</a></li>
                    </ul>
                </li>
                <li><a href="http://localhost:3000">Comunidad</a></li>
                <li><a href="<?= BASE_URL ?>paginas/contacto/contacto.php">Contacto</a></li>
            </ul>
        </nav>

        <section class="header-container-usuario">
            <div id="buscar_container">
            <form action="<?= BASE_URL ?>paginas/configuracion/buscarResultados.php" method="GET" enctype="multipart/form-data" style="position: relative;">
                <input type="text" placeholder="Buscar..." id="buscar_input" name="buscar_input" style="padding-right: 2.5em;" />
                <button type="submit" id="buscar_btn" style="position: absolute; right: 0.3em; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer;">
                <i class="fa fa-search"></i>
                </button>
            </form>
            </div>

            <span id="usuario_dinero"><?= number_format($monedero_virtual, 2) ?> €</span>

            <section class="menu_container">
            <?php if (empty($fotoPerfil)): ?>
                <img
                src="<?= BASE_URL ?>assets/images/logos/usuario_icon.png"
                alt="usuario_icon"
                class="icon_config"
                />
            <?php else: ?>
                <img
                src="<?= BASE_URL ?>assets/images/perfiles/<?= htmlspecialchars($fotoPerfil) ?>"
                alt="usuario_icon"
                class="icon_config"
                />
            <?php endif; ?>

            <div class="menu_config">
                <ul>
                <li><a href="<?= BASE_URL ?>paginas/configuracion/editar_perfil.php">Editar Perfil</a></li>
                <li><a href="<?= BASE_URL ?>paginas/configuracion/mis_pedidos.php">Mis pedidos</a></li>
                <li><a href="<?= BASE_URL ?>paginas/configuracion/cerrar_sesion.php">Cerrar Sesión</a></li>
                </ul>
            </div>
            </section>

            <section class="menu_container">
            <img
                src="<?= BASE_URL ?>assets/images/logos/carro-de-la-compra.png"
                alt="carrito"
                id="icon_carrito"
            />

            <div class="menu_config" id="menu_config_carrito">
                <ul>
                <li><a href="<?= BASE_URL ?>paginas/gestion_videojuegos/carrito.php">Carrito</a></li>
                <li><a href="<?= BASE_URL ?>paginas/gestion_videojuegos/favoritos.php">Favoritos</a></li>
                </ul>
            </div>
            </section>
        </section>
    </header>

    <script src="<?= BASE_URL ?>paginas/menus/menu.js" defer></script>
