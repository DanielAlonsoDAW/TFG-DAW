// Array para almacenar las imágenes miniatura
let imagenes = [];
// Índice de la imagen actualmente mostrada en el visor
let indexActual = 0;

/**
 * Abre el visor de imágenes y muestra la imagen seleccionada.
 * @param {number} idx - Índice de la imagen a mostrar.
 */
function abrirVisor(idx) {
  indexActual = idx;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
  document.getElementById("visorImagen").style.display = "flex";

  // Mostrar u ocultar los botones de navegación según la cantidad de imágenes
  const tieneVarias = imagenes.length > 1;
  document.querySelector(".visor-prev").style.display = tieneVarias
    ? "block"
    : "none";
  document.querySelector(".visor-next").style.display = tieneVarias
    ? "block"
    : "none";
}

/**
 * Cierra el visor de imágenes.
 */
function cerrarVisor() {
  document.getElementById("visorImagen").style.display = "none";
}

/**
 * Muestra la imagen anterior en el visor.
 */
function imagenAnterior() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual - 1 + imagenes.length) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

/**
 * Muestra la imagen siguiente en el visor.
 */
function imagenSiguiente() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual + 1) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Obtiene todas las imágenes miniatura
  imagenes = Array.from(document.querySelectorAll(".imagen-miniatura"));
  if (imagenes.length === 0) return;

  // Añade evento click a cada miniatura para abrir el visor
  imagenes.forEach((img, i) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => abrirVisor(i));
  });

  // Permite cerrar el visor con la tecla Escape
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarVisor();
  });

  // Elementos del formulario relacionados con tipo, raza y tamaño
  const tipoRadios = document.querySelectorAll('input[name="tipo"]');
  const razaSelect = document.querySelector("#raza");
  const tamanoDiv = document.getElementById("tamano");
  const tamanoHidden = document.getElementById("tamano-hidden");

  /**
   * Limpia las opciones del select de raza.
   */
  function limpiarOpciones() {
    razaSelect.innerHTML =
      '<option value="" disabled selected>Elige una raza</option>';
  }

  /**
   * Carga las razas según el tipo seleccionado (perro/gato).
   * @param {string} tipo - Tipo de mascota seleccionado.
   */
  function cargarRazas(tipo) {
    // razasPerro y razasGato deben estar definidos en el contexto global
    const data =
      tipo === "perro" ? razasPerro : tipo === "gato" ? razasGato : {};
    limpiarOpciones();

    // Añade cada raza como opción en el select
    for (const [raza, tamano] of Object.entries(data)) {
      const option = document.createElement("option");
      option.value = raza;
      option.textContent = raza;
      option.dataset.tamano = tamano;
      razaSelect.appendChild(option);
    }
  }

  // Evento para cambiar las razas cuando se selecciona un tipo
  tipoRadios.forEach((radio) => {
    radio.addEventListener("change", (e) => {
      cargarRazas(e.target.value);
      tamanoInput.value = "";
    });
  });

  // Evento para mostrar el tamaño según la raza seleccionada
  razaSelect.addEventListener("change", (e) => {
    const option = e.target.selectedOptions[0];
    const tamanoRaw = option?.dataset?.tamano || "";
    const tipo = Array.from(tipoRadios).find((r) => r.checked)?.value;

    let tamanoLabel = tamanoRaw;

    // Ajusta la etiqueta del tamaño según el tipo de mascota
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

    // Muestra el icono y el tamaño en el badge
    const icono =
      tipo === "perro"
        ? '<i class="fa-solid fa-dog me-1"></i>'
        : '<i class="fa-solid fa-cat me-1"></i>';
    document.getElementById("tamano-badge").innerHTML = icono + tamanoLabel;

    // Guarda el valor real del tamaño en un campo oculto
    tamanoHidden.value = tamanoRaw;
  });

  // Si hay un tipo seleccionado al cargar la página, carga las razas y el tamaño previo
  const tipoSeleccionado = Array.from(tipoRadios).find((r) => r.checked)?.value;
  if (tipoSeleccionado) {
    cargarRazas(tipoSeleccionado);

    if (razaSelect.dataset.valorPrevio) {
      razaSelect.value = razaSelect.dataset.valorPrevio;

      const opt = razaSelect.selectedOptions[0];
      const tamanoRaw = opt?.dataset?.tamano || "";
      let tamanoLabel = tamanoRaw;

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

      const icono =
        tipoSeleccionado === "perro"
          ? '<i class="fa-solid fa-dog me-1"></i>'
          : '<i class="fa-solid fa-cat me-1"></i>';
      document.getElementById("tamano-badge").innerHTML = icono + tamanoLabel;

      tamanoHidden.value = tamanoRaw;
    }
  }

  // Validación del formulario al enviar
  const form = document.getElementById("formEditarMascota");

  form.addEventListener("submit", function (e) {
    // Limpiar mensajes de error anteriores
    document
      .querySelectorAll(".text-danger")
      .forEach((span) => (span.textContent = ""));

    let hayErrores = false;

    // Elementos del formulario a validar
    const nombre = document.getElementById("nombre");
    const tipoPerro = document.getElementById("btn-perro");
    const tipoGato = document.getElementById("btn-gato");
    const raza = document.getElementById("raza");
    const edad = document.getElementById("edad");
    const tamano = document.getElementById("tamano");

    /**
     * Marca un campo como erróneo y muestra el mensaje correspondiente.
     * @param {string} inputId - ID del campo.
     * @param {string} mensaje - Mensaje de error.
     */
    const mostrarError = (inputId, mensaje) => {
      const campo = document.getElementById(inputId);
      const span = campo.closest(".mb-3").querySelector(".text-danger");
      if (span) span.textContent = mensaje;
      campo.classList.add("is-invalid");
      hayErrores = true;
    };

    // Quita la clase de error de todos los campos
    form
      .querySelectorAll(".form-control, .form-select")
      .forEach((el) => el.classList.remove("is-invalid"));

    // Validaciones de cada campo
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
    if (hayErrores) {
      e.preventDefault(); // Evita el envío si hay errores
    }
  });
});
