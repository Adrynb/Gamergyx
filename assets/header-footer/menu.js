const usuarioIcon = document.getElementById("usuario_icon");
const usuarioMenu = document.getElementById("menu_usuario");

usuarioIcon.addEventListener("click", function () {
    if (usuarioMenu.style.display === "block") {
        usuarioMenu.style.display = "none";
    } else {
        usuarioMenu.style.display = "block";
    }
});

