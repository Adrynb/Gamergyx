<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';




?>


<body>
    <?php

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<h2>" . $row['titulo'] . "</h2>";
            if (!empty($row['portada'])) {
                echo "<img src='../../assets/noticias/" . $row['portada'] . "' alt='Portada de " . $row['titulo'] . "' style='max-width: 100%; height: auto;'>";
            }
            echo "<p>" . $row['contenido'] . "</p>";
            echo "<p><strong>Fecha:</strong> " . $row['fecha'] . "</p>";
            if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") {
                echo "<button><a href='./admin/borrarNoticia.php?id=" . $row['id_noticias'] . "'>Eliminar</a></button>";
                echo "<button><a href='./admin/editarNoticia.php?id=" . $row['id_noticias'] . "&titulo=" .
                    urlencode($row['titulo']) . "&contenido=" . urlencode($row['contenido'])
                    . "&fecha=" . urlencode($row['fecha']) . "&fuente=" . urlencode($row['fuente'])
                    . "&enlace=" . urlencode($row['enlace']) . "&portada=" . urlencode($row['portada']) . "'>Editar</a></button>";

            }
            echo "<button><a href='" . $row['enlace'] . "' target='_blank'>Leer más</a></button>";
            echo "<hr>";
        }
    } else {
        echo "<p>No hay noticias disponibles.</p>";
    }

    if (isset($_GET['mensaje'])) {
        if ($_GET['mensaje'] == 'noticia_borrada') {
            echo "<p style='color:green'>Noticia borrada correctamente.</p>";
        }

        if ($_GET['mensaje'] == 'noticia_insertada') {
            echo "<p style='color:green'>Noticia insertada correctamente.</p>";
        }
        if ($_GET['mensaje'] == 'noticia_editada') {
            echo "<p style='color:green'>Noticia editada correctamente.</p>";
        }
    }

    ?>

    <script src=" ../../assets/header-footer/menu.js" defer></script>
</body>

</html>