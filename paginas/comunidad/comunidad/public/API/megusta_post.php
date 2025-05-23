<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include './config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if (!isset($_SESSION['nombre'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (empty($input['idPost']) || empty($input['usuarioActual'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit();
}


$idPost = (int) $input['idPost'];
$usuarioActual = $input['usuarioActual'];



$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmt, 's', $usuarioActual);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    exit();
}

$idUsuario = $row['id_usuarios'];

$sqlVerificar = "SELECT * FROM posts_favoritos WHERE id_post = ? AND id_usuario = ?";
$stmt = mysqli_prepare($conexion, $sqlVerificar);
mysqli_stmt_bind_param($stmt, 'ii', $idPost, $idUsuario);
mysqli_stmt_execute($stmt);

$resultVerificar = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($resultVerificar) > 0) {
    http_response_code(409);
    echo json_encode(['status' => 'error', 'message' => 'El post ya está marcado como favorito']);
    exit();
}

$sqlInsertar = "INSERT INTO posts_favoritos (id_post, id_usuario) VALUES (?, ?)";
$stmt = mysqli_prepare($conexion, $sqlInsertar);
mysqli_stmt_bind_param($stmt, 'ii', $idPost, $idUsuario);
$ejecutar = mysqli_stmt_execute($stmt);

$stmt -> close();


