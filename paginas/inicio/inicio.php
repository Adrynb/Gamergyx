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
    <link rel="stylesheet" href="../../assets/paginas/inicio.css">
    <title>INICIO - Tienda Videojuegos</title>
</head>

<body>
    <header>
        <h2 id="titulo_gamergyx">Gamer<span>gyx</span></h2>

        <section class="header-container">
            <div id="buscar_container">
                <input type="text" placeholder="Buscar..." id="buscar_input">
                <button type="submit" id="buscar_button">Buscar</button>
            </div>

        </section>


        <section class="header-container-usuario">
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


        </section>

    </header>


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

    <main>
        <h1>¡Bienvenido a Gamergyx!</h1>

        <section id="ofertas-section">
            <?php
            $sql = "SELECT * FROM VIDEOJUEGOS WHERE stock > 50 AND precio < 50 ORDER BY RAND() LIMIT 4";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="oferta-item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button>Añadir al carrito</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay ofertas disponibles en este momento.</p>';
            }

            ?>
        </section>


        <section id="novedades-section">
            <h2>Novedades</h2>
            <?php
            $sql = "SELECT * FROM VIDEOJUEGOS ORDER BY fecha_lanzamiento DESC LIMIT 4";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="novedad-item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button>Añadir al carrito</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay novedades disponibles en este momento.</p>';
            }

            ?>
        </section>

        <section id="masvendidos-section">
            <h2>Más Vendidos</h2>
            <?php
            $sql = "SELECT * FROM VIDEOJUEGOS ORDER BY stock DESC LIMIT 4";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="masvendido-item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '">';
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

    <script src="./inicio.js" defer></script>

</body>

</html>