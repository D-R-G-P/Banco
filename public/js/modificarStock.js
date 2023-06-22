function cerrarAnadirForm() {
    if (anadirForm.style.display == "none") {
        anadirForm.style.display = "flex";
        back.style.display = "flex";
    } else {
        anadirForm.style.display = "none"
        back.style.display = "none"
    }
}