const accordionItems = document.querySelectorAll('.accordion-item');

accordionItems.forEach(item => {
  const heading = item.querySelector('.accordion-heading');

  heading.addEventListener('click', () => {
    // Cerrar todos los acordeones
    accordionItems.forEach(accItem => {
      if (accItem !== item) {
        accItem.classList.remove('active');
      }
    });

    // Abrir o cerrar el acordeón seleccionado
    item.classList.toggle('active');
  });
});
