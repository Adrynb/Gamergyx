<?php
include '../../../includes/db.php';
include '../../../includes/sesion.php';
include '../../menus/header.php';

if ($_SESSION["rol"] != "admin") {
    header("Location: ../../index.php");
    exit();
}

if (isset($_GET["id"])) {
    $id_noticia = $_GET["id"];

    $stmt = mysqli_prepare($conexion, "DELETE FROM noticias WHERE id_noticias = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_noticia);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "<script>alert('Noticia borrada correctamente.');</script>";
            mysqli_stmt_close($stmt);
            header("Location: ../noticias.php?mensaje=noticia_borrada");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            echo "<script>alert('Error: No se pudo borrar la noticia.');</script>";
            die("Error en la ejecución: " . mysqli_stmt_error($stmt));
        }


    } else {
        die("Error al preparar la consulta: " . mysqli_error($conexion));
    }

    
}



?>