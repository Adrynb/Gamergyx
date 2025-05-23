<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

session_start();

if (!isset($_SESSION['nombre'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

include './config/config.php';

$autor = $_SESSION['nombre'];

$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmt, 's', $autor);
mysqli_stmt_execute($stmt);
$resultIDusuario = mysqli_stmt_get_result($stmt);

if (!$resultIDusuario) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos.']);
    exit();
}

$idUsuarioRow = mysqli_fetch_assoc($resultIDusuario);
if (!$idUsuarioRow) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    exit();
}

$idUsuario = $idUsuarioRow['id_usuarios'];

$sql = "SELECT posts.id, posts.contenido, posts.fecha_publicacion, usuarios.nombre, usuarios.fotoPerfil, posts.id_padre, posts.imagen
        FROM posts 
        INNER JOIN usuarios ON posts.id_usuario = usuarios.id_usuarios 
        ORDER BY posts.fecha_publicacion DESC";

$result = mysqli_query($conexion, $sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos.']);
    exit();
}

$posts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $posts[] = [
        'id' => $row['id'],
        'contenido' => $row['contenido'],
        'fecha_publicacion' => $row['fecha_publicacion'],
        'nombre' => $row['nombre'],
        'fotoPerfil' => $row['fotoPerfil'],
        'id_padre' => $row['id_padre'],
        'imagen' => $row['imagen'],
    ];
}

echo json_encode($posts);

$stmt->close();


?>
