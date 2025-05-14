<?php
require RUTA_APP . '/views/inc/header.php';
$tamanoArray = $datos['tamano'] ?? [];
?>
<link rel="stylesheet" href="js/leaflet/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="js/leaflet/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<div id="cabecera-buscador">
    <div class="container my-4">
        <h2 class="text-center mb-4">Busca cuidadores cerca de ti</h2>
        <div class="formulario-container col-12 col-md-10">
            <form action="<?= $datos['api_filtrar'] ?>" method="POST" id="form-filtros" class="row gy-4 gx-3 justify-content-center">
                <div class="col-12 col-md-6">
                    <input id="input-ciudad" name="ciudad" type="text" class="form-control" placeholder="Ubicación" value="<?= htmlspecialchars($datos['ciudad']) ?>" />
                </div>
                <div class="col-12 col-md-6">
                    <input type="date" name="fecha" class="form-control" value="<?= htmlspecialchars($datos['fecha']) ?>" />
                </div>
                <div class="col-12 col-md-6 text-center">
                    <label class="form-label mb-3">Tipo de mascota</label>
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <input type="checkbox" class="btn-check" name="tipo_mascota[]" value="Perro" id="mascota-perro" <?= in_array('Perro', $datos['tipo_mascota']) ? 'checked' : '' ?>>
                        <label class="btn btn-perro" for="mascota-perro"><i class="fa-solid fa-dog"></i> Perro</label>
                        <input type="checkbox" class="btn-check" name="tipo_mascota[]" value="Gato" id="mascota-gato" <?= in_array('Gato', $datos['tipo_mascota']) ? 'checked' : '' ?>>
                        <label class="btn btn-gato" for="mascota-gato"><i class="fa-solid fa-cat"></i> Gato</label>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <select name="servicio" class="form-select">
                        <option disabled value="">Servicio</option>
                        <?php foreach (["Alojamiento", "Paseos", "Guardería de día", "Visitas a domicilio", "Cuidado a domicilio", "Taxi"] as $s): ?>
                            <option value="<?= $s ?>" <?= $datos['servicio'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-8 col-md-6 <?= empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'perro')) ? 'd-none' : '' ?>" id="bloque-tamano-perro">
                    <label class="form-label d-block mb-2">Tamaño del perro</label>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <?php foreach (['Pequeño' => '< 35 cm', 'Mediano' => '35–49 cm', 'Grande' => '≥ 50 cm'] as $tam => $medida):
                            $checked = in_array(['tipo' => 'perro', 'tamano' => $tam], $tamanoArray) ? 'checked' : '';
                            $id = 'perro-' . strtolower($tam);
                        ?>
                            <input type="checkbox" class="btn-check" id="<?= $id ?>" name="tamano_perro[]" value="<?= $tam ?>" <?= $checked ?> autocomplete="off">
                            <label class="btn btn-perro mt-2" for="<?= $id ?>"><i class="fa-solid fa-dog me-1"></i> <?= $tam ?> (<?= $medida ?>)</label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-8 col-md-6 <?= empty(array_filter($tamanoArray, fn($t) => $t['tipo'] === 'gato')) ? 'd-none' : '' ?>" id="bloque-tamano-gato">
                    <label class="form-label d-block mb-2">Tamaño del gato</label>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <?php foreach (['Pequeño' => '< 3 kg', 'Mediano' => '3–5 kg', 'Grande' => '> 5 kg'] as $tam => $peso):
                            $checked = in_array(['tipo' => 'gato', 'tamano' => $tam], $tamanoArray) ? 'checked' : '';
                            $id = 'gato-' . strtolower($tam);
                        ?>
                            <input type="checkbox" class="btn-check" id="<?= $id ?>" name="tamano_gato[]" value="<?= $tam ?>" <?= $checked ?> autocomplete="off">
                            <label class="btn btn-gato mt-2" for="<?= $id ?>"><i class="fa-solid fa-cat me-1"></i> <?= $tam ?> (<?= $peso ?>)</label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <input type="hidden" name="tamano" id="input-tamano-json" value='<?= json_encode($tamanoArray, JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>
                <div class="col-12 col-md-6 text-center text-start">
                    <input type="submit" class="btn btn-primary px-4 py-2" value="Buscar" />
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-lg-3 sidebar-custom p-4">
            <h5 class="text-primary-custom mb-4">Cuidadores disponibles</h5>
        </div>
        <div class="col-md-8 col-lg-9 map-container-custom p-0">
            <div id="map" class="w-100 h-100"></div>
        </div>
    </div>
</div>

<script>
    const RUTA_URL = "<?= RUTA_URL ?>";
</script>
<script src="<?= RUTA_URL ?>/js/buscador.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>