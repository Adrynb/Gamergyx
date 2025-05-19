
let nombreInput = document.getElementById("nombre");
let contrasenaInput = document.getElementById("contraseña");
let emailInput = document.getElementById("email");
let errores = document.getElementsByClassName("errores");
let enlaceWebInput = document.getElementById("enlace");
let fechaInput = document.getElementById("fecha");
let formulario = document.getElementById("formulario");
console.log("Formulario encontrado:", formulario);


formulario.addEventListener("submit", function (event) {

    for (let i = 0; i < errores.length; i++) {
        errores[i].textContent = "";
        errores[i].style.display = "none";
    }

    console.log("Validando el formulario...");

    let hayErrores = false;

    let nombre = nombreInput.value.trim();
    let email = emailInput.value.trim();
    let contrasena = contrasenaInput.value;
    let enlaceWeb = enlaceWebInput.value.trim();
    let fecha = fechaInput ? fechaInput.value.trim() : "";


    if (nombre.length < 6) {

        console.log("El nombre debe tener al menos 6 caracteres.");
        errores[0].textContent = "El nombre debe tener al menos 6 caracteres.";
        hayErrores = true;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errores[1].textContent = "Introduce un email válido.";
        hayErrores = true;
    }

    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    if (!passwordRegex.test(contrasena)) {
        errores[2].textContent = "La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.";
        hayErrores = true;

    }
    
    if (enlaceWebInput && errores[3]) {
        const enlaceWebRegex = /^https?:\/\/([a-z0-9-]+\.)+[a-z]{2,}(:\d+)?(\/[^\s]*)?$/i;
        if (!enlaceWebRegex.test(enlaceWeb)) {
            console.log("El enlace web debe comenzar con http:// o https:// y ser válido.");
            errores[3].textContent = "Introduce un enlace web válido que comience con http:// o https://.";
            hayErrores = true;
        }
    }

    if (fechaInput && errores[4]) {
        const fechaRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (fecha && !fechaRegex.test(fecha)) {
            errores[4].textContent = "Introduce una fecha válida en el formato YYYY-MM-DD.";
            hayErrores = true;
        }
    }

    for (let i = 0; i < errores.length; i++) {
        if (errores[i].textContent !== "") {
            errores[i].style.display = "block";
        }
    }

 if (hayErrores) {
    console.log("Se han detectado errores. No se enviará el formulario.");
    event.preventDefault();
}

});
