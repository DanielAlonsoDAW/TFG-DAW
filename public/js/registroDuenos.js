document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registroForm");
  const nombre = document.getElementById("nombre");
  const correo = document.getElementById("correo");
  const contraseña = document.getElementById("contraseña");

  const errorNombre = document.getElementById("error-nombre");
  const errorCorreo = document.getElementById("error-correo");
  const errorContraseña = document.getElementById("error-contraseña");

  function validarNombre(campo, errorSpan, mensaje) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = mensaje;
      return false;
    } else {
      campo.classList.remove("is-invalid");
      errorSpan.textContent = "";
      return true;
    }
  }

  function validarCorreo(campo, errorSpan) {
    const correoValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "El correo es obligatorio.";
      return false;
    } else if (!correoValido.test(campo.value.trim())) {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "El correo no es válido. Ej: ejemplo@email.es";
      return false;
    } else {
      campo.classList.remove("is-invalid");
      errorSpan.textContent = "";
      return true;
    }
  }

  function validarContraseña(campo, errorSpan) {
    const contraseñaValida =
      /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/; // Expresión regular para validar la contraseña
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "La contraseña es obligatoria.";
      return false;
    } else if (!contraseñaValida.test(campo.value.trim())) {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "La contraseña no es valida";
      return false;
    } else {
      campo.classList.remove("is-invalid");
      errorSpan.textContent = "";
      return true;
    }
  }

  // Activar tooltips de Bootstrap
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // Validar al cambiar el foco (blur)
  nombre.addEventListener("blur", function () {
    validarNombre(nombre, errorNombre, "El nombre es obligatorio.");
  });

  correo.addEventListener("blur", function () {
    validarCorreo(correo, errorCorreo);
  });

  contraseña.addEventListener("blur", function () {
    validarContraseña(contraseña, errorContraseña);
  });

  // Validar dinámicamente al escribir en el campo de correo
  correo.addEventListener("input", function () {
    validarNombre(nombre, errorNombre, "El nombre es obligatorio.");
  });

  contraseña.addEventListener("input", function () {
    const nombreValido = validarNombre(
      nombre,
      errorNombre,
      "El nombre es obligatorio."
    );
    const correoValido = validarCorreo(correo, errorCorreo);
  });

  form.addEventListener("submit", function (e) {
    const nombreValido = validarNombre(
      nombre,
      errorNombre,
      "El nombre es obligatorio."
    );
    const correoValido = validarCorreo(correo, errorCorreo);
    const contraseñaValida = validarContraseña(contraseña, errorContraseña);

    if (!nombreValido || !correoValido || !contraseñaValida) {
      e.preventDefault();
    }
  });
});
