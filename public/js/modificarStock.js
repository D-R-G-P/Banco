function toggleForm() {
    if (formCodebar.style.display == "none") {
        formCodebar.style.display = "flex";
        back.style.display = "flex";
    } else {
        formCodebar.style.display = "none"
        back.style.display = "none"
    }
}

function openAdd() {
    if (add.style.display == "none") {
        add.style.display = "flex";
        remove.style.display = "none";
    } else {
        add.style.display = "none"
        remove.style.display = "none";
    }
}

function openRemove() {
    if (remove.style.display == "none") {
        remove.style.display = "flex";
        add.style.display = "none";
    } else {
        add.style.display = "none"
        remove.style.display = "none";
    }
}

function toggleAdd() {
    if (agregarForm.style.display == "none") {
        agregarForm.style.display = "flex";
        back.style.display = "flex";
    } else {
        agregarForm.style.display = "none";
        back.style.display = "none";
    }
}