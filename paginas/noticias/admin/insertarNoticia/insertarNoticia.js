let formulario = document.getElementById("formulario");
let inputTitulo = document.getElementById("titulo");
let inputDescripcion = document.getElementById("descripcion");
let inputImagen = document.getElementById("imagen");
let inputFecha = document.getElementById("fecha");
let inputFuente = document.getElementById("fuente");
let inputEnlace = document.getElementById("enlace");
let mensajeError = document.getElementById("mensajeError");

formulario.addEventListener("submit", e => { 
    e.preventDefault();
    let errores = [];

    if (!/^[A-Z]/.test(inputTitulo.value)) {
        errores.push("El título debe comenzar con una letra mayúscula.");
    }

    if (inputDescripcion.value.length < 10) {
        errores.push("La descripción debe tener al menos 10 caracteres.");
    } else if (inputDescripcion.value.length > 200) {
        errores.push("La descripción no puede tener más de 200 caracteres.");
    }

    if (!/^([0-2][0-9]|3[0-1])(\/|-)(0[1-9]|1[0-2])\2(\d{4})$/.test(inputFecha.value)) {
        errores.push("La fecha debe tener el formato dd/mm/yyyy o dd-mm-yyyy.");
    }

    if (!/^[A-Z]/.test(inputFuente.value)) {
        errores.push("La fuente debe comenzar con una letra mayúscula.");
    }

    if (!/^(https?:\/\/)?([a-z0-9-]+\.)+[a-z]{2,}(:\d+)?(\/.*)?$/.test(inputEnlace.value)) {
        errores.push("El enlace debe ser una URL válida.");
    }

    if (errores.length > 0) {
        mensajeError.style.color = "red";
        mensajeError.textContent = errores.join(" ");
    } else {
        mensajeError.style.color = "green";
        mensajeError.textContent = "Formulario enviado correctamente.";


    }
});