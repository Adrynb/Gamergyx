<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
include './config/config.php';

if (!isset($_SESSION['nombre'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

$idObtenida = json_decode(file_get_contents('php://input'), true);
if (empty($idObtenida['id'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID vacío']);
    exit();
}

$idPost = (int) $idObtenida['id'];


$sqlBorrarRespuestas = "DELETE FROM posts WHERE id_padre = ?";
$stmtRespuestas = mysqli_prepare($conexion, $sqlBorrarRespuestas);
mysqli_stmt_bind_param($stmtRespuestas, 'i', $idPost);
mysqli_stmt_execute($stmtRespuestas);

$sqlBorrarFavoritos = "DELETE FROM posts_favoritos WHERE id_post = ?";
$stmtFav = mysqli_prepare($conexion, $sqlBorrarFavoritos);
mysqli_stmt_bind_param($stmtFav, 'i', $idPost);
mysqli_stmt_execute($stmtFav);

// Luego borrar el post
$sqlBorrarPost = "DELETE FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conexion, $sqlBorrarPost);
mysqli_stmt_bind_param($stmt, 'i', $idPost);
mysqli_stmt_execute($stmt);


if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Post eliminado']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el post']);
}
?>
