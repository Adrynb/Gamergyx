<?php

if(isset($_POST["game_id"])){
    
    $game_id = $_POST["game_id"];
    
    $sql = "SELECT * FROM videojuegos WHERE id_videojuegos = ?";
    // $stmt = $conexion->prepare($sql);
    // $stmt->bind_param("i", $game_id);
    // $stmt->execute();

    // $resultado = $stmt->get_result();
    // $juego = $resultado->fetch_assoc();
    

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/header-footer/header.css">
    <title>Juego Detalle</title>
</head>

<body>

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

            <div id="carrito_container">
                <img src="../../assets/images/logos/carro-de-la-compra.png" alt="carrito" id="carrito_icon">
            </div>
        </section>
        </section>
    </header>

</body>

</html>