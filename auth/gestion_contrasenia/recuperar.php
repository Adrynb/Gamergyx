<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidado - Contraseña</title>
</head>

<body>
    <h1>¿Has olvidado la contraseña?</h1>

    <form action="recuperar_contrasenia.php" method="post" enctype="multipart/form-data">
        <label for="correo">Escriba su correo para que le enviemos una notificación</label><br>
        <input type="email" name="correo" id="correo" required placeholder="Ingrese su correo"><br><br>
    
        <button type="submit">Recuperar Contraseña</button>
        <br><br>
        <a href="../login.php">Volver al inicio</a>

        <?php if(isset($_GET['error'])) : ?>
            <p style="color:red;"><?=$_GET['error']?></p>
        <? endif ; ?>

    </form>

</body>

</html>