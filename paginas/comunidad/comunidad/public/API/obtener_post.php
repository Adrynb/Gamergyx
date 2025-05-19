<?php

include '../config/db.php';
include '../config/sesion.php';


$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = '$autor'";
$resultIDusuario = mysqli_query($conn, $sqlIDusuario);
$idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];

if (!$resultIDusuario) {
    http_response_code(500);
    echo json_encode(array('status' => 'error', 'message' => 'Error en la consulta a la base de datos.'));
    exit();
}

$sql = "SELECT * FROM posts WHERE id_usuarios = '$idUsuario' ORDER BY fecha DESC";
$result = mysqli_query($conn, $sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(array('status' => 'error', 'message' => 'Error en la consulta a la base de datos.'));
    exit();
}

$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'id_post' => $row['id_post'],
        'post' => $row['post'],
        'fecha' => $row['fecha'],
        'nombre' => $autor
    ];
}

header('Content-Type: application/json');
echo json_encode($posts);


?>