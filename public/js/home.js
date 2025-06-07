// Espera a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Obtiene referencias a los elementos del DOM
  const chkPerro = document.getElementById("mascota-perro");
  const chkGato = document.getElementById("mascota-gato");
  const bloquePerro = document.getElementById("bloque-tamano-perro");
  const bloqueGato = document.getElementById("bloque-tamano-gato");
  const selectServ = document.querySelector("select[name='servicio']");
  const form = document.querySelector("#form-filtros");
  const inputTamano = document.getElementById("input-tamano-json");

  // Opciones de servicios según el tipo de mascota seleccionada
  const opcionesServicio = {
    todos: [
      "Alojamiento",
      "Paseos",
      "Guardería de día",
      "Visitas a domicilio",
      "Cuidado a domicilio",
      "Taxi",
    ],
    perro: [
      "Alojamiento",
      "Paseos",
      "Guardería de día",
      "Visitas a domicilio",
      "Cuidado a domicilio",
      "Taxi",
    ],
    gato: ["Alojamiento", "Visitas a domicilio", "Cuidado a domicilio", "Taxi"],
  };

  // Actualiza las opciones del select de servicios según la mascota seleccionada
  function actualizarOpcionesServicio() {
    const perro = chkPerro.checked;
    const gato = chkGato.checked;
    let lista;
    if (perro && gato) lista = opcionesServicio.todos;
    else if (perro) lista = opcionesServicio.perro;
    else if (gato) lista = opcionesServicio.gato;
    else lista = opcionesServicio.todos;

    const actual = selectServ.value;
    // Reinicia las opciones del select
    selectServ.innerHTML =
      '<option disabled selected value="">Servicio</option>';
    // Agrega las opciones correspondientes
    lista.forEach((s) => {
      const o = document.createElement("option");
      o.value = s;
      o.textContent = s;
      selectServ.appendChild(o);
    });
    // Mantiene la opción seleccionada si sigue disponible
    if (lista.includes(actual)) selectServ.value = actual;
  }

  // Muestra u oculta los bloques de tamaño según la mascota seleccionada y actualiza servicios
  function actualizarFormulario() {
    bloquePerro.classList.toggle("d-none", !chkPerro.checked);
    bloqueGato.classList.toggle("d-none", !chkGato.checked);
    actualizarOpcionesServicio();
  }

  // Escucha cambios en los checkboxes de mascota
  chkPerro.addEventListener("change", actualizarFormulario);
  chkGato.addEventListener("change", actualizarFormulario);
  // Inicializa el formulario al cargar la página
  actualizarFormulario();

  // Al enviar el formulario, serializa los tamaños seleccionados en formato JSON
  form.addEventListener("submit", (e) => {
    const tamPerro = Array.from(
      form.querySelectorAll('input[name="tamano_perro[]"]:checked')
    ).map((cb) => ({ tipo: "perro", tamano: cb.value }));
    const tamGato = Array.from(
      form.querySelectorAll('input[name="tamano_gato[]"]:checked')
    ).map((cb) => ({ tipo: "gato", tamano: cb.value }));
    inputTamano.value = JSON.stringify([...tamPerro, ...tamGato]);
  });
});
