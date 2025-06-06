<?php require RUTA_APP . '/views/inc/header.php'; ?>

<?php
// Filtra los tipos de mascotas admitidas por el cuidador
$perroActivo = array_filter($datos['admite'], fn($a) => $a->tipo_mascota === 'perro');
$gatoActivo = array_filter($datos['admite'], fn($a) => $a->tipo_mascota === 'gato');
// Obtiene los tamaños admitidos para cada tipo de mascota
$tamanosPerro = array_column($perroActivo, 'tamano');
$tamanosGato = array_column($gatoActivo, 'tamano');
?>

<div class="container mt-5 mb-5">
  <h2 class="section-title">Editar Servicios Ofrecidos</h2>
  <div class="formulario-container col-12 col-md-8 col-lg-7">
    <!-- Formulario para editar los servicios ofrecidos por el cuidador -->
    <form method="POST">
      <!-- Campo para el número máximo de mascotas al día -->
      <div class="mb-4">
        <label for="max_mascotas_dia" class="form-label">Máx. Mascotas al día:</label>
        <input type="number" id="max_mascotas_dia" name="max_mascotas_dia" class="form-control" value="<?= $entrada['max_mascotas_dia'] ?? $datos['datos']->max_mascotas_dia ?>">
        <span class="text-danger" id="error-max_mascotas_dia"><?= $datos['errores']['max_mascotas_dia'] ?? '' ?></span>
      </div>

      <!-- Selección de tipos de mascotas aceptadas -->
      <div class="mb-4">
        <label class="form-label">Acepta:</label>
        <span id="error-tipo" class="text-danger"></span>
        <div class="d-flex gap-3 mt-2">
          <!-- Checkbox para perros -->
          <input type="checkbox" class="btn-check" id="acepta_perro" name="acepta_perro" autocomplete="off"
            <?= isset($entrada['acepta_perro']) || !empty($perroActivo) ? 'checked' : '' ?>>
          <label class="btn btn-perro" for="acepta_perro">
            <i class="fa-solid fa-dog"></i> Perro
          </label>
          <!-- Checkbox para gatos -->
          <input type="checkbox" class="btn-check" id="acepta_gato" name="acepta_gato" autocomplete="off"
            <?= isset($entrada['acepta_gato']) || !empty($gatoActivo) ? 'checked' : '' ?>>
          <label class="btn btn-gato" for="acepta_gato">
            <i class="fa-solid fa-cat"></i> Gato
          </label>
        </div>
      </div>

      <!-- Selección de tamaños de perro admitidos -->
      <div class="mb-4" id="grupo-perro" style="<?= !empty($perroActivo) ? '' : 'display: none;' ?>">
        <label class="form-label">Tamaños de perro admitidos:</label>
        <span id="error-tamano-perro" class="text-danger"></span>
        <div class="d-flex flex-wrap gap-2">
          <!-- Tamaño pequeño -->
          <input type="checkbox" class="btn-check" id="perro-pequeno" name="tamanos_perro[]" value="pequeño" <?= in_array('pequeño', $tamanosPerro) ? 'checked' : '' ?>>
          <label class="btn btn-perro mt-2 me-2" for="perro-pequeno"><?= getIcono('perro') ?> Pequeño &lt; 35 cm</label>
          <!-- Tamaño mediano -->
          <input type="checkbox" class="btn-check" id="perro-mediano" name="tamanos_perro[]" value="mediano" <?= in_array('mediano', $tamanosPerro) ? 'checked' : '' ?>>
          <label class="btn btn-perro mt-2 me-2" for="perro-mediano"><?= getIcono('perro') ?> Mediano 35–49 cm</label>
          <!-- Tamaño grande -->
          <input type="checkbox" class="btn-check" id="perro-grande" name="tamanos_perro[]" value="grande" <?= in_array('grande', $tamanosPerro) ? 'checked' : '' ?>>
          <label class="btn btn-perro mt-2 me-2" for="perro-grande"><?= getIcono('perro') ?> Grande ≥ 50 cm</label>
        </div>
      </div>

      <!-- Selección de tamaños de gato admitidos -->
      <div class="mb-4" id="grupo-gato" style="<?= !empty($gatoActivo) ? '' : 'display: none;' ?>">
        <label class="form-label">Tamaños de gato admitidos:</label>
        <span id="error-tamano-gato" class="text-danger"></span>
        <div class="d-flex flex-wrap gap-2">
          <!-- Tamaño pequeño -->
          <input type="checkbox" class="btn-check" id="gato-pequeno" name="tamanos_gato[]" value="pequeño" <?= in_array('pequeño', $tamanosGato) ? 'checked' : '' ?>>
          <label class="btn btn-gato mt-2 me-2" for="gato-pequeno"><?= getIcono('gato') ?> Pequeño &lt; 3 kg</label>
          <!-- Tamaño mediano -->
          <input type="checkbox" class="btn-check" id="gato-mediano" name="tamanos_gato[]" value="mediano" <?= in_array('mediano', $tamanosGato) ? 'checked' : '' ?>>
          <label class="btn btn-gato mt-2 me-2" for="gato-mediano"><?= getIcono('gato') ?> Mediano 3–5 kg</label>
          <!-- Tamaño grande -->
          <input type="checkbox" class="btn-check" id="gato-grande" name="tamanos_gato[]" value="grande" <?= in_array('grande', $tamanosGato) ? 'checked' : '' ?>>
          <label class="btn btn-gato mt-2 me-2" for="gato-grande"><?= getIcono('gato') ?> Grande &gt; 5 kg</label>
        </div>
      </div>

      <!-- Selección de servicios ofrecidos y sus precios -->
      <div class="mb-4">
        <label class="form-label">Servicios ofrecidos:</label>
        <span id="error-servicios" class="text-danger mb-2"></span>
        <div id="grupo-servicios">
          <?php
          // Lista de servicios disponibles
          $serviciosDisponibles = [
            'Alojamiento',
            'Cuidado a domicilio',
            'Visitas a domicilio',
            'Paseos',
            'Guardería de día',
            'Taxi'
          ];
          // Servicios actualmente ofrecidos por el cuidador
          $serviciosActuales = [];
          foreach ($datos['servicios'] as $s) {
            $serviciosActuales[$s->servicio] = $s->precio;
          }
          ?>
          <?php foreach ($serviciosDisponibles as $serv): $clave = str_replace(' ', '_', $serv); ?>
            <div
              class="row align-items-center mb-3 servicio-row"
              data-servicio="<?= htmlspecialchars($serv, ENT_QUOTES) ?>">
              <div class="col-auto">
                <div class="form-check">
                  <!-- Checkbox para activar/desactivar el servicio -->
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="servicios[]"
                    id="servicio_<?= $serv ?>"
                    value="<?= $serv ?>"
                    <?= isset($serviciosActuales[$serv]) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="servicio_<?= $serv ?>">
                    <?= $serv ?>
                  </label>
                </div>
              </div>
              <div class="col">
                <?php if ($serv === 'Taxi'): ?>
                  <!-- Campo de precio especial para Taxi (€/km) -->
                  <div class="input-group">
                    <span class="input-group-text">10 € +</span>
                    <input type="number" step="0.01" min="0" class="form-control"
                      name="precio[<?= $clave ?>]" placeholder="€/km"
                      value="<?= $serviciosActuales[$serv] ?? '' ?>">
                    <span class="input-group-text">€/km</span>
                  </div>
                <?php else: ?>
                  <!-- Campo de precio para el resto de servicios -->
                  <div class="input-group">
                    <input type="number" step="0.01" min="0" class="form-control"
                      name="precio[<?= $clave ?>]" placeholder="Precio"
                      value="<?= $serviciosActuales[$serv] ?? '' ?>">
                    <span class="input-group-text">
                      <?php
                      // Unidad de precio según el servicio
                      echo match ($serv) {
                        'Alojamiento', 'Cuidado a domicilio' => '€/noche',
                        'Guardería de día'                    => '€/día',
                        'Paseos'                              => '€/paseo',
                        'Visitas a domicilio'                => '€/visita',
                        default                               => '',
                      };
                      ?>
                    </span>
                  </div>
                <?php endif; ?>

                <!-- Mensaje de error para el precio del servicio -->
                <span id="error-precio_<?= $clave ?>"
                  class="text-danger mb-2 precio-error"></span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <!-- Botón para guardar los cambios -->
      <div class="text-center mt-5">
        <input type="submit" class="btn btn-primary" value="Guardar cambios">
      </div>
    </form>
  </div>
</div>

<!-- Script JS para la lógica de la vista -->
<script src="<?= RUTA_URL ?>/js/cuidadores/editarServicios.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>