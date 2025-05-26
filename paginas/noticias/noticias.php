<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

$sqlNoticias = "SELECT * FROM noticias ORDER BY fecha DESC";
$result = mysqli_query($conexion, $sqlNoticias);
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conexion));
}

?>

<body>
    <main>
        <link rel="stylesheet" href="../../assets/paginas/noticias/noticias.css">
        <h1>Noticias</h1>


        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<div class='noticias-lista'>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='noticia'>";
                if (!empty($row['portada'])) {
                    echo "<img class='noticia-portada' src='../../assets/noticias/" . $row['portada'] . "' alt='Portada de " . $row['titulo'] . "'>";
                }
                echo "<h2 class='noticia-titulo'>" . $row['titulo'] . "</h2>";
                echo "<p class='noticia-contenido'>" . $row['contenido'] . "</p>";
                echo "<p class='noticia-fecha'><strong>Fecha:</strong> " . $row['fecha'] . "</p>";
                echo "<div class='noticia-botones'>";
                if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") {
                    echo "<button class='noticia-btn noticia-eliminar'><a href='./admin/borrarNoticia.php?id=" . $row['id_noticias'] . "'><i class='fa fa-trash'></i> Eliminar</a></button>";
                    echo "<button class='noticia-btn noticia-editar'><a href='./admin/editarNoticia.php?id=" . $row['id_noticias'] . "&titulo=" .
                        urlencode($row['titulo']) . "&contenido=" . urlencode($row['contenido'])
                        . "&fecha=" . urlencode($row['fecha']) . "&fuente=" . urlencode($row['fuente'])
                        . "&enlace=" . urlencode($row['enlace']) . "&portada=" . urlencode($row['portada']) . "'><i class='fa fa-edit'></i> Editar</a></button>";
                }
                echo "<button class='noticia-btn noticia-leer-mas'><a href='" . $row['enlace'] . "' target='_blank'>Leer más</a></button>";
                echo "</div>";
                echo "</div>";
                echo "<hr class='noticia-separador'>";
            }
            echo "</div>";
        } else {
            echo "<p class='noticias-vacio'>No hay noticias disponibles.</p>";
        }

        if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin") {
            echo "<button title='Insertar Noticia' id='insertarNoticia'><a href='./admin/insertarNoticia.php' style='text-decoration:none; color:inherit;'><i class='fa fa-plus' style='font-size:3em;'></i></a></button>";
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

    </main>
</body>

<?php

include '../menus/footer-absolute.php';

?>




</html>