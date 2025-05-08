<?php


include '../../includes/db.php';

session_start();



if ($_SESSION["nombre"] == null || $_SESSION["nombre"] == "") {
    header("Location: ../../auth/login.php");
    exit();
}


$sql = "SELECT * FROM noticias ORDER BY fecha DESC";
$result = mysqli_query($conexion, $sql);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias Actuales ></title>
    <link rel="stylesheet" href="../../assets/paginas/noticias.css">
    <link rel="stylesheet" href="../../assets/header-footer/header.css">
    <link rel="stylesheet" href="../../assets/header-footer/footer.css">
</head>

<header>
    <h2 id="titulo_gamergyx">Gamer<span>gyx</span></h2>
    <nav>
        <ul>
            <li><a href="../inicio/inicio.php">Inicio</a></li>
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

        <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin"): ?>
            <section id="admin_menu_container">
                <img src="../../assets/images/logos/usuario_icon.png" alt="usuario_icon" id="usuario_icon">
                <div id="admin_menu">
                    <ul>
                        <li><a href="./admin/insertarNoticia/insertarNoticia.php">Insertar Noticias</a></li>
                    </ul>
                </div>
            </section>
        <?php endif; ?>
    </section>
    </section>

</header>


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