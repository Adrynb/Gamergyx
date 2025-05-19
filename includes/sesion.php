<?php 
session_start();

if (!isset($_SESSION['nombre']) || $_SESSION['nombre'] == "") {
    header("Location: ../../auth/login.php");
    exit();
}

define('BASE_URL', '/gamergyx/');


?>