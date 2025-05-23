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


$sql = "
    SELECT 
        p.id,
        p.contenido,
        p.imagen,
        p.fecha_publicacion,
        u.nombre AS autor,
        COUNT(pf.id) AS likes
    FROM posts p
    LEFT JOIN posts_favoritos pf ON p.id = pf.id_post
    INNER JOIN usuarios u ON p.id_usuario = u.id_usuarios
    GROUP BY p.id
    ORDER BY likes DESC, p.fecha_publicacion DESC
    LIMIT 10
";


$stmt = mysqli_prepare($conexion, $sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos']);
    exit();
}
mysqli_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos']);
    exit();
}

$posts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
    $result->free();
}

header('Content-Type: application/json');
echo json_encode($posts);

$stmt->close();