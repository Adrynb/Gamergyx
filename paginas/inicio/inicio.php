<?php

include '../menus/header.php';


 ?>
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
                    echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                    echo '<div class="oferta-extra-item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-extra">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                    echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                    echo '</div>';
                    echo '</form>';
                }
                echo '</div>';
            } else {
                echo '<p>No hay ofertas destacadas disponibles en este momento.</p>';
            }
            if (mysqli_num_rows($result) > 0) {
                echo '<div class="oferta-items-container">';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="oferta-item">';
                    echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                    echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                    echo '</div>';
                    echo '</form>';
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
            $sql = "SELECT id_videojuegos, titulo, imagen, precio FROM VIDEOJUEGOS WHERE fecha_lanzamiento >= '2018-01-01' ORDER BY RAND() DESC LIMIT 8";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                    echo '<div class="item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                    echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                    echo '</div>';
                    echo '</form>';
                }
            } else {
                echo '<p>No hay productos recientes disponibles en este momento.</p>';
            }
            ?>
        </section>

        <h2 class="titulo-section">MÁS VENDIDOS</h2>

        <section id="masvendidos-section">

            <?php
            $sql = "SELECT * FROM VIDEOJUEGOS ORDER BY stock DESC LIMIT 4";
            $result = mysqli_query($conexion, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                    echo '<div class="item">';
                    echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                    echo '<h3>' . $row['titulo'] . '</h3>';
                    echo '<p>Precio: $' . $row['precio'] . '</p>';
                    echo '<input type="hidden" name="game_id" value="' . $row['id_videojuegos'] . '">';
                    echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                    echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                    echo '</div>';
                    echo '</form>';
                }
            } else {
                echo '<p>No hay productos más vendidos disponibles en este momento.</p>';
            }
            ?>
            

        </section>

        </section>
    </main>

    </script>


    <?= include '../menus/footer.php'; ?>


    <script src=" ../../assets/header-footer/menu.js" defer></script>


</body>

</html>