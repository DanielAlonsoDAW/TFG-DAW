document.addEventListener("DOMContentLoaded", () => {
  const tipoRadios = document.querySelectorAll('input[name="tipo"]');
  const razaSelect = document.querySelector("#raza");
  const tamanoInput = document.querySelector("#tamano");

  function limpiarOpciones() {
    razaSelect.innerHTML =
      '<option value="" disabled selected>Elige una raza</option>';
  }

  function cargarRazas(tipo) {
    const data =
      tipo === "perro" ? razasPerro : tipo === "gato" ? razasGato : {};
    limpiarOpciones();

    for (const [raza, tamano] of Object.entries(data)) {
      const option = document.createElement("option");
      option.value = raza;
      option.textContent = raza;
      option.dataset.tamano = tamano;
      razaSelect.appendChild(option);
    }
  }

  tipoRadios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
      cargarRazas(e.target.value);
      tamanoInput.value = "";
    });
  });

  razaSelect.addEventListener("change", (e) => {
    const tamano = e.target.selectedOptions[0]?.dataset?.tamano || "";
    tamanoInput.value = tamano;
  });

  // Rellenar si ya se había seleccionado algo (para validación fallida)
  if (tipoRadios.value) {
    cargarRazas(tipoRadios.value);
    if (razaSelect.dataset.valorPrevio) {
      razaSelect.value = razaSelect.dataset.valorPrevio;
      const opt = razaSelect.selectedOptions[0];
      tamanoInput.value = opt?.dataset?.tamano || "";
    }
  }

  const form = document.getElementById("formAgregarMascota");

  form.addEventListener("submit", function (e) {
    // Limpiar mensajes anteriores
    document
      .querySelectorAll(".text-danger")
      .forEach((span) => (span.textContent = ""));

    let hayErrores = false;

    const nombre = document.getElementById("nombre");
    const tipoPerro = document.getElementById("btn-perro");
    const tipoGato = document.getElementById("btn-gato");
    const raza = document.getElementById("raza");
    const edad = document.getElementById("edad");
    const tamano = document.getElementById("tamano");
    const imagenes = document.getElementById("imagenes");

    // Util para marcar errores
    const mostrarError = (inputId, mensaje) => {
      const campo = document.getElementById(inputId);
      const span = campo.closest(".mb-3").querySelector(".text-danger");
      if (span) span.textContent = mensaje;
      campo.classList.add("is-invalid");
      hayErrores = true;
    };

    // Limpiar clases
    form
      .querySelectorAll(".form-control, .form-select")
      .forEach((el) => el.classList.remove("is-invalid"));

    if (!nombre.value.trim())
      mostrarError("nombre", "El nombre es obligatorio.");
    if (!tipoPerro.checked && !tipoGato.checked) {
      const spanTipo = tipoPerro.closest(".mb-3").querySelector(".text-danger");
      if (spanTipo) spanTipo.textContent = "Selecciona un tipo.";
      hayErrores = true;
    }
    if (!raza.value) mostrarError("raza", "La raza es obligatoria.");
    if (edad.value === "" || isNaN(edad.value) || parseInt(edad.value) < 0)
      mostrarError("edad", "Edad no válida.");
    if (!tamano.value.trim())
      mostrarError("tamano", "El tamaño es obligatorio.");
    if (!imagenes.files.length)
      mostrarError("imagenes", "Debes subir al menos una imagen.");

    if (hayErrores) {
      e.preventDefault();
    }
  });
});
