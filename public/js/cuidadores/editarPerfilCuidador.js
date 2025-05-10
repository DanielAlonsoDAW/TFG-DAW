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
      mensaje: "Email no válido.",
    },
    telefono: {
      input: document.getElementById("telefono"),
      error: document.getElementById("error-telefono"),
      validar: (valor) => /^(6|7|9)\d{8}$/.test(valor.trim()),
      mensaje: "Teléfono inválido.",
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
    max_mascotas_dia: {
      input: document.getElementById("max_mascotas_dia"),
      error: document.getElementById("error-max_mascotas_dia"),
      validar: (valor) => /^\d+$/.test(valor.trim()) && parseInt(valor) > 0,
      mensaje: "Número inválido.",
    },
  };

  // Validación al escribir o salir de foco
  Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
    input.addEventListener("blur", () =>
      validarCampo(input, error, validar, mensaje)
    );
    input.addEventListener("input", () =>
      validarCampo(input, error, validar, mensaje)
    );
  });

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
