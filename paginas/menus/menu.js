const iconConfig = document.querySelector(".icon_config");
const menuConfig = document.querySelector(".menu_config");

document.querySelectorAll(".menu_container").forEach(container => {
    const icon = container.querySelector(".icon_config, #icon_carrito");
    const menu = container.querySelector(".menu_config");



    if (icon && menu) {
        icon.addEventListener("click", () => {
            menu.style.display = menu.style.display === "block" ? "none" : "block";
            
        });

        document.addEventListener("click", (event) => {
            if (!container.contains(event.target)) {
                menu.style.display = "none";
            }
        });
    }
});


document.getElementById('mostrarCodigo').addEventListener('click', function () {
    document.getElementById('codigo').style.display = 'block';
});

