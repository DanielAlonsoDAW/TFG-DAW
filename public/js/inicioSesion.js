// Espera a que el DOM esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
  // Obtiene referencias a los elementos del formulario y campos de entrada
  const form = document.getElementById("loginForm");
  const correo = document.getElementById("correo");
  const contrasena = document.getElementById("contrasena");

  // Obtiene referencias a los elementos donde se mostrarán los mensajes de error
  const errorCorreo = document.getElementById("error-correo");
  const errorContraseña = document.getElementById("error-contrasena");

  // Función para validar el campo de correo electrónico
  function validarCorreo(campo, errorSpan) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid"); // Marca el campo como inválido
      errorSpan.textContent = "El correo es obligatorio."; // Muestra mensaje de error
      return false;
    } else {
      campo.classList.remove("is-invalid"); // Elimina la marca de inválido
      errorSpan.textContent = ""; // Limpia el mensaje de error
      return true;
    }
  }

  // Función para validar el campo de contraseña
  function validarContraseña(campo, errorSpan) {
    if (campo.value.trim() === "") {
      campo.classList.add("is-invalid"); // Marca el campo como inválido
      errorSpan.textContent = "La contrasena es obligatoria."; // Muestra mensaje de error
      return false;
    } else {
      campo.classList.remove("is-invalid"); // Elimina la marca de inválido
      errorSpan.textContent = ""; // Limpia el mensaje de error
      return true;
    }
  }

  // Valida dinámicamente el campo de correo al escribir en el campo de contraseña
  contrasena.addEventListener("input", function () {
    validarCorreo(correo, errorCorreo);
  });

  // Valida los campos al enviar el formulario
  form.addEventListener("submit", function (e) {
    const correoValido = validarCorreo(correo, errorCorreo);
    const contraseñaValida = validarContraseña(contrasena, errorContraseña);

    // Si algún campo no es válido, evita que se envíe el formulario
    if (!correoValido || !contraseñaValida) {
      e.preventDefault();
    }
  });
});
