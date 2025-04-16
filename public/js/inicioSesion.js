document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const correo = document.getElementById("correo");
  const contrasena = document.getElementById("contrasena");

  const errorCorreo = document.getElementById("error-correo");
  const errorContraseña = document.getElementById("error-contrasena");

  function validarCorreo(campo, errorSpan) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "El correo es obligatorio.";
      return false;
    } else {
      campo.classList.remove("is-invalid");
      errorSpan.textContent = "";
      return true;
    }
  }

  function validarContraseña(campo, errorSpan) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid");
      errorSpan.textContent = "La contrasena es obligatoria.";
      return false;
    } else {
      campo.classList.remove("is-invalid");
      errorSpan.textContent = "";
      return true;
    }
  }

  // Validar dinámicamente al escribir en el campo de contrasena
  contrasena.addEventListener("input", function () {
    validarCorreo(correo, errorCorreo);
  });

  // Validar campos al pulsar en submit
  form.addEventListener("submit", function (e) {
    const correoValido = validarCorreo(correo, errorCorreo);
    const contraseñaValida = validarContraseña(contrasena, errorContraseña);

    if (!correoValido || !contraseñaValida) {
      e.preventDefault();
    }
  });
});
