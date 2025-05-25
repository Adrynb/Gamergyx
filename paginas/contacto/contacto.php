<?php
include '../../includes/db.php';
include '../../includes/sesion.php';
include '../menus/header.php';

?>
<main>
    <link rel="stylesheet" href="../../assets/paginas/contacto/contacto.css">
    <section id="contacto-form">
        <form method="POST" action="./enviarContacto.php">
            <h1>Formulario de contacto</h1>
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?= $_SESSION['nombre'] ?>" required> <br>

            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required> <br><br>

            <label for="mensaje">Mensaje:</label><br>
            <textarea id="mensaje" name="mensaje" required></textarea><br><br>

            <button id="enviar_formulario" type="submit">Enviar</button>

            <?php if (isset($_GET['mensaje_enviado'])): ?>
                <p style="color:lightgreen"><?= $_GET['mensaje_enviado'] ?></p>
            <?php elseif (isset($_GET['error'])): ?>
                <p style="color:red"><?= $_GET['error'] ?></p>
            <?php endif; ?>
        </form>
    </section>

    <section id="contacto-imagen">
        <img src="../../assets/paginas/contacto/contacto.jpg" alt="contactoFormulario">
    </section>

</main>


<?php

include '../menus/footer.php';



?>