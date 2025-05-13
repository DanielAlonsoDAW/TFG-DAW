let imagenes = [];
let indexActual = 0;

function abrirVisor(idx) {
  indexActual = idx;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
  document.getElementById("visorImagen").style.display = "flex";

  const tieneVarias = imagenes.length > 1;
  document.querySelector(".visor-prev").style.display = tieneVarias
    ? "block"
    : "none";
  document.querySelector(".visor-next").style.display = tieneVarias
    ? "block"
    : "none";
}

function cerrarVisor() {
  document.getElementById("visorImagen").style.display = "none";
}

function imagenAnterior() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual - 1 + imagenes.length) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

function imagenSiguiente() {
  if (imagenes.length <= 1) return;
  indexActual = (indexActual + 1) % imagenes.length;
  document.getElementById("imagenAmpliada").src = imagenes[indexActual].src;
}

document.addEventListener("DOMContentLoaded", () => {
  imagenes = Array.from(document.querySelectorAll(".imagen-miniatura"));
  if (imagenes.length === 0) return;

  imagenes.forEach((img, i) => {
    img.style.cursor = "pointer";
    img.addEventListener("click", () => abrirVisor(i));
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") cerrarVisor();
  });

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

  const tipoSeleccionado = Array.from(tipoRadios).find((r) => r.checked)?.value;
  if (tipoSeleccionado) {
    cargarRazas(tipoSeleccionado);

    if (razaSelect.dataset.valorPrevio) {
      razaSelect.value = razaSelect.dataset.valorPrevio;

      const opt = razaSelect.selectedOptions[0];
      tamanoInput.value = opt?.dataset?.tamano || "";
    }
  }

  const form = document.getElementById("formEditarMascota");

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

    if (hayErrores) {
      e.preventDefault();
    }
  });
});
