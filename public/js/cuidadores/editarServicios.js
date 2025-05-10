document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("form");
  const maxInput = document.getElementById("max_mascotas_dia");
  const errorMax = document.getElementById("error-max_mascotas_dia");

  const perroCheck = document.getElementById("acepta_perro");
  const gatoCheck = document.getElementById("acepta_gato");

  const grupoPerro = document.getElementById("grupo-perro");
  const grupoGato = document.getElementById("grupo-gato");

  const tamanosPerro = document.querySelectorAll(
    "input[name='tamanos_perro[]']"
  );
  const tamanosGato = document.querySelectorAll("input[name='tamanos_gato[]']");

  function validarNumero(valor) {
    return /^\d+$/.test(valor.trim()) && parseInt(valor) > 0;
  }

  function mostrarErrores(input, errorSpan, mensaje) {
    input.classList.add("is-invalid");
    errorSpan.textContent = mensaje;
  }

  function limpiarErrores(input, errorSpan) {
    input.classList.remove("is-invalid");
    errorSpan.textContent = "";
  }

  function toggleGrupo(grupoId, estado) {
    grupoId.style.display = estado ? "block" : "none";
  }

  // Mostrar u ocultar los grupos al cambiar
  perroCheck.addEventListener("change", () => {
    toggleGrupo(grupoPerro, perroCheck.checked);
  });

  gatoCheck.addEventListener("change", () => {
    toggleGrupo(grupoGato, gatoCheck.checked);
  });

  // Mostrar u ocultar en carga
  toggleGrupo(grupoPerro, perroCheck.checked);
  toggleGrupo(grupoGato, gatoCheck.checked);

  // Validar al enviar
  form.addEventListener("submit", (e) => {
    let esValido = true;

    // Validar max mascotas
    if (!validarNumero(maxInput.value)) {
      mostrarErrores(
        maxInput,
        errorMax,
        "Debes ingresar un número válido mayor que 0."
      );
      esValido = false;
    } else {
      limpiarErrores(maxInput, errorMax);
    }

    // Validar tipo mascota
    if (!perroCheck.checked && !gatoCheck.checked) {
      alert("Debes seleccionar al menos un tipo de animal (perro o gato).");
      esValido = false;
    }

    // Validar al menos un tamaño si se ha marcado el tipo
    if (perroCheck.checked) {
      const algunoPerro = Array.from(tamanosPerro).some((cb) => cb.checked);
      if (!algunoPerro) {
        alert("Selecciona al menos un tamaño de perro.");
        esValido = false;
      }
    }

    if (gatoCheck.checked) {
      const algunoGato = Array.from(tamanosGato).some((cb) => cb.checked);
      if (!algunoGato) {
        alert("Selecciona al menos un tamaño de gato.");
        esValido = false;
      }
    }

    if (!esValido) e.preventDefault();
  });
});
