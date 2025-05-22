document.querySelectorAll(".menu_container").forEach(container => {
    const icon = container.querySelector(".icon_config, #icon_carrito");
    const menu = container.querySelector(".menu_config");

    if (icon && menu) {
        icon.addEventListener("click", (event) => {
            event.stopPropagation();
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", (event) => {
            if (!container.contains(event.target)) {
                menu.style.display = "none";
            }
        });
    }
});
