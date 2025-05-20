<?php 

session_start();

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if(!isset($_SESSION['nombre'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

echo json_encode(['status' => 'success', 'nombre' => $_SESSION['nombre']]);


?>