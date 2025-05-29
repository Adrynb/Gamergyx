<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/paginas/resultados.css">
    <title>Buscar Contenido</title>
</head>

<body>
    <header>
       
    </header>

    <main>
        <section id="resultados">
             <h1>Resultados de la búsqueda</h1>
            <?php
            if (isset($_GET['buscar_input'])) {
                $buscar_input = $_GET['buscar_input'];

                $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
                $limite = 8;
                $offset = ($pagina - 1) * $limite;

                $sqlContar = "SELECT COUNT(*) as total FROM VIDEOJUEGOS WHERE titulo LIKE '%" . mysqli_real_escape_string($conexion, $buscar_input) . "%'";
                $resultContar = mysqli_query($conexion, $sqlContar);
                $total = mysqli_fetch_assoc($resultContar)['total'];
                $totalPaginas = ceil($total / $limite);

                if ($totalPaginas > 25) {
                    $totalPaginas = 9;
                }

                $sql = "SELECT * FROM VIDEOJUEGOS WHERE titulo LIKE '%" . mysqli_real_escape_string($conexion, $buscar_input) . "%' LIMIT $limite OFFSET $offset";
                $result = mysqli_query($conexion, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo '<ul class="lista-juego">';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li class="lista-juegos-item">';
                        echo '<a href="../juego-detalle/juego-detalle.php?id_videojuegos=' . htmlspecialchars($row['id_videojuegos']) . '" class="lista-juegos-link">';
                        echo '<img src="' . $row['imagen'] . '" alt="' . htmlspecialchars($row['titulo']) . '" class="lista-juegos-imagen">';
                        echo '</a>';
                        echo '<div class="lista-juegos-detalles">';
                        echo '<span class="lista-juego-titulo">' . htmlspecialchars($row['titulo']) . '</span>';
                        echo '<span class="lista-juegos-precio">Precio: $' . htmlspecialchars($row['precio']) . '</span>';
                        echo '<form method="POST" action="../juego-detalle/juego-detalle.php" style="margin:0">';
                        echo '<input type="hidden" name="id_videojuegos" value="' . htmlspecialchars($row['id_videojuegos']) . '">';
                        echo '<button type="submit" class="lista-juegos-botones">Ver Detalles</button>';
                        echo '</form>';
                        echo '</div>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No se encontraron resultados para "' . htmlspecialchars($buscar_input) . '"</p>';
                }
            }
            ?>
            <div class="paginacion">
                <?php
                if (isset($totalPaginas) && $totalPaginas > 1) {
                    if ($pagina > 1) {
                        echo '<a href="?buscar_input=' . urlencode($buscar_input) . '&pagina=' . ($pagina - 1) . '" class="btn btn-secondary">Anterior</a>';
                    }

                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        echo '<a href="?buscar_input=' . urlencode($buscar_input) . '&pagina=' . $i . '" class="btn ' . ($i == $pagina ? 'btn-primary' : 'btn-secondary') . '">' . $i . '</a>';
                    }

                    if ($pagina < $totalPaginas) {
                        echo '<a href="?buscar_input=' . urlencode($buscar_input) . '&pagina=' . ($pagina + 1) . '" class="btn btn-secondary">Siguiente</a>';
                    }
                }
                ?>
            </div>

        </section>
    </main>

    <?php include '../menus/footer.php'; ?>
    <script src="../menus/menu.js"></script>

</body>

</html>