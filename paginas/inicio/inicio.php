<?php

include '../../includes/db.php';

session_start();

if ($_SESSION["nombre"] == null || $_SESSION["nombre"] == "") {
    header("Location: ../../auth/login.php");
    exit();
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
    <title>INICIO - Tienda Videojuegos</title>
    <link rel="stylesheet" href="../../assets/paginas/inicio.css">

</head>

<body>
    <header>
        <h2 id="titulo_gamergyx">Gamer<span>gyx</span></h2>


        <nav>
            <ul>
                <li><a href="./inicio.php">Inicio</a></li>
                <li><a href="./noticias.php">Noticias</a></li>
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
                <img src="../../assets/images/logos/usuario_icon.png" alt="usuario_icon" id="usuario_icon">
                <div id="menu_usuario">
                    <ul>
                        <li><a href="../configuracion/config_perfil.php">Editar Perfil</a></li>
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



    <main>
        <section id="banner-section" class="w-100 p-0 m-0">
            <div id="carouselExampleIndicators" class="carousel slide">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                        class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                        aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                        aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner">
                    <?php
                    $sql = "SELECT DISTINCT imagen, titulo FROM VIDEOJUEGOS ORDER BY RAND() LIMIT 3";
                    $result = mysqli_query($conexion, $sql);
                    $active = true;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="carousel-item' . ($active ? ' active' : '') . '">';
                            echo '<img src="' . $row['imagen'] . '" class="d-block w-100 object-fit-cover" alt="' . $row['titulo'] . '">';
                            echo '</div>';
                            $active = false;
                        }
                    } else {
                        echo '<div class="carousel-item active">';
                        echo '<p class="text-center">No hay imágenes disponibles.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>


        <h2 class="titulo-section" style="background-image: url(../../assets/images/banners/ofertas.jpg);">MEJORAS
            OFERTAS DE MERCADO</h2>

        <section id="ofertas-section">
            <?php
            $sql = "SELECT DISTINCT * FROM VIDEOJUEGOS ORDER BY RAND() LIMIT 4";
            $sqlOfertas = "SELECT DISTINCT * FROM VIDEOJUEGOS WHERE stock = (SELECT MAX(stock) FROM VIDEOJUEGOS LIMIT 1) LIMIT 1";
            $result = mysqli_query($conexion, $sql);
            $resultOfertas = mysqli_query($conexion, $sqlOfertas);

            echo '<div class="ofertas-container">';

            if (mysqli_num_rows($resultOfertas) > 0) {
            echo '<div class="oferta-extra-container">';
            while ($row = mysqli_fetch_assoc($resultOfertas)) {
                echo '<div class="oferta-extra-item">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-extra">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<p>Precio: $' . $row['precio'] . '</p>';
                echo '</div>';
            }
            echo '</div>';
            } else {
            echo '<p>No hay ofertas destacadas disponibles en este momento.</p>';
            }
            if (mysqli_num_rows($result) > 0) {
            echo '<div class="oferta-items-container">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="oferta-item">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<p>Precio: $' . $row['precio'] . '</p>';
                echo '</div>';
            }
            echo '</div>';
            } else {
            echo '<p>No hay ofertas disponibles en este momento.</p>';
            }

            echo '</div>'; 
            ?>
        </section>

        ?>
        </section>

        <h2 class="titulo-section">MÁS RECIENTES</h2>
        <section id="novedades-section">

            <?php
            $sql = "SELECT titulo, imagen, precio FROM VIDEOJUEGOS WHERE fecha_lanzamiento >= '2018-01-01' ORDER BY RAND() DESC LIMIT 8";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos recientes disponibles en este momento.</p>';
            }
            ?>
        </section>



        <section id="masvendidos-section">
            <h2 class="titulo-section"">MÁS VENDIDOS</h2>
            <?php
            $sql = "SELECT * FROM VIDEOJUEGOS ORDER BY stock DESC LIMIT 4";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button>Añadir al carrito</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay productos más vendidos disponibles en este momento.</p>';
            }
            ?>

        </section>
    </main>






    <script src=" ./inicio.js" defer>
    </script>


</body>

</html>