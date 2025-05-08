// Manejar el envío del formulario
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#form-filtros");

  form.addEventListener("submit", (e) => {
    e.preventDefault(); // evitar el envío por POST

    const ciudad = document.querySelector("#input-ciudad").value.trim();

    const tipoMascota = Array.from(
      document.querySelectorAll('input[name="tipo_mascota[]"]:checked')
    )
      .map((cb) => cb.value)
      .join(",");

    const servicio = document.querySelector("select[name='servicio']").value;

    const tamanoPerro = Array.from(
      document.querySelectorAll('input[name="tamano_perro[]"]:checked')
    ).map((val) => ({ tipo: "perro", tamano: val.value }));

    const tamanoGato = Array.from(
      document.querySelectorAll('input[name="tamano_gato[]"]:checked')
    ).map((val) => ({ tipo: "gato", tamano: val.value }));

    const tamanos = [...tamanoPerro, ...tamanoGato];
    const tamano = encodeURIComponent(JSON.stringify(tamanos));

    // Redirigir con parámetros GET a la vista del mapa
    const url = `${URL_BASE}/buscador?ciudad=${encodeURIComponent(
      ciudad
    )}&tipo_mascota=${encodeURIComponent(
      tipoMascota
    )}&servicio=${encodeURIComponent(servicio)}&tamano=${tamano}`;

    window.location.href = url;
  });

  const opcionesServicio = {
    todos: [
      "Alojamiento",
      "Paseos",
      "Guardería de día",
      "Visitas a domicilio",
      "Cuidado a domicilio",
      "Taxi",
    ],
    gato: ["Alojamiento", "Visitas a domicilio", "Cuidado a domicilio", "Taxi"],
    perro: [
      "Alojamiento",
      "Paseos",
      "Guardería de día",
      "Visitas a domicilio",
      "Cuidado a domicilio",
      "Taxi",
    ],
  };

  const selectServicio = document.querySelector("select[name='servicio']");

  function actualizarOpcionesServicio() {
    const perro = chkPerro.checked;
    const gato = chkGato.checked;

    let serviciosPermitidos = [];

    if (perro && gato) {
      serviciosPermitidos = opcionesServicio.todos;
    } else if (gato) {
      serviciosPermitidos = opcionesServicio.gato;
    } else if (perro) {
      serviciosPermitidos = opcionesServicio.perro;
    } else {
      serviciosPermitidos = opcionesServicio.todos;
    }

    // Guardamos el valor actual seleccionado si sigue siendo válido
    const valorSeleccionado = selectServicio.value;

    // Limpiamos y reconstruimos el select
    selectServicio.innerHTML =
      '<option selected disabled value="">Servicio</option>';

    serviciosPermitidos.forEach((serv) => {
      const option = document.createElement("option");
      option.value = serv;
      option.textContent = serv;
      selectServicio.appendChild(option);
    });

    // Restauramos el valor si aún está disponible
    if (serviciosPermitidos.includes(valorSeleccionado)) {
      selectServicio.value = valorSeleccionado;
    }
  }

  const chkPerro = document.getElementById("mascota-perro");
  const chkGato = document.getElementById("mascota-gato");
  const bloquePerro = document.getElementById("bloque-tamano-perro");
  const bloqueGato = document.getElementById("bloque-tamano-gato");

  function actualizarFormulario() {
    bloquePerro.classList.toggle("d-none", !chkPerro.checked);
    bloqueGato.classList.toggle("d-none", !chkGato.checked);
    actualizarOpcionesServicio();
  }

  chkPerro.addEventListener("change", actualizarFormulario);
  chkGato.addEventListener("change", actualizarFormulario);

  actualizarFormulario();
});
