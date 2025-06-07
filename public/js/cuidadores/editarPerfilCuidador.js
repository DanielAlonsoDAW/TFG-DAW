// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el formulario en la página
  const form = document.querySelector("form");

  // Define los campos del formulario, sus elementos de entrada, mensajes de error y funciones de validación
  const campos = {
    nombre: {
      input: document.getElementById("nombre"),
      error: document.getElementById("error-nombre"),
      // Valida que el nombre no esté vacío
      validar: (valor) => valor.trim() !== "",
      mensaje: "El nombre es obligatorio.",
    },
    email: {
      input: document.getElementById("email"),
      error: document.getElementById("error-email"),
      // Valida el formato del email
      validar: (valor) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor.trim()),
      mensaje: "Email no válido.",
    },
    telefono: {
      input: document.getElementById("telefono"),
      error: document.getElementById("error-telefono"),
      // Valida que el teléfono empiece por 6, 7 o 9 y tenga 9 dígitos
      validar: (valor) => /^(6|7|9)\d{8}$/.test(valor.trim()),
      mensaje: "Teléfono inválido.",
    },
    direccion: {
      input: document.getElementById("direccion"),
      error: document.getElementById("error-direccion"),
      // Valida que la dirección no esté vacía
      validar: (valor) => valor.trim() !== "",
      mensaje: "La dirección es obligatoria.",
    },
    ciudad: {
      input: document.getElementById("ciudad"),
      error: document.getElementById("error-ciudad"),
      // Valida que la ciudad no esté vacía
      validar: (valor) => valor.trim() !== "",
      mensaje: "La ciudad es obligatoria.",
    },
    pais: {
      input: document.getElementById("pais"),
      error: document.getElementById("error-pais"),
      // Valida que el país no esté vacío
      validar: (valor) => valor.trim() !== "",
      mensaje: "El país es obligatorio.",
    },
    max_mascotas_dia: {
      input: document.getElementById("max_mascotas_dia"),
      error: document.getElementById("error-max_mascotas_dia"),
      // Valida que sea un número entero mayor que 0
      validar: (valor) => /^\d+$/.test(valor.trim()) && parseInt(valor) > 0,
      mensaje: "Número inválido.",
    },
  };

  // Añade validación en tiempo real y al salir del campo para cada input
  Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
    // Valida al salir del campo
    input.addEventListener("blur", () =>
      validarCampo(input, error, validar, mensaje)
    );
    // Valida mientras se escribe
    input.addEventListener("input", () =>
      validarCampo(input, error, validar, mensaje)
    );
  });

  // Función para validar un campo y mostrar/ocultar el mensaje de error
  function validarCampo(input, error, validar, mensaje) {
    if (!validar(input.value)) {
      input.classList.add("is-invalid");
      error.textContent = mensaje;
      return false;
    } else {
      input.classList.remove("is-invalid");
      error.textContent = "";
      return true;
    }
  }

  // Valida todos los campos al enviar el formulario
  form.addEventListener("submit", (e) => {
    let esValido = true;
    Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
      if (!validarCampo(input, error, validar, mensaje)) {
        esValido = false;
      }
    });

    // Si algún campo no es válido, evita el envío del formulario
    if (!esValido) {
      e.preventDefault();
    }
  });
});
