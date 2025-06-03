<?php


header("Access-Control-Allow-Origin: http://44.213.37.94:4000");
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
$sqlIDusuario = "SELECT id_usuarios FROM usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conexion, $sqlIDusuario);
mysqli_stmt_bind_param($stmt, 's', $autor);
mysqli_stmt_execute($stmt);
$resultIDusuario = mysqli_stmt_get_result($stmt);

if (!$resultIDusuario) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en la consulta a la base de datos. No se encuentra el nombre']);
    exit();
}

$row = mysqli_fetch_assoc($resultIDusuario);
if (!$row) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    exit();
}
$idUsuario = $row['id_usuarios'];

$posts = json_decode(file_get_contents('php://input'), true);

if (empty($posts['contenido'])) {  
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Contenido vacío']);
    exit();
}

$idPadre = isset($posts['id_padre']) ? $posts['id_padre'] : null;
$imagen = isset($posts['imagen']) ? $posts['imagen'] : null;
$rutaImagen = null;

if ($imagen) {
    $directorio = __DIR__ . '/imagenes/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0755, true);
    }
    $nombreArchivo = uniqid('img_') . '.png';
    $rutaCompleta = $directorio . $nombreArchivo;


    if (preg_match('/^data:image\/(\w+);base64,/', $imagen, $tipo)) {
        $imagen = substr($imagen, strpos($imagen, ',') + 1);
        $extension = strtolower($tipo[1]);
        $nombreArchivo = uniqid('img_') . '.' . $extension;
        $rutaCompleta = $directorio . $nombreArchivo;
    }

    $imagen = base64_decode($imagen);

    if ($imagen === false) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Imagen no válida']);
        exit();
    }

    if (file_put_contents($rutaCompleta, $imagen) === false) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar la imagen']);
        exit();
    }

    
    $rutaImagen = 'imagenes/' . $nombreArchivo;
}

$insertarPost = "INSERT INTO posts (contenido, id_usuario, fecha_publicacion, id_padre, imagen) VALUES (?, ?, NOW(), ?, ?)";


$stmt = mysqli_prepare($conexion, $insertarPost);

if ($idPadre === null) {
    $idPadreParam = null;
    mysqli_stmt_bind_param($stmt, 'siss', $posts['contenido'], $idUsuario, $idPadreParam, $rutaImagen);
} else {
    $idPadreInt = (int)$idPadre;
    mysqli_stmt_bind_param($stmt, 'siis', $posts['contenido'], $idUsuario, $idPadreInt, $rutaImagen);
}

mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Post añadido correctamente']);
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error al añadir el post a la base de datos.']);
}

$stmt->close();

?>
