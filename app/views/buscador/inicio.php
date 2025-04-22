<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<link rel="stylesheet" href="<?php echo RUTA_URL; ?>\js\leaflet\leaflet.css" />
<script src="<?php echo RUTA_URL; ?>\js\leaflet\leaflet.js"></script>

<!-- Buscador -->
<div id="cabecera-buscador">
    <div class="container my-3" id="buscador">
        <h2 class="text-center mb-4">Busca cuidadores cerca de ti</h2>
        <form class="row g-4 justify-content-center">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Ubicación" />
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Tipo de mascota</option>
                    <option>Perro</option>
                    <option>Gato</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Servicio</option>
                    <option>Alojamiento</option>
                    <option>Paseo de perros</option>
                    <option>Guardería de día</option>
                    <option>Visitas a domicilio</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Tamaño del animal</option>
                    <option>Pequeño</option>
                    <option>Mediano</option>
                    <option>Grande</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" />
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- CUIDADORES + MAPA -->
<div class="container-fluid">
    <div class="row no-gutters">
        <!-- Sidebar 30% -->
        <div class="col-md-4 col-lg-3 sidebar-custom p-4">
            <h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>

            <div class="card custom-card mb-3">
                <div class="card-body">
                    <h6 class="card-title">Carlos G.</h6>
                    <p class="card-text">Barcelona | Perros pequeños</p>
                    <p class="testimonial">“Muy atento y cariñoso con mi perro. ¡Repetiré sin duda!”</p>
                    <p class="rating">★★★★★</p>
                </div>
            </div>

            <!-- Repite estructura para los demás cuidadores -->
        </div>

        <!-- Mapa 70% -->
        <div class="col-md-8 col-lg-9 map-container-custom p-0">
            <div id="map" class="w-100 h-100"></div>
        </div>
    </div>
</div>


<!-- LEAFLET SCRIPT -->
<script>
    var map = L.map('map').setView([40.416775, -3.703790], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var markers = [{
            coords: [41.3851, 2.1734],
            popup: "Carlos G. - Barcelona"
        },
        {
            coords: [40.4168, -3.7038],
            popup: "Lucía M. - Madrid"
        },
        {
            coords: [39.4699, -0.3763],
            popup: "Elena R. - Valencia"
        },
        {
            coords: [37.3886, -5.9823],
            popup: "Javier L. - Sevilla"
        }
    ];

    markers.forEach(function(marker) {
        L.marker(marker.coords).addTo(map).bindPopup(marker.popup);
    });
</script>


<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>