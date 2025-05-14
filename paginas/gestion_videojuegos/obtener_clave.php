<?php 
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

?>


<main>

<?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == "correcto") : ?>

    <h1>¡Felicidades! Haga click para obtener el codigo</h1>
    <button type="submit">Codigo</button>

    <?php 
    
    $codigo = bin2hex(random_bytes(8));
    echo "<p>Su código es: <strong>$codigo</strong></p>";
    
    ?>

<?php elseif (isset($_GET['mensaje']) && $_GET['mensaje'] == "incorrecto") : ?>

    <h1>Ooops... Algo salió mal.</h1>

<?php else : ?>

    <h1>No hay ningún mensaje para mostrar.</h1>

<?php endif; ?>







</main>



<?php 

include '../menus/footer.php'

?>