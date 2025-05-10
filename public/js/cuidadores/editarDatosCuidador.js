document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");

  const campos = {
    telefono: {
      input: document.getElementById("telefono"),
      error: document.getElementById("error-telefono"),
      validar: (valor) => /^(6|7|9)\d{8}$/.test(valor.trim()),
      mensaje: "El teléfono debe comenzar por 6, 7 o 9 y tener 9 dígitos.",
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

  Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
    input.addEventListener("input", () =>
      validarCampo(input, error, validar, mensaje)
    );
    input.addEventListener("blur", () =>
      validarCampo(input, error, validar, mensaje)
    );
  });

  form.addEventListener("submit", (e) => {
    let valido = true;
    Object.values(campos).forEach(({ input, error, validar, mensaje }) => {
      if (!validarCampo(input, error, validar, mensaje)) {
        valido = false;
      }
    });

    if (!valido) e.preventDefault();
  });
});