<?php

include '../../includes/db.php';

session_start();

if ($_SESSION["nombre"] == null || $_SESSION["nombre"] == "") {
    header("Location: ../../auth/login.php");
    exit();
}


$nombre = $_SESSION['nombre'];

$sqlFP = "SELECT fotoPerfil FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlFP);
mysqli_stmt_bind_param($stmt, "s", $nombre);
mysqli_stmt_execute($stmt);
$resultFP = mysqli_stmt_get_result($stmt);

if ($resultFP && $row = mysqli_fetch_assoc($resultFP)) {
    $fotoPerfil = $row['fotoPerfil'];
} else {
    $fotoPerfil = '../../assets/images/logos/usuario_icon.png'; 
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets">
    <meta name="description" content="Tienda de videojuegos, compra y venta de videojuegos, consolas y accesorios.">
    <meta name="keywords" content="videojuegos, consolas, accesorios, compra, venta, tienda, videojuegos en línea">
    <meta name="author" content="Adrián Navarro Buceta">
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans:wght@400..700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/header-footer/header.css">
    <link rel="stylesheet" href="../../assets/header-footer/footer.css">

    <title>INICIO - Tienda Videojuegos</title>
    <link rel="stylesheet" href="../../assets/paginas/inicio.css">

</head>

<body>
    <header>
        <h2 id="titulo_gamergyx">Gamer<span>gyx</span></h2>


        <nav>
            <ul>
                <li><a href="../inicio/inicio.php">Inicio</a></li>
                <li><a href="../noticias/noticias.php">Noticias</a></li>
                <li><a href="#">Plataformas</a>
                    <ul>
                        <li><a href="./plataformas/nintendo.php">Nintendo Switch</a></li>
                        <li><a href="./plataformas/playstation.php">PlayStation</a></li>
                        <li><a href="./plataformas/xbox.php">Xbox</a></li>
                        <li><a href="./plataformas/pc.php">PC</a></li>
                    </ul>
                </li>
                <li><a href="./comunidad/comunidad.php">Comunidad</a></li>
                <li><a href="./contacto.php">Contacto</a></li>
            </ul>
        </nav>


        <section class="header-container-usuario">
            <div id="buscar_container">
                <input type="text" placeholder="Buscar..." id="buscar_input">
            </div>
            <span id="usuario_dinero">$100.00</span>


            <section id="usuario_menu_container">
                <?php if (empty($fotoPerfil)): ?>
                    <img src="../../assets/images/logos/usuario_icon.png" alt="usuario_icon" id="usuario_icon">
                <?php else: ?>
                    <img src="../../assets/images/perfiles/<?= $fotoPerfil ?>" alt="usuario_icon" id="usuario_icon">
                <?php endif; ?>

                <div id="menu_usuario">
                    <ul>
                        <li><a href="../configuracion/editar_perfil.php">Editar
                                Perfil</a></li>
                        <li><a href="../configuracion/mis_pedidos.php">Mis pedidos</a></li>
                        <li><a href="../configuracion/cerrar_sesion.php">Cerrar Sesión</a></li>
                    </ul>
                </div>
            </section>

            <div id="carrito_container">
                <img src="../../assets/images/logos/carro-de-la-compra.png" alt="carrito" id="carrito_icon">

            </div>
        </section>
        </section>
</header>

