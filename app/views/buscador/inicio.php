<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';

$tamanoArray = [];
if (!empty($_GET['tamano'])) {
    $decoded = json_decode($_GET['tamano'], true);
    if (is_array($decoded)) {
        $tamanoArray = $decoded;
    }
}
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
            <form action="<?php echo RUTA_URL; ?>/buscador/api_filtrar" method="GET" id="form-filtros" class="row gy-4 gx-3 justify-content-center">
                <!-- Ciudad -->
                <div class="col-12 col-md-6">
                    <input id="input-ciudad" name="ciudad" type="text" class="form-control" placeholder="Ubicación"
                        value="<?php echo $_GET['ciudad'] ?? ''; ?>" />
                </div>
                <!-- Fecha -->
                <div class="col-12 col-md-6">
                    <input type="date" name="fecha" class="form-control"
                        value="<?php echo $_GET['fecha'] ?? ''; ?>" />
                </div>
                <!-- Tipo de mascota -->
                <div class="col-12 col-md-6 text-center">
                    <label class="form-label mb-3">Tipo de mascota</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tipo_mascota[]" value="Perro" id="mascota-perro"
                                <?php if (!empty($_GET['tipo_mascota']) && in_array('Perro', explode(',', $_GET['tipo_mascota']))) echo 'checked'; ?>>
                            <label class="form-check-label" for="mascota-perro">Perro</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tipo_mascota[]" value="Gato" id="mascota-gato"
                                <?php if (!empty($_GET['tipo_mascota']) && in_array('Gato', explode(',', $_GET['tipo_mascota']))) echo 'checked'; ?>>
                            <label class="form-check-label" for="mascota-gato">Gato</label>
                        </div>
                    </div>
                </div>
                <!-- Servicio -->
                <div class="col-12 col-md-6">
                    <select name="servicio" class="form-select">
                        <option disabled value="">Servicio</option>
                        <?php
                        $servicios = ["Alojamiento", "Paseos", "Guardería de día", "Visitas a domicilio", "Cuidado a domicilio", "Taxi"];
                        foreach ($servicios as $s) {
                            $selected = (isset($_GET['servicio']) && $_GET['servicio'] === $s) ? 'selected' : '';
                            echo "<option value=\"$s\" $selected>$s</option>";
                        }
                        ?>
                    </select>

                </div>
                <!-- Tamaño del animal para Perro -->
                <div class="col-12 col-md-6 <?php echo empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'perro')) ? 'd-none' : ''; ?>" id="bloque-tamano-perro">
                    <label class="form-label d-block mb-2">Tamaño del perro</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <?php
                        $tamanosPerro = ['Pequeño', 'Mediano', 'Grande'];
                        foreach ($tamanosPerro as $tam) {
                            $isChecked = in_array(['tipo' => 'perro', 'tamano' => $tam], $tamanoArray);
                            echo '
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tamano_perro[]" value="' . $tam . '" id="perro-' . strtolower($tam) . '"' . ($isChecked ? ' checked' : '') . '>
                    <label class="form-check-label" for="perro-' . strtolower($tam) . '">' . $tam . '</label>
                </div>
            ';
                        }
                        ?>
                    </div>
                </div>
                <!-- Tamaño del animal para Gato -->
                <div class="col-12 col-md-6 <?php echo empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'gato')) ? 'd-none' : ''; ?>" id="bloque-tamano-gato">
                    <label class="form-label d-block mb-2">Tamaño del gato</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <?php
                        $tamanosGato = ['Pequeño', 'Mediano', 'Grande'];
                        foreach ($tamanosGato as $tam) {
                            $isChecked = in_array(['tipo' => 'gato', 'tamano' => $tam], $tamanoArray);
                            echo '
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tamano_gato[]" value="' . $tam . '" id="gato-' . strtolower($tam) . '"' . ($isChecked ? ' checked' : '') . '>
                    <label class="form-check-label" for="gato-' . strtolower($tam) . '">' . $tam . '</label>
                </div>
            ';
                        }
                        ?>
                    </div>
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
    const RUTA_URL = "<?= RUTA_URL ?>";
</script>

<script src="<?php echo RUTA_URL; ?>\js\buscador.js"></script>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>