document.addEventListener("DOMContentLoaded", () => {
  // Inicializa el mapa y el grupo de marcadores
  const map = L.map("map").setView([40.416775, -3.70379], 6);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);
  const markerGroup = L.layerGroup().addTo(map);

  // Crea una tarjeta visual para mostrar la información de un cuidador
  function crearTarjeta(c) {
    const card = document.createElement("div");
    card.className = "card custom-card mb-3";
    card.style.cursor = "pointer";
    card.onclick = () =>
      (window.location.href = `${RUTA_URL}/cuidadores/perfil/${c.id}`);
    card.innerHTML = `
      <div class="card-body">
        <div class="d-flex align-items-start">
          <img src="${RUTA_URL}/${c.imagen}"
               alt="${c.nombre}"
               class="rounded-circle me-3"
               style="width:50px;height:50px;object-fit:cover;">
          <div class="flex-grow-1">
            <h6 class="card-title">${c.nombre}</h6>
            <p class="mb-1">${c.ciudad}</p>
            ${
              c.mejor_resena
                ? `<p class="testimonial">“${c.mejor_resena}”</p>`
                : ""
            }
            <p class="rating">
              Valoración: ${parseFloat(c.media_valoracion).toFixed(1)} ★
              ${
                c.total_resenas
                  ? `<span class="text-muted">(${c.total_resenas}) reseñas</span>`
                  : ""
              }
            </p>
            ${
              c.precio_servicio
                ? `<div><span class="badge bg-light text-dark">${c.precio_servicio}</span></div>`
                : ""
            }
          </div>
        </div>
      </div>`;
    return card;
  }

  // Muestra los cuidadores en el sidebar y en el mapa
  function mostrarCuidadores(cuidadores) {
    const sidebar = document.querySelector(".sidebar-custom");
    sidebar.innerHTML =
      '<h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>';
    const frag = document.createDocumentFragment();

    // Limpia los marcadores anteriores del mapa
    markerGroup.clearLayers();
    const bounds = L.latLngBounds();

    cuidadores.forEach((c) => {
      const lat = parseFloat(c.lat),
        lng = parseFloat(c.lng);
      if (!isNaN(lat) && !isNaN(lng)) {
        const m = L.marker([lat, lng]).bindPopup(
          `<strong>${c.nombre}</strong><br>${c.ciudad}`
        );
        markerGroup.addLayer(m);
        bounds.extend([lat, lng]);
      }
      frag.appendChild(crearTarjeta(c));
    });

    sidebar.appendChild(frag);

    // Ajusta el mapa para mostrar todos los marcadores
    if (bounds.isValid()) {
      map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
    } else {
      map.setView([40.416775, -3.70379], 6);
    }
  }

  // Envía el formulario por POST y procesa la respuesta
  async function buscarConFiltros(form) {
    // Serializa los tamaños seleccionados en formato JSON
    const tamPerro = Array.from(
      form.querySelectorAll('input[name="tamano_perro[]"]:checked')
    ).map((cb) => ({ tipo: "perro", tamano: cb.value }));
    const tamGato = Array.from(
      form.querySelectorAll('input[name="tamano_gato[]"]:checked')
    ).map((cb) => ({ tipo: "gato", tamano: cb.value }));
    form.querySelector("#input-tamano-json").value = JSON.stringify([
      ...tamPerro,
      ...tamGato,
    ]);

    // Prepara el FormData y realiza la petición fetch
    const fd = new FormData(form);
    try {
      const res = await fetch(form.action, {
        method: "POST",
        body: fd,
      });
      if (!res.ok) throw new Error(res.statusText);
      const data = await res.json();
      mostrarCuidadores(data);
    } catch (err) {
      console.error("Error al buscar cuidadores:", err);
    }
  }

  // Añade el listener al formulario para manejar el submit
  const form = document.querySelector("#form-filtros");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    buscarConFiltros(form);
  });

  // Realiza la búsqueda inicial al cargar la página
  buscarConFiltros(form);

  // Opciones de servicios disponibles según el tipo de mascota seleccionado
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
  const chkPerro = document.getElementById("mascota-perro");
  const chkGato = document.getElementById("mascota-gato");
  const bloquePerro = document.getElementById("bloque-tamano-perro");
  const bloqueGato = document.getElementById("bloque-tamano-gato");

  // Actualiza las opciones del select de servicios según la mascota seleccionada
  function actualizarOpcionesServicio() {
    const perro = chkPerro.checked;
    const gato = chkGato.checked;
    let lista = opcionesServicio.todos;
    if (perro && !gato) lista = opcionesServicio.perro;
    if (gato && !perro) lista = opcionesServicio.gato;

    // Reconstruye el select de servicios
    const seleccionado = selectServicio.value;
    selectServicio.innerHTML =
      '<option disabled selected value="">Servicio</option>';
    lista.forEach((serv) => {
      const opt = document.createElement("option");
      opt.value = serv;
      opt.textContent = serv;
      selectServicio.appendChild(opt);
    });
    if (lista.includes(seleccionado)) selectServicio.value = seleccionado;
  }

  // Muestra u oculta los bloques de tamaños según la mascota seleccionada
  function actualizarFormulario() {
    bloquePerro.classList.toggle("d-none", !chkPerro.checked);
    bloqueGato.classList.toggle("d-none", !chkGato.checked);
    actualizarOpcionesServicio();
  }

  chkPerro.addEventListener("change", actualizarFormulario);
  chkGato.addEventListener("change", actualizarFormulario);
  actualizarFormulario();
});
