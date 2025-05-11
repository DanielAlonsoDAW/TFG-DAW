document.addEventListener("DOMContentLoaded", () => {
  const tipoSelect = document.querySelector('select[name="tipo"]');
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

  tipoSelect.addEventListener("change", (e) => {
    cargarRazas(e.target.value);
    tamanoInput.value = "";
  });

  razaSelect.addEventListener("change", (e) => {
    const tamano = e.target.selectedOptions[0]?.dataset?.tamano || "";
    tamanoInput.value = tamano;
  });

  // Rellenar si ya se había seleccionado algo (para validación fallida)
  if (tipoSelect.value) {
    cargarRazas(tipoSelect.value);
    if (razaSelect.dataset.valorPrevio) {
      razaSelect.value = razaSelect.dataset.valorPrevio;
      const opt = razaSelect.selectedOptions[0];
      tamanoInput.value = opt?.dataset?.tamano || "";
    }
  }
});
