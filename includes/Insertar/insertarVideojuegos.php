<?php

include '../db.php';

$api = '1b59c7f60d814b35b205f50e2b0182ef';
$cantidadJuegos = 500;
$juegos = [];
$nextUrl = 'https://api.rawg.io/api/games?key=' . $api . '&page_size=40';

while (count($juegos) < $cantidadJuegos && $nextUrl) {
    $response = file_get_contents($nextUrl);
    $data = json_decode($response, true);

    if (!isset($data['results'])) {
        echo 'No se encontraron juegos o ocurrió un error.';
        break;
    }

    foreach ($data['results'] as $juego) {
        $id_rawg = $juego['id'];
        if (!isset($juegos[$id_rawg])) {
            $juegos[$id_rawg] = $juego;
        }
    }

    $nextUrl = $data['next'];
}

$juegos = array_slice($juegos, 0, $cantidadJuegos);

$sqlGeneros = "INSERT INTO generos(id_generos, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)";
$stmtGeneros = $conexion->prepare($sqlGeneros);

$sqlPlataformas = "INSERT INTO plataformas(id_plataformas, nombre) VALUES (?, ?) ON DUPLICATE KEY UPDATE nombre = VALUES(nombre)";
$stmtPlataformas = $conexion->prepare($sqlPlataformas);

$sqlVideojuegos = "INSERT INTO videojuegos(titulo, descripcion, fecha_lanzamiento, id_plataforma, id_generos, precio, stock, imagen) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmtVideojuegos = $conexion->prepare($sqlVideojuegos);

foreach ($juegos as $juego) {
    $titulo = $juego['name'] ?? 'Sin título';
    $fecha = $juego['released'] ?? '2000-01-01';
    $imagen = $juego['background_image'] ?? '';
    $descripcion = $juego['slug'];

    $genero = $juego['genres'][0] ?? ['id' => 0, 'name' => 'Desconocido'];
    $id_generos = $genero['id'];
    $genero_nombre = $genero['name'];

    $plataforma = $juego['platforms'][0]['platform'] ?? ['id' => 0, 'name' => 'Desconocido'];
    $id_plataformas = $plataforma['id'];
    $plataforma_nombre = $plataforma['name'];

    $precio = rand(20, 70);
    $stock = rand(1, 500);

    // Ejecutar inserts
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

?>
