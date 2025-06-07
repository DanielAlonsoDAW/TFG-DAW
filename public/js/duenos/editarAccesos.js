// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el formulario en la página
  const form = document.querySelector("form");

  // Define los campos a validar con sus elementos, reglas y mensajes
  const campos = {
    email: {
      input: document.getElementById("email"),
      error: document.getElementById("error-email"),
      // Valida formato de correo electrónico
      validar: (valor) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor.trim()),
      mensaje: "Correo electrónico no válido.",
    },
    contrasena_actual: {
      input: document.getElementById("contrasena_actual"),
      error: document.getElementById("error-contrasena_actual"),
      // Valida que no esté vacío
      validar: (valor) => valor.trim() !== "",
      mensaje: "Debes introducir tu contraseña actual.",
    },
    contrasena: {
      input: document.getElementById("contrasena"),
      error: document.getElementById("error-contrasena"),
      // Valida contraseña segura: 8+ caracteres, mayúscula, minúscula, número y símbolo
      validar: (valor) =>
        /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(valor),
      mensaje:
        "Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.",
    },
  };

  // Función para validar un campo y mostrar/ocultar errores
  function validarCampo(input, error, validar, mensaje) {
    const valor = input.value.trim();
    if (!validar(valor)) {
      input.classList.add("is-invalid"); // Marca el campo como inválido
      error.textContent = mensaje; // Muestra el mensaje de error
      return false;
    } else {
      input.classList.remove("is-invalid"); // Quita la marca de inválido
      error.textContent = ""; // Limpia el mensaje de error
      return true;
    }
  }

  // Añade validación dinámica al escribir y al salir de cada campo
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
