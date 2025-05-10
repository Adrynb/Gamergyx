<?php

include '../menus/header.php';
include '../../includes/db.php';



?>


<main>
    <h2 class="titulo-section" style="background-color: black;">TENDENCIAS</h2>

    <section id="tendencias-section">
        <?php
        $sql = "SELECT DISTINCT * FROM VIDEOJUEGOS ORDER BY RAND() LIMIT 6";
        $sqlTendencias = "SELECT DISTINCT * FROM VIDEOJUEGOS WHERE stock = (SELECT MIN(stock) FROM VIDEOJUEGOS WHERE stock > 0 AND precio < 30  AND 'id_plataforma = 4' LIMIT 1) LIMIT 1";
        $result = mysqli_query($conexion, $sql);
        $resultTendencias = mysqli_query($conexion, $sqlTendencias);

        echo '<div class="tendencias-container">';

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

    <h2 class="titulo-section">NOVEDADES</h2>
    <section id="novedades-section">

        <?php
        $sql = "SELECT id_videojuegos, titulo, imagen, precio FROM VIDEOJUEGOS WHERE fecha_lanzamiento >= '2021-01-01' AND id_plataforma = 4 ORDER BY RAND() DESC LIMIT 8";
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

    <h2 class="titulo-section">DESCUBRE TODO TIPO DE JUEGOS</h2>

    <section id="filtros-juegos">
        <div class="filtro-form">
            <form method="GET" action="pc.php">
                <label for="genero">Género:</label>
                <select name="genero" id="genero">
                    <option value="">Seleccionar</option>
                    <option value="4">Acción</option>
                    <option value="3">Aventura</option>
                    <option value="15">Deportes</option>
                    <option value="1">Carreras</option>
                    <option value="11">Arcade</option>
                    <option value="5">RPG</option>
                    <option value="6">Peleas</option>
                    <option value="10">Estrategia</option>
                    <option value="14">Simulacion</option>
                    <option value="51">Indie</option>
                    <option value="83">Plataformas</option>
                </select>

                <label for="precio">Precio:</label>
                <select name="precio" id="precio">
                    <option value="">Seleccionar</option>
                    <option value="20">$20</option>
                    <option value="50">$50</option>
                    <option value="100">$80</option>
                </select>

                <button type="submit" class="btn btn-primary bg-gradient">Filtrar</button>
                <br><br>
            </form>
        </div>

        <div class="filtro-juegos">
            <?php
            $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
            $precio = isset($_GET['precio']) ? $_GET['precio'] : '';
            $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            $limite = 8;
            $offset = ($pagina - 1) * $limite;

            $sqlFiltros = "SELECT * FROM VIDEOJUEGOS WHERE id_plataforma = 4";
            $sqlContar = "SELECT COUNT(*) as total FROM VIDEOJUEGOS WHERE id_plataforma = 4";

            if ($genero != "") {
                $sqlFiltros .= " AND id_generos = '$genero'";
                $sqlContar .= " AND id_generos = '$genero'";
            }

            if ($precio != "") {
                $sqlFiltros .= " AND precio < '$precio'";
                $sqlContar .= " AND precio < '$precio'";
            }

            $sqlFiltros .= " LIMIT $limite OFFSET $offset";

            $resultFiltros = mysqli_query($conexion, $sqlFiltros);
            $resultContar = mysqli_query($conexion, $sqlContar);
            $total = mysqli_fetch_assoc($resultContar)['total'];
            $totalPaginas = ceil($total / $limite);

            if (mysqli_num_rows($resultFiltros) > 0) {
                while ($row = mysqli_fetch_assoc($resultFiltros)) {
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
                echo '<p>No hay productos disponibles con los filtros seleccionados.</p>';
            }
            ?>

            <div class="paginacion">
                <?php
                if ($pagina > 1) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . ($pagina - 1) . '" class="btn btn-secondary">Anterior</a>';
                }

                for ($i = 1; $i <= $totalPaginas; $i++) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . $i . '" class="btn ' . ($i == $pagina ? 'btn-primary' : 'btn-secondary') . '">' . $i . '</a>';
                }

                if ($pagina < $totalPaginas) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . ($pagina + 1) . '" class="btn btn-secondary">Siguiente</a>';
                }
                ?>
            </div>
        </div>
    </section>

    </section>
</main>

</script>


<?= include '../menus/footer.php'; ?>


<script src=" ../../assets/header-footer/menu.js" defer></script>
</body>

</html>