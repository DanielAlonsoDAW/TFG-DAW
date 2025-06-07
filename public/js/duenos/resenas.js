document.addEventListener("DOMContentLoaded", function () {
  // Selecciona todas las estrellas dentro del elemento con id "rating-stars"
  const stars = document.querySelectorAll("#rating-stars .bi");
  // Selecciona el input donde se guarda la calificación
  const input = document.getElementById("calificacion");

  // Función para actualizar el aspecto de las estrellas según el valor dado
  function actualizarEstrellas(valor) {
    stars.forEach((star, index) => {
      if (index < valor) {
        // Si el índice es menor que el valor, muestra la estrella rellena
        star.classList.remove("bi-star");
        star.classList.add("bi-star-fill");
      } else {
        // Si no, muestra la estrella vacía
        star.classList.remove("bi-star-fill");
        star.classList.add("bi-star");
      }
    });
  }

  // Añade un evento de click a cada estrella
  stars.forEach((star) => {
    star.addEventListener("click", () => {
      // Obtiene el valor de la estrella clicada
      const valor = parseInt(star.getAttribute("data-value"));
      // Actualiza el valor del input
      input.value = valor;
      // Actualiza la visualización de las estrellas
      actualizarEstrellas(valor);
    });
  });

  // Al cargar la página, mantiene el valor actual de las estrellas si ya hay una calificación
  if (input.value) {
    actualizarEstrellas(parseInt(input.value));
  }
});
