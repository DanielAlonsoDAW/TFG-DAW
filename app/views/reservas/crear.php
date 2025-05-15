<?php
require RUTA_APP . '/views/inc/header.php';

$cuidador = $datos['cuidador'] ?? [];
$mascotas = $datos['mascotas'] ?? [];
$precios = $datos['precios'] ?? [];
?>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Geocoder con Nominatim -->
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>


<div class="container my-5">
    <div class="row">
        <!-- Columna izquierda: Info del cuidador -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="<?= RUTA_URL . '/' . $cuidador->imagen ?>" class="img-fluid rounded shadow-sm cuidador-img-responsive mb-3" alt="Imagen cuidador">
                <div class="card-body">
                    <h4 class="text-primary-custom"><?= htmlspecialchars($cuidador->nombre) ?></h4>
                    <p><strong><?= getIcono("location") ?> Ciudad:</strong> <?= htmlspecialchars($cuidador->ciudad) ?> (<?= htmlspecialchars($cuidador->pais) ?>)</p>
                    <p><strong><?= getIcono("star") ?> Valoración:</strong>
                        <?= estrellasBootstrap($cuidador->promedio_calificacion ?? 0) ?>
                        (<?= number_format($cuidador->promedio_calificacion ?? 0, 1) ?>/5)
                    </p>
                </div>
            </div>
        </div>

        <!-- Columna central: Formulario -->
        <div class="col-md-4 mb-4">
            <div class="formulario-container">
                <form id="formReserva" action="<?= RUTA_URL ?>/reservas/crear/<?= $cuidador->id ?>" method="POST">
                    <div class="mb-3">
                        <label for="servicio" class="form-label">Servicio:</label>
                        <select name="servicio" id="servicio" class="form-select" required>
                            <option value="">Selecciona</option>
                            <?php foreach ($datos['precios'] as $servicio => $precio): ?>
                                <option value="<?= $servicio ?>"><?= $servicio ?> (<?= number_format($precio, 2) ?>€)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha inicio:</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha fin:</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mascotas:</label>
                        <div class="row">
                            <?php foreach ($mascotas as $mascota): ?>
                                <div class="col-6 form-check">
                                    <input class="form-check-input mascotas-check" type="checkbox"
                                        name="mascotas[]" id="mascota_<?= $mascota->id ?>"
                                        value="<?= $mascota->id ?>" data-tipo="<?= strtolower($mascota->tipo) ?>">
                                    <label class="form-check-label" for="mascota_<?= $mascota->id ?>">
                                        <?= htmlspecialchars($mascota->nombre) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="camposTaxi" style="display: none;">
                        <div class="mb-3">
                            <label for="direccion_origen" class="form-label">Dirección recogida:</label>
                            <input type="text" ... onclick="seleccionarDireccion('origen')" />
                            <div id="map-direccion" style="height: 300px; display: none;" class="mb-3"></div>
                        </div>
                        <div class="mb-3">
                            <label for="direccion_destino" class="form-label">Dirección destino:</label>
                            <input type="text" ... onclick="seleccionarDireccion('destino')" />
                            <div id="map-direccion" style="height: 300px; display: none;" class="mb-3"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Columna derecha: Resumen y precio -->
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm">
                <h5 class="text-primary-custom mb-3">Resumen de la reserva</h5>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item"><strong>Servicio:</strong> <span id="resumen-servicio">-</span></li>
                    <li class="list-group-item"><strong>Mascotas:</strong> <span id="resumen-mascotas">0</span></li>
                    <li class="list-group-item"><strong>Precio base por mascota:</strong> <span id="resumen-precio-base">0.00€</span></li>
                    <li class="list-group-item"><strong>Distancia estimada:</strong> <span id="resumen-distancia">0.00 km</span></li>
                    <li class="list-group-item"><strong>Suplemento Taxi:</strong> <span id="resumen-taxi">0.00€</span></li>
                    <li class="list-group-item"><strong>Total estimado:</strong> <span id="resumen-total">0.00€</span></li>
                </ul>
                <button type="submit" form="formReserva" class="btn btn-primary w-100">Reservar y Pagar</button>
            </div>
        </div>
    </div>
</div>

<script>
    const preciosPorServicio = <?= json_encode($precios, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>;
    cosnt RUTA_URL = "<?= RUTA_URL ?>";
</script>
<script src="<?= RUTA_URL ?>/js/reservas/formulario.js"></script>


<?php require RUTA_APP . '/views/inc/footer.php'; ?>