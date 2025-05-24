// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona los radios de tipo (perro/gato), el select de raza y el input de tamaño
  const tipoRadios = document.querySelectorAll('input[name="tipo"]');
  const razaSelect = document.querySelector("#raza");
  const tamanoInput = document.querySelector("#tamano");

  // Limpia las opciones del select de raza
  function limpiarOpciones() {
    razaSelect.innerHTML =
      '<option value="" disabled selected>Elige una raza</option>';
  }

  // Carga las razas según el tipo seleccionado (perro/gato)
  function cargarRazas(tipo) {
    // Selecciona el objeto de razas correspondiente
    const data =
      tipo === "perro" ? razasPerro : tipo === "gato" ? razasGato : {};
    limpiarOpciones();

    // Recorre las razas y las añade como opciones al select
    for (const [raza, tamano] of Object.entries(data)) {
      const option = document.createElement("option");
      option.value = raza;
      option.textContent = raza;
      option.dataset.tamano = tamano; // Guarda el tamaño en un atributo data
      razaSelect.appendChild(option);
    }
  }

  // Evento: cuando cambia el tipo (perro/gato), carga las razas correspondientes
  tipoRadios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
      cargarRazas(e.target.value);
      tamanoInput.value = ""; // Limpia el input de tamaño
    });
  });

  // Evento: cuando cambia la raza, muestra el tamaño correspondiente
  razaSelect.addEventListener("change", (e) => {
    const option = e.target.selectedOptions[0];
    const tamanoRaw = option?.dataset?.tamano || "";
    const tipo = Array.from(tipoRadios).find((r) => r.checked)?.value;

    let tamanoLabel = tamanoRaw;

    // Ajusta la etiqueta del tamaño según el tipo y valor
    if (tipo === "perro") {
      switch (tamanoRaw.toLowerCase()) {
        case "pequeño":
          tamanoLabel = "Pequeño < 35 cm";
          break;
        case "mediano":
          tamanoLabel = "Mediano 35-49 cm";
          break;
        case "grande":
          tamanoLabel = "Grande ≥ 50 cm";
          break;
      }
    } else if (tipo === "gato") {
      switch (tamanoRaw.toLowerCase()) {
        case "pequeño":
          tamanoLabel = "Pequeño < 3 kg";
          break;
        case "mediano":
          tamanoLabel = "Mediano 3-5 kg";
          break;
        case "grande":
          tamanoLabel = "Grande ≥ 5 kg";
          break;
      }
    }

    // Selecciona el icono según el tipo
    const icono =
      tipo === "perro"
        ? '<i class="fa-solid fa-dog me-1"></i>'
        : '<i class="fa-solid fa-cat me-1"></i>';

    // Muestra el tamaño en el badge y lo guarda en el input oculto
    document.getElementById("tamano-badge").innerHTML = icono + tamanoLabel;
    document.getElementById("tamano-hidden").value = tamanoRaw;
  });

  // Si ya había selección previa (por validación fallida), la restaura
  const tipoSeleccionado = Array.from(tipoRadios).find((r) => r.checked)?.value;
  if (tipoSeleccionado) {
    cargarRazas(tipoSeleccionado);

    if (razaSelect.dataset.valorPrevio) {
      razaSelect.value = razaSelect.dataset.valorPrevio;

      const opt = razaSelect.selectedOptions[0];
      const tamanoRaw = opt?.dataset?.tamano || "";
      let tamanoLabel = tamanoRaw;

      // Ajusta la etiqueta del tamaño según el tipo y valor
      if (tipoSeleccionado === "perro") {
        switch (tamanoRaw.toLowerCase()) {
          case "pequeño":
            tamanoLabel = "Pequeño < 35 cm";
            break;
          case "mediano":
            tamanoLabel = "Mediano 35-49 cm";
            break;
          case "grande":
            tamanoLabel = "Grande ≥ 50 cm";
            break;
        }
      } else if (tipoSeleccionado === "gato") {
        switch (tamanoRaw.toLowerCase()) {
          case "pequeño":
            tamanoLabel = "Pequeño < 3 kg";
            break;
          case "mediano":
            tamanoLabel = "Mediano 3-5 kg";
            break;
          case "grande":
            tamanoLabel = "Grande ≥ 5 kg";
            break;
        }
      }

      // Selecciona el icono según el tipo
      const icono =
        tipoSeleccionado === "perro"
          ? '<i class="fa-solid fa-dog me-1"></i>'
          : '<i class="fa-solid fa-cat me-1"></i>';

      tamanoBadge.innerHTML = icono + tamanoLabel;
      tamanoHidden.value = tamanoRaw;
    }
  }

  // Selecciona el formulario de agregar mascota
  const form = document.getElementById("formAgregarMascota");

  // Evento: al enviar el formulario, valida los campos
  form.addEventListener("submit", function (e) {
    // Limpia mensajes de error anteriores
    document
      .querySelectorAll(".text-danger")
      .forEach((span) => (span.textContent = ""));

    let hayErrores = false;

    // Selecciona los campos del formulario
    const nombre = document.getElementById("nombre");
    const tipoPerro = document.getElementById("btn-perro");
    const tipoGato = document.getElementById("btn-gato");
    const raza = document.getElementById("raza");
    const edad = document.getElementById("edad");
    const tamano = document.getElementById("tamano");
    const imagenes = document.getElementById("imagenes");

    // Función para mostrar errores en los campos
    const mostrarError = (inputId, mensaje) => {
      const campo = document.getElementById(inputId);
      const span = campo.closest(".mb-3").querySelector(".text-danger");
      if (span) span.textContent = mensaje;
      campo.classList.add("is-invalid");
      hayErrores = true;
    };

    // Limpia las clases de error de los campos
    form
      .querySelectorAll(".form-control, .form-select")
      .forEach((el) => el.classList.remove("is-invalid"));

    // Validaciones de los campos
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
    if (!tamanoHidden.value.trim())
      mostrarError("tamano-hidden", "El tamaño es obligatorio.");
    if (!imagenes.files.length)
      mostrarError("imagenes", "Debes subir al menos una imagen.");
    if (hayErrores) {
      e.preventDefault(); // Si hay errores, evita el envío del formulario
    }
  });
});
