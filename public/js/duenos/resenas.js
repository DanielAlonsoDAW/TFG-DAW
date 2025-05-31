document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll("#rating-stars .bi");
  const input = document.getElementById("calificacion");

  function actualizarEstrellas(valor) {
    stars.forEach((star, index) => {
      if (index < valor) {
        star.classList.remove("bi-star");
        star.classList.add("bi-star-fill");
      } else {
        star.classList.remove("bi-star-fill");
        star.classList.add("bi-star");
      }
    });
  }

  stars.forEach((star) => {
    star.addEventListener("click", () => {
      const valor = parseInt(star.getAttribute("data-value"));
      input.value = valor;
      actualizarEstrellas(valor);
    });
  });

  // Al cargar la página, mantener el valor actual
  if (input.value) {
    actualizarEstrellas(parseInt(input.value));
  }
});
