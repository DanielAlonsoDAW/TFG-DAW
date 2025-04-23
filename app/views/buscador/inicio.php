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
        <form id="form-filtros" class="row g-4 justify-content-center">
            <div class="col-md-3">
                <input id="input-ciudad" type="text" class="form-control" placeholder="Ubicación" />
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

<!-- CUIDADORES Y MAPA -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar con lista de cuidadores -->
        <div class="col-md-4 col-lg-3 sidebar-custom p-4">
            <h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>
            <!-- Aquí se insertarán las tarjetas dinámicamente desde JS -->
        </div>

        <!-- Mapa -->
        <div class="col-md-8 col-lg-9 map-container-custom p-0">
            <div id="map" class="w-100 h-100"></div>
        </div>
    </div>
</div>

<script>
    const RUTA_API = "<?= RUTA_URL ?>/buscador/api_filtrar";
</script>

<script src="<?php echo RUTA_URL; ?>\js\buscador.js"></script>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>