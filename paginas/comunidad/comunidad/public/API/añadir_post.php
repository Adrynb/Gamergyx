<?php

include './config/sesion.php';
include './config/db.php';

$autor = $_SESSION['nombre'];

$datos = json_decode(file_get_contents("php://input"), true);

$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = '$autor'";
$resultIDusuario = mysqli_query($conn, $sqlIDusuario);
$idUsuario = mysqli_fetch_assoc($resultIDusuario)['id_usuarios'];

if (!$resultIDusuario) {
    http_response_code(500);
    echo json_encode(array('status' => 'error', 'message' => 'Error en la consulta a la base de datos.'));
    exit();
}

if (!isset($datos['post']) || trim($datos['post']) == '') {
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'No se ha enviado el post.'));
    exit();

}

$sqlInsertPosts = "INSERT INTO posts (id_usuarios, post, fecha) VALUES ('?', '?', NOW())";
$stmt = mysqli_prepare($conn, $sqlInsertPosts);
$stmt->bind_param("is", $idUsuario, $datos['post']);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(array('status' => 'error', 'message' => 'Error al insertar el post.'));
    exit();
} else {
    echo json_encode(["success" => true, "message" => "Post agregado"]);
}






?>