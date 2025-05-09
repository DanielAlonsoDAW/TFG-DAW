document.addEventListener("DOMContentLoaded", () => {
  // 1) Inicializar mapa
  const map = L.map("map").setView([40.416775, -3.70379], 6);
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  }).addTo(map);

  // 2) LayerGroup para todos los marcadores
  const markerGroup = L.layerGroup().addTo(map);

  // 3) Función para pintar tarjeta en un fragmento
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

  // 4) Mostrar cuidadores optimizado
  function mostrarCuidadores(cuidadores) {
    // 4a) Preparamos sidebar y fragmento
    const sidebar = document.querySelector(".sidebar-custom");
    sidebar.innerHTML =
      '<h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>';
    const frag = document.createDocumentFragment();

    // 4b) Limpiamos marcadores anteriores
    markerGroup.clearLayers();

    // 4c) Creamos un bounds para centrar todo junto
    const bounds = L.latLngBounds();

    // 4d) Iteramos una sola vez
    cuidadores.forEach((c) => {
      const lat = parseFloat(c.lat),
        lng = parseFloat(c.lng);
      const valid = !isNaN(lat) && !isNaN(lng);

      if (valid) {
        // Añadimos marker al layerGroup
        const m = L.marker([lat, lng]).bindPopup(
          `<strong>${c.nombre}</strong><br>${c.ciudad}`
        );
        markerGroup.addLayer(m);
        bounds.extend([lat, lng]);
      }
      // Siempre creamos la tarjeta (sin marker si no hay coords)
      frag.appendChild(crearTarjeta(c));
    });

    // 4e) Volcamos todas las tarjetas al sidebar de golpe
    sidebar.appendChild(frag);

    // 4f) Ajustamos vista: un solo fitBounds
    if (bounds.isValid()) {
      map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12 });
    } else {
      map.setView([40.416775, -3.70379], 6);
    }
  }

  // 5) Carga API y render
  async function cargarCuidadores(ciudad = "", tipo = "", serv = "", tam = "") {
    const url =
      `${RUTA_URL}/buscador/api_filtrar?` +
      `ciudad=${encodeURIComponent(ciudad)}` +
      `&tipo_mascota=${encodeURIComponent(tipo)}` +
      `&servicio=${encodeURIComponent(serv)}` +
      `&tamano=${encodeURIComponent(tam)}`;
    try {
      const res = await fetch(url);
      const data = await res.json();
      mostrarCuidadores(data);
    } catch (err) {
      console.error("Error al cargar cuidadores:", err);
    }
  }

  // 6) Parámetros iniciales y evento form
  const params = new URLSearchParams(window.location.search);
  cargarCuidadores(
    params.get("ciudad") || "",
    params.get("tipo_mascota") || "",
    params.get("servicio") || "",
    params.get("tamano") || ""
  );

  document.querySelector("#form-filtros").addEventListener("submit", (e) => {
    e.preventDefault();
    const ciudadVal = document.querySelector("#input-ciudad").value.trim();
    const tipoVal = Array.from(
      document.querySelectorAll('input[name="tipo_mascota[]"]:checked')
    )
      .map((cb) => cb.value)
      .join(",");
    const servVal = document.querySelector("select[name='servicio']").value;
    const tamPerro = Array.from(
      document.querySelectorAll('input[name="tamano_perro[]"]:checked')
    ).map((cb) => ({ tipo: "perro", tamano: cb.value }));
    const tamGato = Array.from(
      document.querySelectorAll('input[name="tamano_gato[]"]:checked')
    ).map((cb) => ({ tipo: "gato", tamano: cb.value }));
    const tamArr = [...tamPerro, ...tamGato];
    const tamVal = encodeURIComponent(JSON.stringify(tamArr));

    cargarCuidadores(ciudadVal, tipoVal, servVal, tamVal);
  });
});
