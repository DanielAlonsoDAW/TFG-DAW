<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>


<!-- Leaflet CSS -->
<link rel="stylesheet" href="<?php echo RUTA_URL; ?>\js\leaflet\leaflet.css" />
<!-- Leaflet Control Geocoder CSS -->
<link rel="stylesheet" href="<?php echo RUTA_URL; ?>js\leaflet\leaflet-control-geocoder\Control.Geocoder.css" />
<!-- Leaflet JS -->
<script src="js/leaflet/leaflet.js"></script>
<!-- Leaflet Control Geocoder JS -->
<script src="<?php echo RUTA_URL; ?>js\leaflet\leaflet-control-geocoder\Control.Geocoder.js"></script>

<!-- Buscador -->
<div id="cabecera-buscador">
    <div class="container my-4">
        <h2 class="text-center mb-4">Busca cuidadores cerca de ti</h2>
        <div class="form-container">
            <form action="<?php echo RUTA_URL; ?>/buscador/api_filtrar" method="POST" id="form-filtros" class="row gy-4 gx-3 justify-content-center">
                <!-- Ciudad -->
                <div class="col-12 col-md-6">
                    <input id="input-ciudad" name="ciudad" type="text" class="form-control" placeholder="Ubicación" />
                </div>
                <!-- Tipo de mascota -->
                <div class="col-12 col-md-6 text-center">
                    <label class="form-label mb-3">Tipo de mascota</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tipo_mascota[]" value="Perro" id="mascota-perro">
                            <label class="form-check-label" for="mascota-perro">Perro</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tipo_mascota[]" value="Gato" id="mascota-gato">
                            <label class="form-check-label" for="mascota-gato">Gato</label>
                        </div>
                    </div>
                </div>
                <!-- Servicio -->
                <div class="col-12 col-md-6">
                    <select name="servicio" class="form-select">
                        <option selected disabled value="">Servicio</option>
                        <option value="Alojamiento">Alojamiento</option>
                        <option value="Paseos">Paseo de perros</option>
                        <option value="Guardería de día">Guardería de día</option>
                        <option value="Visitas a domicilio">Visitas a domicilio</option>
                        <option value="Cuidado a domicilio">Cuidado a domicilio</option>
                        <option value="Taxi">Taxi</option>
                    </select>
                </div>
                <!-- Tamaño del animal para Perro -->
                <div class="col-12 col-md-6 d-none" id="bloque-tamano-perro">
                    <label class="form-label d-block mb-2">Tamaño del perro</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_perro[]" value="Pequeño" id="perro-pequeno">
                            <label class="form-check-label" for="perro-pequeno">Pequeño</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_perro[]" value="Mediano" id="perro-mediano">
                            <label class="form-check-label" for="perro-mediano">Mediano</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_perro[]" value="Grande" id="perro-grande">
                            <label class="form-check-label" for="perro-grande">Grande</label>
                        </div>
                    </div>
                </div>
                <!-- Tamaño del animal para Gato -->
                <div class="col-12 col-md-6 d-none" id="bloque-tamano-gato">
                    <label class="form-label d-block mb-2">Tamaño del gato</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_gato[]" value="Pequeño" id="gato-pequeno">
                            <label class="form-check-label" for="gato-pequeno">Pequeño</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_gato[]" value="Mediano" id="gato-mediano">
                            <label class="form-check-label" for="gato-mediano">Mediano</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tamano_gato[]" value="Grande" id="gato-grande">
                            <label class="form-check-label" for="gato-grande">Grande</label>
                        </div>
                    </div>
                </div>

                <!-- Fecha -->
                <div class="col-12 col-md-6">
                    <input type="date" name="fecha" class="form-control" />
                </div>
                <!-- Botón -->
                <div class="col-12 col-md-6 text-center text-start">
                    <input type="submit" class="btn btn-primary px-4 py-2" value="Buscar" />
                </div>
            </form>
        </div>
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