// Inicializar el mapa centrado en España
const map = L.map("map").setView([40.416775, -3.70379], 6);

// Capa base OpenStreetMap
L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  attribution:
    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
}).addTo(map);

// Función para limpiar todos los marcadores
function limpiarMarcadores() {
  map.eachLayer((layer) => {
    if (layer instanceof L.Marker) {
      map.removeLayer(layer);
    }
  });
}

// Coordenadas de ciudades
const ciudadesCoords = {
  Madrid: [40.4168, -3.7038],
  Barcelona: [41.3851, 2.1734],
  Valencia: [39.4699, -0.3763],
  Málaga: [36.7213, -4.4214],
  Zaragoza: [41.6488, -0.8891],
  Toledo: [39.8628, -4.0273],
  Valladolid: [41.6523, -4.7245],
  "Santiago de Compostela": [42.8805, -8.5457],
  Mérida: [38.917, -6.3455],
  Oviedo: [43.3619, -5.8494],
  Pamplona: [42.8125, -1.6458],
  Bilbao: [43.263, -2.935],
  Santander: [43.4623, -3.8099],
  Logroño: [42.4667, -2.45],
  Palma: [39.5696, 2.6502],
  "Santa Cruz de Tenerife": [28.4636, -16.2518],
  Murcia: [37.9922, -1.1307],
};

// Función para mostrar cuidadores en mapa y sidebar
function mostrarCuidadores(cuidadores) {
  const sidebar = document.querySelector(".sidebar-custom");
  sidebar.innerHTML =
    '<h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>';

  cuidadores.forEach((c) => {
    const coords = ciudadesCoords[c.ciudad];
    if (!coords) return;

    // Añadir marcador al mapa
    L.marker(coords).addTo(map).bindPopup(`${c.nombre} - ${c.ciudad}`);

    // Crear tarjeta en el sidebar
    const card = document.createElement("div");
    card.className = "card custom-card mb-3";
    card.style.cursor = "pointer";
    card.onclick = () => (window.location.href = `${RUTA_URL}/perfil/${c.id}`);
    card.innerHTML = `
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h6 class="card-title">${c.nombre}</h6>
          <p class="mb-1">${c.ciudad}</p>
          ${
            c.mejor_resena
              ? `<p class="testimonial">“${c.mejor_resena}”</p>`
              : ""
          }
          <p class="rating">Valoración: ${parseFloat(
            c.media_valoracion
          ).toFixed(1)} ★ ${
      c.total_resenas
        ? `<span class="text-muted">(${c.total_resenas}) Reseñas</span>`
        : ""
    } </p>
        </div>
        ${
          c.precio_servicio
            ? `<div class="text-end"><span class="badge bg-light text-dark">${c.precio_servicio}</span></div>`
            : ""
        }
      </div>
    </div>
  `;

    sidebar.appendChild(card);
  });

  // Forzar que el mapa recalibre su tamaño al renderizar
  map.invalidateSize();
}

// Función para cargar cuidadores desde API (con o sin filtros)
function cargarCuidadores(
  ciudad = "",
  tipoMascota = "",
  servicio = "",
  tamano = ""
) {
  const url = `${RUTA_API}?ciudad=${encodeURIComponent(
    ciudad
  )}&tipo_mascota=${encodeURIComponent(
    tipoMascota
  )}&servicio=${encodeURIComponent(servicio)}&tamano=${encodeURIComponent(
    tamano
  )}`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      limpiarMarcadores();
      mostrarCuidadores(data);

      if (ciudad && ciudadesCoords[ciudad]) {
        map.setView(ciudadesCoords[ciudad], 11);
      } else {
        map.setView([40.416775, -3.70379], 6);
      }
    })
    .catch((err) => {
      console.error("Error al cargar cuidadores:", err);
    });
}

function obtenerParametrosURL() {
  const params = new URLSearchParams(window.location.search);
  return {
    ciudad: params.get("ciudad") || "",
    tipoMascota: params.get("tipo_mascota") || "",
    servicio: params.get("servicio") || "",
    tamano: params.get("tamano") || "",
  };
}

// Manejar el envío del formulario
document.addEventListener("DOMContentLoaded", () => {
  const { ciudad, tipoMascota, servicio, tamano } = obtenerParametrosURL();

  cargarCuidadores(ciudad, tipoMascota, servicio, tamano);

  const form = document.querySelector("#form-filtros");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

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

    // Juntamos los tamaños
    const tamanos = [...tamanoPerro, ...tamanoGato];

    // Pasamos el array como json
    const tamano = encodeURIComponent(JSON.stringify(tamanos));

    cargarCuidadores(ciudad, tipoMascota, servicio, tamano);
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
