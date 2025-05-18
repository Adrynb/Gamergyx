<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';


if (isset($_POST['id_pedidos'])) {
    $sqlPedidos = "SELECT 
                pedidos.fecha, 
                usuarios.nombre, 
                pedidos.codigo_videojuego, 
                videojuegos.imagen, 
                videojuegos.titulo, 
                generos.nombre AS genero, 
                plataformas.nombre AS plataforma, 
                videojuegos.precio
            FROM pedidos
            INNER JOIN usuarios ON pedidos.id_usuarios = usuarios.id_usuarios
            INNER JOIN videojuegos ON pedidos.id_videojuegos = videojuegos.id_videojuegos
            INNER JOIN generos ON videojuegos.id_generos = generos.id_generos
            INNER JOIN plataformas ON videojuegos.id_plataforma = plataformas.id_plataformas
            WHERE pedidos.id_pedidos = ?";

    $stmt = $conexion->prepare($sqlPedidos);
    $stmt->bind_param("i", $_POST['id_pedidos']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo '<div style="text-align:center;">';
        echo '<table border="1" cellpadding="5" style="color:white; margin: 0 auto;">';
        echo '<tr><th>Fecha</th><th>Usuario</th><th>Código</th><th>Precio</th><th>Título</th><th>Género</th><th>Plataforma</th><th>Imagen</th></tr>';
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
        echo '<td>' . htmlspecialchars($row['codigo_videojuego']) . '</td>';
        echo '<td>' . htmlspecialchars($row['precio']) . ' €</td>';
        echo '<td>' . htmlspecialchars($row['titulo']) . '</td>';
        echo '<td>' . htmlspecialchars($row['genero']) . '</td>';
        echo '<td>' . htmlspecialchars($row['plataforma']) . '</td>';
        echo '<td><img src="' . htmlspecialchars($row['imagen']) . '" alt="Imagen" width="100"></td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    } else {
        echo "No se encontraron detalles para este pedido.";
    }
}
include '../menus/footer.php';

?>