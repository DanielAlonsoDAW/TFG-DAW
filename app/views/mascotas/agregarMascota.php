<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2 class="section-title">Añadir Nueva Mascota</h2>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <form id="formAgregarMascota" method="POST" enctype="multipart/form-data" novalidate>
            <?php
            $e  = $datos['errores']  ?? [];
            $in = $datos['entrada'] ?? [];
            ?>

            <!-- Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control <?= !empty($e['nombre']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($in['nombre'] ?? '') ?>">
                <span class="text-danger"><?= $e['nombre'] ?? '' ?></span>
            </div>

            <!-- Tipo -->
            <div class="mb-3">
                <label class="form-label">Tipo</label><br>
                <input type="radio" class="btn-check" name="tipo" id="btn-perro" value="perro" <?= ($in['tipo'] ?? '') === 'perro' ? 'checked' : '' ?>>
                <label class="btn btn-perro me-2" for="btn-perro"><i class="fa-solid fa-dog"></i> Perro</label>

                <input type="radio" class="btn-check" name="tipo" id="btn-gato" value="gato" <?= ($in['tipo'] ?? '') === 'gato' ? 'checked' : '' ?>>
                <label class="btn btn-gato" for="btn-gato"><i class="fa-solid fa-cat"></i> Gato</label>
                <span class="text-danger"><?= $e['Tipo'] ?? '' ?></span>
            </div>

            <!-- Raza -->
            <div class="mb-3">
                <label for="raza" class="form-label">Raza:</label>
                <select id="raza" name="raza" data-valor-previo="<?= htmlspecialchars($in['raza'] ?? '') ?>"
                    class="form-select <?= !empty($e['raza']) ? 'is-invalid' : '' ?>">
                    <option value="" disabled <?= empty($in['raza']) ? 'selected' : '' ?>>Elige una raza</option>
                </select>
                <span class="text-danger"><?= $e['raza'] ?? '' ?></span>
            </div>


            <!-- Edad -->
            <div class="mb-3">
                <label for="edad" class="form-label">Edad (años):</label>
                <input type="number" id="edad" name="edad" min="0" class="form-control <?= !empty($e['edad']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($in['edad'] ?? '') ?>">
                <span class="text-danger"><?= $e['edad'] ?? '' ?></span>
            </div>

            <!-- Tamaño -->
            <div class="mb-3">
                <label for="tamano" class="form-label">Tamaño:</label>
                <input type="text" id="tamano" name="tamano" class="form-control <?= !empty($e['tamano']) ? 'is-invalid' : '' ?>"
                    readonly value="<?= htmlspecialchars($in['tamano'] ?? '') ?>">
                <span class="text-danger"><?= $e['tamano'] ?? '' ?></span>
            </div>

            <!-- Observaciones -->
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones:</label>
                <textarea id="observaciones" name="observaciones" class="form-control"><?= htmlspecialchars($in['observaciones'] ?? '') ?></textarea>
            </div>

            <!-- Imágenes -->
            <div class="mb-3">
                <label for="imagenes" class="form-label">Imágenes:</label>
                <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*" class="form-control <?= !empty($e['imagenes']) ? 'is-invalid' : '' ?>">
                <span class="text-danger"><?= $e['imagenes'] ?? '' ?></span>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Guardar Mascota</button>
            </div>
        </form>
    </div>
</div>

<script>
    const razasPerro = <?= json_encode($datos['razasPerro'] ?? []) ?>;
    const razasGato = <?= json_encode($datos['razasGato'] ?? []) ?>;
</script>
<script src="<?= RUTA_URL ?>/js/mascotas/agregar.js"></script>


<?php require RUTA_APP . '/views/inc/footer.php'; ?>