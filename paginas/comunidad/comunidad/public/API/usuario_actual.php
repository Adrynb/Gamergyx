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

$autor = $_SESSION['nombre'];

$sqlIDusuario = "SELECT fotoPerfil, monedero_virtual, nombre FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmt, 's', $autor);
mysqli_stmt_execute($stmt);
$resultIDusuario = mysqli_stmt_get_result($stmt);
if (!$resultIDusuario) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos.']);
    exit();
}
else{
    http_response_code(200);
    echo json_encode(['status' => 'success', 'data' => mysqli_fetch_assoc($resultIDusuario)]);
}



$stmt->close();


?>