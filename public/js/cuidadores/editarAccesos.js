document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");

  const campos = {
    nombre: {
      input: document.getElementById("nombre"),
      error: document.getElementById("error-nombre"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "El nombre es obligatorio.",
    },
    email: {
      input: document.getElementById("email"),
      error: document.getElementById("error-email"),
      validar: (valor) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor.trim()),
      mensaje: "Correo electrónico no válido.",
    },
    contrasena_actual: {
      input: document.getElementById("contrasena_actual"),
      error: document.getElementById("error-contrasena_actual"),
      validar: (valor) => valor.trim() !== "",
      mensaje: "Debes introducir tu contraseña actual.",
    },
    contrasena: {
      input: document.getElementById("contrasena"),
      error: document.getElementById("error-contrasena"),
      validar: (valor) =>
        /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(valor),
      mensaje:
        "Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo.",
    },
  };

  function validarCampo(input, error, validar, mensaje) {
    const valor = input.value.trim();
    if (!validar(valor)) {
      input.classList.add("is-invalid");
      error.textContent = mensaje;
      return false;
    } else {
      input.classList.remove("is-invalid");
      error.textContent = "";
      return true;
    }
  }

  // Validación dinámica al escribir y al salir del campo
  Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
    input.addEventListener("input", () =>
      validarCampo(input, error, validar, mensaje)
    );
    input.addEventListener("blur", () =>
      validarCampo(input, error, validar, mensaje)
    );
  });

  form.addEventListener("submit", (e) => {
    let esValido = true;
    Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
      if (!validarCampo(input, error, validar, mensaje)) {
        esValido = false;
      }
    });
    if (!esValido) {
      e.preventDefault();
    }
  });
});
