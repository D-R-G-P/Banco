// Abre userOptions
function menuUser() {
    userOptions.classList.toggle("active");
    flecha.classList.toggle("active");
};

document.addEventListener('click', function (e) {
    if (e.target != userOptions && e.target != user && e.target != userI && e.target != flecha) {
        userOptions.classList.remove('active');
        user.classList.remove('active');
    }
});