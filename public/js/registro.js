document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registroForm");
  const nombre = document.getElementById("nombre");
  const correo = document.getElementById("correo");
  const contrasena = document.getElementById("contrasena");

  const errorNombre = document.getElementById("error-nombre");
  const errorCorreo = document.getElementById("error-correo");
  const errorContraseña = document.getElementById("error-contrasena");

  function validarNombre(campo, errorSpan) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "El nombre es obligatorio.";
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
      /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "La contrasena es obligatoria.";
      return false;
    } else if (!contraseñaValida.test(campo.value.trim())) {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "La contrasena no es valida";
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
    validarNombre(nombre, errorNombre);
  });

  correo.addEventListener("blur", function () {
    validarCorreo(correo, errorCorreo);
  });

  contrasena.addEventListener("blur", function () {
    validarContraseña(contrasena, errorContraseña);
  });

  // Validar dinámicamente al escribir en el campo de correo
  correo.addEventListener("input", function () {
    validarNombre(nombre, errorNombre);
  });

  // Validar dinámicamente al escribir en el campo de contrasena
  contrasena.addEventListener("input", function () {
    validarNombre(nombre, errorNombre);
    validarCorreo(correo, errorCorreo);
  });

  // Validar campos al pulsar en submit
  form.addEventListener("submit", function (e) {
    const nombreValido = validarNombre(nombre, errorNombre);
    const correoValido = validarCorreo(correo, errorCorreo);
    const contraseñaValida = validarContraseña(contrasena, errorContraseña);

    if (!nombreValido || !correoValido || !contraseñaValida) {
      e.preventDefault();
    }
  });
});
