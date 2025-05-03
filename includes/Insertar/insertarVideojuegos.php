<?php

include '../db.php';

$api = '1b59c7f60d814b35b205f50e2b0182ef';
$juegos = [];
$url = 'https://api.rawg.io/api/games?key=' . $api . '&page=1';
$cantidadJuegos = 500;

while (count($juegos) < $cantidadJuegos) {
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (isset($data['results'])) {
        $juegos = array_merge($juegos, $data['results']);
    } else {
        echo 'No se encontraron juegos o ocurrió un error.';
        break;
    }

    if (!isset($data['next'])) {
        break;
    }
}

$juegos = array_slice($juegos, 0, $cantidadJuegos);


$sqlGeneros = "INSERT INTO generos(id_generos, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)";
$stmtGeneros = $conexion->prepare($sqlGeneros);

$sqlPlataformas = "INSERT INTO plataformas(id_plataformas, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)";
$stmtPlataformas = $conexion->prepare($sqlPlataformas);

$sqlVideojuegos = "INSERT INTO videojuegos(titulo, descripcion, fecha_lanzamiento, id_plataforma, id_generos, precio, stock, imagen) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmtVideojuegos = $conexion->prepare($sqlVideojuegos);

if ($juegos) {
    foreach ($juegos as $juego) {
        $titulo = $juego['name'];
        $fecha = $juego['released'];
        $id_plataformas = $juego['platforms'][0]['platform']['id'];
        $plataforma_nombre = $juego['platforms'][0]['platform']['name'];
        $id_generos = $juego['genres'][0]['id'];
        $genero_nombre = $juego['genres'][0]['name'];
        $imagen = $juego['background_image'];
        $descripcion = $juego['description_raw'] ?? 'No description available.';
        $precio = $juego['price'] ?? rand(20, 70); 
        $stock = $juego['stock'] ?? rand(1, 500);

        $stmtGeneros->bind_param("is", $id_generos, $genero_nombre);
        $stmtGeneros->execute();

        $stmtPlataformas->bind_param("is", $id_plataformas, $plataforma_nombre);
        $stmtPlataformas->execute();

        $stmtVideojuegos->bind_param("sssiiiis", $titulo, $descripcion, $fecha, $id_plataformas, $id_generos, $precio, $stock, $imagen);
        $stmtVideojuegos->execute();

    }

    $stmtGeneros->close();
    $stmtPlataformas->close();
    $stmtVideojuegos->close();
}
?>