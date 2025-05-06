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
    card.innerHTML = `
      <div class="card-body">
        <h6 class="card-title">${c.nombre}</h6>
        <p class="card-text">${c.ciudad}</p>
        <p class="rating">Valoración: ${parseFloat(c.media_valoracion).toFixed(
          1
        )} ★</p>
      </div>
    `;
    sidebar.appendChild(card);
  });
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

// Manejar el envío del formulario
document.addEventListener("DOMContentLoaded", () => {
  cargarCuidadores();

  const form = document.querySelector("#form-filtros");
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const ciudad = document.querySelector("#input-ciudad").value.trim();
    const tipoMascota = document.querySelector(
      "select.form-select:nth-of-type(1)"
    ).value;
    const servicio = document.querySelector(
      "select.form-select:nth-of-type(2)"
    ).value;
    const tamano = document.querySelector(
      "select.form-select:nth-of-type(3)"
    ).value;

    // Si el valor está en "seleccionado" por defecto, enviar vacío
    cargarCuidadores(
      ciudad,
      tipoMascota.includes("Tipo") ? "" : tipoMascota,
      servicio.includes("Servicio") ? "" : servicio,
      tamano.includes("Tamaño") ? "" : tamano
    );
  });
});
