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
    <title>Buscar Contenido</title>
</head>

<body>
    <header>
        <h1>Resultados de la búsqueda</h1>
    </header>

    <main>
        <section id="resultados">
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
                    $totalPaginas = 25;
                }

                $sql = "SELECT * FROM VIDEOJUEGOS WHERE titulo LIKE '%" . mysqli_real_escape_string($conexion, $buscar_input) . "%' LIMIT $limite OFFSET $offset";
                $result = mysqli_query($conexion, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="resultado-item">';
                        echo '<img src="' . $row['imagen'] . '" alt="' . $row['titulo'] . '" class="imagen-section">';
                        echo '<h3>' . $row['titulo'] . '</h3>';
                        echo '<p>Precio: $' . $row['precio'] . '</p>';
                        echo '<form method="POST" action="../juego-detalle/juego-detalle.php">';
                        echo '<input type="hidden" name="id_videojuegos" value="' . $row['id_videojuegos'] . '">';
                        echo '<button type="submit" class="btn btn-warning bg-gradient">Ver Detalles</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No se encontraron resultados para "' . htmlspecialchars($buscar_input) . '"</p>';
                }
                
            }
            ?>
            <div id="paginacion">
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