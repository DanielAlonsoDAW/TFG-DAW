<?php require RUTA_APP . '/views/inc/header.php';

$mascota = $datos['mascota'];
$errores = $datos['errores'];
$razasPerro = $datos['razasPerro'];
$razasGato = $datos['razasGato'];
$imagenes = $datos['imagenes'];
?>

<div class="container my-5">
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <h3 class="section-title">Editar Mascota</h3>
        <form id="formEditarMascota" action="<?= RUTA_URL ?>/mascotas/editarMascotas/<?= $mascota->id ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $mascota->id ?>">

            <!-- Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control <?= !empty($errores['nombre']) ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= htmlspecialchars($mascota->nombre) ?>" required>
                <span class="text-danger"><?= $errores['nombre'] ?? '' ?></span>
            </div>

            <!-- Tipo -->
            <div class="mb-3">
                <label class="form-label">Tipo</label><br>
                <input type="radio" class="btn-check" name="tipo" id="btn-perro" value="perro" <?= $mascota->tipo == 'perro' ? 'checked' : '' ?>>
                <label class="btn btn-perro me-2" for="btn-perro"><i class="fa-solid fa-dog"></i> Perro</label>

                <input type="radio" class="btn-check" name="tipo" id="btn-gato" value="gato" <?= $mascota->tipo == 'gato' ? 'checked' : '' ?>>
                <label class="btn btn-gato" for="btn-gato"><i class="fa-solid fa-cat"></i> Gato</label>

                <span class="text-danger"><?= $errores['tipo'] ?? '' ?></span>
            </div>

            <!-- Raza -->
            <div class="mb-3">
                <label for="raza" class="form-label">Raza:</label>
                <select id="raza" name="raza"
                    data-valor-previo="<?= htmlspecialchars($mascota->raza ?? '') ?>"
                    class="form-select <?= !empty($errores['raza']) ? 'is-invalid' : '' ?>">
                </select>
                <span class="text-danger"><?= $errores['raza'] ?? '' ?></span>
            </div>

            <!-- Edad -->
            <div class="mb-3">
                <label for="edad" class="form-label">Edad (años)</label>
                <input type="number" class="form-control <?= !empty($errores['edad']) ? 'is-invalid' : '' ?>" id="edad" name="edad" value="<?= htmlspecialchars($mascota->edad) ?>" min="0" required>
                <span class="text-danger"><?= $errores['edad'] ?? '' ?></span>
            </div>

            <!-- Tamaño -->
            <div class="mb-3">
                <label for="tamano" class="form-label">Tamaño:</label>
                <input type="text" id="tamano" name="tamano"
                    class="form-control <?= !empty($errores['tamano']) ? 'is-invalid' : '' ?>"
                    readonly
                    value="<?= htmlspecialchars($mascota->tamano ?? '') ?>">
                <span class="text-danger"><?= $errores['tamano'] ?? '' ?></span>
            </div>

            <!-- Observaciones -->
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= htmlspecialchars($mascota->observaciones) ?></textarea>
            </div>

            <!-- Galería de imágenes -->
            <div class="mb-3">
                <label class="form-label">Imágenes actuales</label>
                <div class="scroll-carousel">
                    <?php foreach ($imagenes as $imagen): ?>
                        <?php
                        $src = (str_starts_with($imagen->imagen, 'http') || str_starts_with($imagen->imagen, '//'))
                            ? $imagen->imagen
                            : RUTA_URL . '/' . ltrim($imagen->imagen, '/');
                        ?>
                        <img src="<?= $src ?>" alt="Imagen mascota" class="imagen-miniatura">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Visor overlay -->
            <div id="visorImagen" class="visor-overlay">
                <span class="visor-cerrar" onclick="cerrarVisor()">×</span>
                <button type="button" class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
                <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
                <button type="button" class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
            </div>

            <!-- Nuevas imágenes -->
            <div class="mb-3">
                <label for="nuevas_imagenes" class="form-label">Subir nuevas imágenes</label>
                <input type="file" class="form-control" name="nuevas_imagenes[]" multiple accept="image/*">
                <small class="form-text text-muted">Puedes seleccionar varias imágenes</small>
                <span class="text-danger"><?= $errores['imagenes'] ?? '' ?></span>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    const razasPerro = <?= json_encode($datos['razasPerro'] ?? []) ?>;
    const razasGato = <?= json_encode($datos['razasGato'] ?? []) ?>;
</script>

<script src="<?= RUTA_URL ?>/js/mascotas/editar.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>