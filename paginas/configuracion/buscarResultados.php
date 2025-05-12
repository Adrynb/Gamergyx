<?php

include "../menus/header.php";



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

            if (isset($_POST['buscar_input'])) {
                $buscar_input = $_POST['buscar_input'];
                $sql = "SELECT * FROM VIDEOJUEGOS WHERE titulo LIKE '%$buscar_input%'";
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
        </section>
    </main>

    <?php include '../menus/footer.php'; ?>
    <script src="../menus/menu.js"></script>

</body>

</html>