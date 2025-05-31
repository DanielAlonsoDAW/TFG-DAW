<?php
// Incluye el header común de la aplicación
require RUTA_APP . '/views/inc/header.php';
?>

<div class="container mt-5 mb-5">
    <h2 class="section-title">Añadir Nueva Mascota</h2>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <!-- Formulario para agregar una nueva mascota -->
        <form id="formAgregarMascota" method="POST" enctype="multipart/form-data" novalidate>
            <?php
            // Recupera errores y datos de entrada previos si existen
            $e  = $datos['errores']  ?? [];
            $in = $datos['entrada'] ?? [];
            ?>

            <!-- Campo: Nombre de la mascota -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control <?= !empty($e['nombre']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($in['nombre'] ?? '') ?>">
                <span class="text-danger"><?= $e['nombre'] ?? '' ?></span>
            </div>

            <!-- Campo: Tipo de mascota (Perro o Gato) -->
            <div class="mb-3">
                <label class="form-label">Tipo</label><br>
                <input type="radio" class="btn-check" name="tipo" id="btn-perro" value="perro" <?= ($in['tipo'] ?? '') === 'perro' ? 'checked' : '' ?>>
                <label class="btn btn-perro me-2" for="btn-perro"><i class="fa-solid fa-dog"></i> Perro</label>

                <input type="radio" class="btn-check" name="tipo" id="btn-gato" value="gato" <?= ($in['tipo'] ?? '') === 'gato' ? 'checked' : '' ?>>
                <label class="btn btn-gato" for="btn-gato"><i class="fa-solid fa-cat"></i> Gato</label>
                <span class="text-danger"><?= $e['Tipo'] ?? '' ?></span>
            </div>

            <!-- Campo: Raza de la mascota (se rellena dinámicamente según el tipo) -->
            <div class="mb-3">
                <label for="raza" class="form-label">Raza:</label>
                <select id="raza" name="raza" data-valor-previo="<?= htmlspecialchars($in['raza'] ?? '') ?>"
                    class="form-select <?= !empty($e['raza']) ? 'is-invalid' : '' ?>">
                    <option value="" disabled <?= empty($in['raza']) ? 'selected' : '' ?>>Elige una raza</option>
                </select>
                <span class="text-danger"><?= $e['raza'] ?? '' ?></span>
            </div>

            <!-- Campo: Edad de la mascota -->
            <div class="mb-3">
                <label for="edad" class="form-label">Edad (años):</label>
                <input type="number" id="edad" name="edad" min="0" class="form-control <?= !empty($e['edad']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($in['edad'] ?? '') ?>">
                <span class="text-danger"><?= $e['edad'] ?? '' ?></span>
            </div>

            <!-- Campo: Tamaño de la mascota (se gestiona con JS) -->
            <div class="mb-3">
                <label class="form-label">Tamaño:</label>
                <div id="tamano" class="<?= !empty($e['tamano']) ? 'border border-danger text-danger' : '' ?>">
                    <span class="badge bg-primary p-2" id="tamano-badge">
                    </span>
                </div>
                <input type="hidden" id="tamano-hidden" name="tamano" value="<?= htmlspecialchars($in['tamano'] ?? '') ?>">
                <span class="text-danger"><?= $e['tamano'] ?? '' ?></span>
            </div>

            <!-- Campo: Observaciones adicionales -->
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones:</label>
                <textarea id="observaciones" name="observaciones" class="form-control"><?= htmlspecialchars($in['observaciones'] ?? '') ?></textarea>
            </div>

            <!-- Campo: Subida de imágenes de la mascota -->
            <div class="mb-3">
                <label for="imagenes" class="form-label">Imágenes:</label>
                <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" class="form-control <?= !empty($e['imagenes']) ? 'is-invalid' : '' ?>">
                <small class="form-text text-muted">Puedes seleccionar varias imágenes</small>
                <span class="text-danger"><?= $e['imagenes'] ?? '' ?></span>
            </div>

            <!-- Botón para enviar el formulario -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Añadir Mascota</button>
            </div>
        </form>
    </div>
</div>

<!-- Variables de razas para JS (rellena el select de raza dinámicamente) -->
<script>
    const razasPerro = <?= json_encode($datos['razasPerro'] ?? []) ?>;
    const razasGato = <?= json_encode($datos['razasGato'] ?? []) ?>;
</script>
<!-- Script personalizado para la lógica del formulario -->
<script src="<?= RUTA_URL ?>/js/mascotas/agregar.js"></script>

<?php
// Incluye el footer común de la aplicación
require RUTA_APP . '/views/inc/footer.php';
?>