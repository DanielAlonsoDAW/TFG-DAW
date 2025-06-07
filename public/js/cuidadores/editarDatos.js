// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el formulario en la página
  const form = document.querySelector("form");

  // Define los campos a validar con sus elementos y reglas
  const campos = {
    nombre: {
      input: document.getElementById("nombre"),
      error: document.getElementById("error-nombre"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "El nombre es obligatorio.",
    },
    direccion: {
      input: document.getElementById("direccion"),
      error: document.getElementById("error-direccion"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "La dirección es obligatoria.",
    },
    ciudad: {
      input: document.getElementById("ciudad"),
      error: document.getElementById("error-ciudad"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "La ciudad es obligatoria.",
    },
    pais: {
      input: document.getElementById("pais"),
      error: document.getElementById("error-pais"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "El país es obligatorio.",
    },
  };

  // Función para validar un campo individual
  function validarCampo(input, error, validar, mensaje) {
    const valor = input.value.trim();
    if (!validar(valor)) {
      // Si no es válido, añade clase de error y muestra mensaje
      input.classList.add("is-invalid");
      error.textContent = mensaje;
      return false;
    } else {
      // Si es válido, elimina clase de error y limpia mensaje
      input.classList.remove("is-invalid");
      error.textContent = "";
      return true;
    }
  }

  // Añade eventos de validación en tiempo real y al perder el foco
  Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
    input.addEventListener("input", () =>
      validarCampo(input, error, validar, mensaje)
    );
    input.addEventListener("blur", () =>
      validarCampo(input, error, validar, mensaje)
    );
  });

  // Valida todos los campos al enviar el formulario
  form.addEventListener("submit", (e) => {
    let valido = true;
    Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
      if (!validarCampo(input, error, validar, mensaje)) {
        valido = false;
      }
    });

    // Si algún campo no es válido, evita el envío del formulario
    if (!valido) e.preventDefault();
  });
});
