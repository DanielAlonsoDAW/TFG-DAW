// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  // Obtiene referencias a los elementos del formulario y sus campos
  const form = document.getElementById("registroForm");
  const nombre = document.getElementById("nombre");
  const correo = document.getElementById("correo");
  const contrasena = document.getElementById("contrasena");

  // Obtiene referencias a los spans de error
  const errorNombre = document.getElementById("error-nombre");
  const errorCorreo = document.getElementById("error-correo");
  const errorContraseña = document.getElementById("error-contrasena");

  // Función para validar el campo nombre
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

  // Función para validar el campo correo
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

  // Función para validar el campo contraseña
  function validarContraseña(campo, errorSpan) {
    // Debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial
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

  // Activa los tooltips de Bootstrap para los elementos que lo requieran
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // Valida el campo nombre al perder el foco
  nombre.addEventListener("blur", function () {
    validarNombre(nombre, errorNombre);
  });

  // Valida el campo correo al perder el foco
  correo.addEventListener("blur", function () {
    validarCorreo(correo, errorCorreo);
  });

  // Valida el campo contraseña al perder el foco
  contrasena.addEventListener("blur", function () {
    validarContraseña(contrasena, errorContraseña);
  });

  // Valida dinámicamente el campo nombre al escribir en el campo correo
  correo.addEventListener("input", function () {
    validarNombre(nombre, errorNombre);
  });

  // Valida dinámicamente los campos nombre y correo al escribir en el campo contraseña
  contrasena.addEventListener("input", function () {
    validarNombre(nombre, errorNombre);
    validarCorreo(correo, errorCorreo);
  });

  // Valida todos los campos al enviar el formulario
  form.addEventListener("submit", function (e) {
    const nombreValido = validarNombre(nombre, errorNombre);
    const correoValido = validarCorreo(correo, errorCorreo);
    const contraseñaValida = validarContraseña(contrasena, errorContraseña);

    // Si algún campo no es válido, evita el envío del formulario
    if (!nombreValido || !correoValido || !contraseñaValida) {
      e.preventDefault();
    }
  });
});
