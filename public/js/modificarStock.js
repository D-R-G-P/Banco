function cerrarAnadirForm() {
    if (anadirForm.style.display == "none") {
        anadirForm.style.display = "flex";
        back.style.display = "flex";
    } else {
        anadirForm.style.display = "none"
        back.style.display = "none"
    }
}

function cerrarEliminarForm() {
    if (eliminarForm.style.display == "none") {
        eliminarForm.style.display = "flex";
        back.style.display = "flex";
    } else {
        eliminarForm.style.display = "none"
        back.style.display = "none"
    }
}