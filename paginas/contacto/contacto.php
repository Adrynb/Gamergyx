<?php 

include '../menus/header.php';

?>

<main>
    <section id="contacto-form">
        <h1>Formulario de contacto</h1>
        <form method="POST" action="./enviarContacto.php">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" name="mensaje" required></textarea>

            <button type="submit">Enviar</button>

            <?php if(isset($_GET['mensaje_enviado'])) :?>
            <p style="color:green"><?=$_GET['mensaje_enviado']?></p>
            <?php elseif(isset($_GET['error'])): ?>
            <p style="color:red"><?=$_GET['error']?></p>
            <?php endif;?>
                


        </form>
    </section>

    <section id="contacto-imagen">
        <img src="../../assets/images/banners/pepe.png" alt="contactoFormulario">
    </section>
    

<?php 

include '../menus/footer.php';

?>

</main>