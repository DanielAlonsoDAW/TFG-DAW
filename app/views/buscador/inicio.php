<?php
// Incluye el header común de la aplicación
require RUTA_APP . '/views/inc/header.php';

// Obtiene el array de tamaños de mascota si existe
$tamanoArray = $datos['tamano'] ?? [];
?>
<!-- Hoja de estilos y scripts para Leaflet (mapa interactivo) -->
<link rel="stylesheet" href="js/leaflet/leaflet.css" />
<script src="js/leaflet/leaflet.js"></script>

<div id="cabecera-buscador">
    <div class="container my-4">
        <h2 class="text-center mb-4">Busca cuidadores cerca de ti</h2>
        <!-- Formulario de filtros de búsqueda -->
        <div class="formulario-container col-12 col-md-10">
            <form action="<?= $datos['api_filtrar'] ?>" method="POST" id="form-filtros" class="row gy-4 gx-3 justify-content-center">
                <!-- Campo de ubicación -->
                <div class="col-12 col-md-6">
                    <input id="input-ciudad" name="ciudad" type="text" class="form-control" placeholder="Ubicación" value="<?= htmlspecialchars($datos['ciudad']) ?>" />
                </div>

                <!-- Selección de servicio -->
                <div class="col-12 col-md-6">
                    <select name="servicio" class="form-select">
                        <option disabled value="">Servicio</option>
                        <?php foreach (["Alojamiento", "Paseos", "Guardería de día", "Visitas a domicilio", "Cuidado a domicilio", "Taxi"] as $s): ?>
                            <option value="<?= $s ?>" <?= $datos['servicio'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Selección de tipo de mascota -->
                <div class="col-12 col-md-6 text-center">
                    <label class="form-label mb-3">Tipo de mascota</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <!-- Checkbox para Perro -->
                        <input type="checkbox" class="btn-check" name="tipo_mascota[]" value="Perro" id="mascota-perro" <?= in_array('Perro', $datos['tipo_mascota']) ? 'checked' : '' ?>>
                        <label class="btn btn-perro" for="mascota-perro"><i class="fa-solid fa-dog"></i> Perro</label>
                        <!-- Checkbox para Gato -->
                        <input type="checkbox" class="btn-check" name="tipo_mascota[]" value="Gato" id="mascota-gato" <?= in_array('Gato', $datos['tipo_mascota']) ? 'checked' : '' ?>>
                        <label class="btn btn-gato" for="mascota-gato"><i class="fa-solid fa-cat"></i> Gato</label>
                    </div>
                </div>

                <!-- Selección de tamaño del perro (solo si corresponde) -->
                <div class="col-8 col-md-6 <?= empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'perro')) ? 'd-none' : '' ?>" id="bloque-tamano-perro">
                    <label class="form-label d-block mb-2">Tamaño del perro</label>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <?php
                        // Opciones de tamaño para perros
                        foreach (['Pequeño' => '< 35 cm', 'Mediano' => '35–49 cm', 'Grande' => '≥ 50 cm'] as $tam => $medida):
                            $checked = in_array(['tipo' => 'perro', 'tamano' => $tam], $tamanoArray) ? 'checked' : '';
                            $id = 'perro-' . strtolower($tam);
                        ?>
                            <input type="checkbox" class="btn-check" id="<?= $id ?>" name="tamano_perro[]" value="<?= $tam ?>" <?= $checked ?> autocomplete="off">
                            <label class="btn btn-perro mt-2" for="<?= $id ?>"><i class="fa-solid fa-dog me-1"></i> <?= $tam ?> <?= $medida ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Selección de tamaño del gato (solo si corresponde) -->
                <div class="col-8 col-md-6 <?= empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'gato')) ? 'd-none' : '' ?>" id="bloque-tamano-gato">
                    <label class="form-label d-block mb-2">Tamaño del gato</label>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <?php
                        // Opciones de tamaño para gatos
                        foreach (['Pequeño' => '< 3 kg', 'Mediano' => '3–5 kg', 'Grande' => '> 5 kg'] as $tam => $peso):
                            $checked = in_array(['tipo' => 'gato', 'tamano' => $tam], $tamanoArray) ? 'checked' : '';
                            $id = 'gato-' . strtolower($tam);
                        ?>
                            <input type="checkbox" class="btn-check" id="<?= $id ?>" name="tamano_gato[]" value="<?= $tam ?>" <?= $checked ?> autocomplete="off">
                            <label class="btn btn-gato mt-2" for="<?= $id ?>"><i class="fa-solid fa-cat me-1"></i> <?= $tam ?> <?= $peso ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Campo oculto para enviar los tamaños seleccionados en formato JSON -->
                <input type="hidden" name="tamano" id="input-tamano-json" value='<?= json_encode($tamanoArray, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                <!-- Botón de búsqueda -->
                <div class="col-12 col-md-6 text-center text-start">
                    <input type="submit" class="btn btn-primary px-4 py-2" value="Buscar" />
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Contenedor principal: barra lateral y mapa -->
<div class="container-fluid">
    <div class="row">
        <!-- Barra lateral: lista de cuidadores -->
        <div class="col-md-4 col-lg-3 sidebar-custom p-4">
            <h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>
            <!-- Aquí se mostrarán los cuidadores filtrados -->
        </div>
        <!-- Mapa interactivo -->
        <div class="col-md-8 col-lg-9 map-container-custom p-0">
            <div id="map" class="w-100 h-100"></div>
        </div>
    </div>
</div>

<!-- Variable global con la ruta base de la aplicación -->
<script>
    const RUTA_URL = "<?= RUTA_URL ?>";
</script>
<!-- Script principal del buscador -->
<script src="<?= RUTA_URL ?>/js/buscador.js"></script>

<?php
// Incluye el footer común de la aplicación
require RUTA_APP . '/views/inc/footer.php';
?>