<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';
?>


<link rel="stylesheet" href="../../assets/paginas/plataformas/plataformas.css">

<main>
    <h2 class="titulo-section">TENDENCIAS</h2>

    <section id="tendencias-section">
        <?php
        $sql = "SELECT DISTINCT * FROM VIDEOJUEGOS ORDER BY RAND() LIMIT 6";
        $sqlTendencias = "SELECT DISTINCT * FROM VIDEOJUEGOS WHERE stock = (SELECT MIN(stock) FROM VIDEOJUEGOS WHERE stock > 0 AND precio < 30 AND id_plataforma = 187 LIMIT 1) LIMIT 1";
        $result = mysqli_query($conexion, $sql);
        $resultTendencias = mysqli_query($conexion, $sqlTendencias);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="oferta-items-container">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                echo '<div class="oferta-item">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<div class="oferta-button-container">';
                echo '<p>Precio: $' . $row['precio'] . '</p>';
                echo '<button type="submit">Ver Detalles</button>';
                echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                echo '</div>';
                echo '</div>';
                echo '</form>';
            }
            echo '</div>';
        } else {
            echo '<p>No hay ofertas disponibles en este momento.</p>';
        }
        ?>
    </section>

    <h2 class="titulo-section">NOVEDADES</h2>
    <section id="novedades-section">
        <?php
        $sql = "SELECT id_videojuegos, titulo, imagen, precio FROM VIDEOJUEGOS WHERE fecha_lanzamiento >= '2021-01-01' AND id_plataforma = 187 ORDER BY RAND() DESC LIMIT 8";
        $result = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<form method="POST" action="../juego-detalle/juego-detalle.php" class="item-form">';
                echo '<div class="oferta-item">';
                echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                echo '<h3>' . $row['titulo'] . '</h3>';
                echo '<div class="oferta-button-container">';
                echo '<p>Precio: $' . $row['precio'] . '</p>';
                echo '<button type="submit">Ver Detalles</button>';
                echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                echo '</div>';
                echo '</div>';
                echo '</form>';
            }
        } else {
            echo '<p>No hay productos recientes disponibles en este momento.</p>';
        }
        ?>
    </section>

    <h2 class="titulo-section" id="titulo-section">DESCUBRE TODO TIPO DE JUEGOS</h2>

    <section class="filtros-juegos">
        <div class="filtro-form">
            <form method="GET" action="#titulo-section">
                <label for="genero">Género:</label>
                <select name="genero" id="genero">
                    <option value="">Seleccionar</option>
                    <option value="4" <?php if (isset($_GET['genero']) && $_GET['genero'] == '4')
                        echo 'selected'; ?>>
                        Acción</option>
                    <option value="3" <?php if (isset($_GET['genero']) && $_GET['genero'] == '3')
                        echo 'selected'; ?>>
                        Aventura</option>
                    <option value="15" <?php if (isset($_GET['genero']) && $_GET['genero'] == '15')
                        echo 'selected'; ?>>
                        Deportes</option>
                    <option value="1" <?php if (isset($_GET['genero']) && $_GET['genero'] == '1')
                        echo 'selected'; ?>>
                        Carreras</option>
                    <option value="11" <?php if (isset($_GET['genero']) && $_GET['genero'] == '11')
                        echo 'selected'; ?>>
                        Arcade</option>
                    <option value="5" <?php if (isset($_GET['genero']) && $_GET['genero'] == '5')
                        echo 'selected'; ?>>RPG
                    </option>
                    <option value="6" <?php if (isset($_GET['genero']) && $_GET['genero'] == '6')
                        echo 'selected'; ?>>
                        Peleas</option>
                    <option value="10" <?php if (isset($_GET['genero']) && $_GET['genero'] == '10')
                        echo 'selected'; ?>>
                        Estrategia</option>
                    <option value="14" <?php if (isset($_GET['genero']) && $_GET['genero'] == '14')
                        echo 'selected'; ?>>
                        Simulacion</option>
                    <option value="51" <?php if (isset($_GET['genero']) && $_GET['genero'] == '51')
                        echo 'selected'; ?>>
                        Indie</option>
                    <option value="83" <?php if (isset($_GET['genero']) && $_GET['genero'] == '83')
                        echo 'selected'; ?>>
                        Plataformas</option>
                </select>

                <label for="precio">Precio:</label>
                <select name="precio" id="precio">
                    <option value="">Seleccionar</option>
                    <option value="20" <?php if (isset($_GET['precio']) && $_GET['precio'] == '20')
                        echo 'selected'; ?>>$20
                    </option>
                    <option value="50" <?php if (isset($_GET['precio']) && $_GET['precio'] == '50')
                        echo 'selected'; ?>>$50
                    </option>
                    <option value="100" <?php if (isset($_GET['precio']) && $_GET['precio'] == '100')
                        echo 'selected'; ?>>
                        $80</option>
                </select>

                <button type="submit">Filtrar</button>
                <br><br>
            </form>
        </div>

        <div class="lista-juegos">
            <?php
            $genero = isset($_GET['genero']) ? $_GET['genero'] : '';
            $precio = isset($_GET['precio']) ? $_GET['precio'] : '';
            $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
            $limite = 8;
            $offset = ($pagina - 1) * $limite;

            $sqlFiltros = "SELECT * FROM VIDEOJUEGOS WHERE id_plataforma = 187";
            $sqlContar = "SELECT COUNT(*) as total FROM VIDEOJUEGOS WHERE id_plataforma = 187";

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
                    $detalleUrl = "../juego-detalle/juego-detalle.php?id_videojuegos=" . $row['id_videojuegos'];
                    echo '<a href="' . $detalleUrl . '" class="lista-juegos-link">';
                    echo '  <div class="lista-juegos-item">';
                    echo '      <img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="lista-juegos-imagen">';
                    echo '      <h3 class="lista-juego-titulo">' . $row['titulo'] . '</h3>';
                    echo '      <div class="lista-juegos-detalles"></div>';
                    echo '      <p class="lista-juegos-precio">$' . $row['precio'] . '</p>';
                    echo '  </div>';
                    echo '</a>';
                }
            }

            else {
                echo '<p>No se encontraron videojuegos que coincidan con los filtros seleccionados.</p>';
            }

            ?>

            <div class="paginacion">
                <?php
                if ($pagina > 1) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . ($pagina - 1) . '#titulo-section" class="btn btn-secondary">Anterior</a>';
                }

                for ($i = 1; $i <= $totalPaginas; $i++) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . $i . '#titulo-section" class="btn ' . ($i == $pagina ? 'btn-primary' : 'btn-secondary') . '">' . $i . '</a>';
                }

                if ($pagina < $totalPaginas) {
                    echo '<a href="?genero=' . $genero . '&precio=' . $precio . '&pagina=' . ($pagina + 1) . '#titulo-section" class="btn btn-secondary">Siguiente</a>';
                }
                ?>
            </div>
        </div>
    </section>
</main>

</script>


<?= include '../menus/footer.php'; ?>


<script src=" ../../assets/header-footer/menu.js" defer></script>
</body>

</html>