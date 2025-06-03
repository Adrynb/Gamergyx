<?php 

$conexion = mysqli_connect("ec2-44-213-37-94.compute-1.amazonaws.com", "root", "", "gamergyx");

if (!$conexion) {
    die("Conexión fallida " . mysqli_connect_error());
}




?>