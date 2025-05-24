<?php 
// Incluye el header común de la aplicación
require RUTA_APP . '/views/inc/header.php';

// Variables recibidas desde el controlador
$mascota = $datos['mascota'];
$errores = $datos['errores'];
$razasPerro = $datos['razasPerro'];
$razasGato = $datos['razasGato'];
$imagenes = $datos['imagenes'];
?>

<div class="container my-5">
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <h3 class="section-title">Editar Mascota</h3>
        <!-- Formulario para editar los datos de la mascota -->
        <form id="formEditarMascota" action="<?= RUTA_URL ?>/mascotas/editarMascotas/<?= $mascota->id ?>" method="POST" enctype="multipart/form-data">
            <!-- Campo oculto con el ID de la mascota -->
            <input type="hidden" name="id" value="<?= $mascota->id ?>">

            <!-- Campo para el nombre de la mascota -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control <?= !empty($errores['nombre']) ? 'is-invalid' : '' ?>" id="nombre" name="nombre" value="<?= htmlspecialchars($mascota->nombre) ?>" required>
                <span class="text-danger"><?= $errores['nombre'] ?? '' ?></span>
            </div>

            <!-- Selección del tipo de mascota (perro o gato) -->
            <div class="mb-3">
                <label class="form-label">Tipo</label><br>
                <input type="radio" class="btn-check" name="tipo" id="btn-perro" value="perro" <?= $mascota->tipo == 'perro' ? 'checked' : '' ?>>
                <label class="btn btn-perro me-2" for="btn-perro"><i class="fa-solid fa-dog"></i> Perro</label>

                <input type="radio" class="btn-check" name="tipo" id="btn-gato" value="gato" <?= $mascota->tipo == 'gato' ? 'checked' : '' ?>>
                <label class="btn btn-gato" for="btn-gato"><i class="fa-solid fa-cat"></i> Gato</label>

                <span class="text-danger"><?= $errores['tipo'] ?? '' ?></span>
            </div>

            <!-- Selección de la raza, se rellena dinámicamente según el tipo -->
            <div class="mb-3">
                <label for="raza" class="form-label">Raza:</label>
                <select id="raza" name="raza"
                    data-valor-previo="<?= htmlspecialchars($mascota->raza ?? '') ?>"
                    class="form-select <?= !empty($errores['raza']) ? 'is-invalid' : '' ?>">
                </select>
                <span class="text-danger"><?= $errores['raza'] ?? '' ?></span>
            </div>

            <!-- Campo para la edad de la mascota -->
            <div class="mb-3">
                <label for="edad" class="form-label">Edad (años)</label>
                <input type="number" class="form-control <?= !empty($errores['edad']) ? 'is-invalid' : '' ?>" id="edad" name="edad" value="<?= htmlspecialchars($mascota->edad) ?>" min="0" required>
                <span class="text-danger"><?= $errores['edad'] ?? '' ?></span>
            </div>

            <!-- Selección del tamaño de la mascota -->
            <div class="mb-3">
                <label class="form-label">Tamaño</label>
                <div id="tamano" class="<?= !empty($errores['tamano']) ? 'border border-danger text-danger' : '' ?>">
                    <span class="badge bg-primary p-2" id="tamano-badge">
                        <?= htmlspecialchars($mascota->tamano ?? '') ?>
                    </span>
                </div>
                <!-- Campo oculto para enviar el tamaño seleccionado -->
                <input type="hidden" id="tamano-hidden" name="tamano" value="<?= htmlspecialchars($mascota->tamano ?? '') ?>">
                <span class="text-danger"><?= $errores['tamano'] ?? '' ?></span>
            </div>

            <!-- Observaciones adicionales sobre la mascota -->
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= htmlspecialchars($mascota->observaciones) ?></textarea>
            </div>

            <!-- Galería de imágenes actuales de la mascota -->
            <div class="mb-3">
                <label class="form-label">Imágenes actuales</label>
                <div class="scroll-carousel">
                    <?php foreach ($imagenes as $imagen): ?>
                        <?php
                        // Determina si la imagen es una URL absoluta o relativa
                        $src = (str_starts_with($imagen->imagen, 'http') || str_starts_with($imagen->imagen, '//'))
                            ? $imagen->imagen
                            : RUTA_URL . '/' . ltrim($imagen->imagen, '/');
                        ?>
                        <img src="<?= $src ?>" alt="Imagen mascota" class="imagen-miniatura">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Visor de imágenes en overlay para ampliar -->
            <div id="visorImagen" class="visor-overlay">
                <span class="visor-cerrar" onclick="cerrarVisor()">×</span>
                <button type="button" class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
                <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
                <button type="button" class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
            </div>

            <!-- Campo para subir nuevas imágenes -->
            <div class="mb-3">
                <label for="nuevas_imagenes" class="form-label">Subir nuevas imágenes</label>
                <input type="file" class="form-control" name="nuevas_imagenes[]" multiple accept="image/*">
                <small class="form-text text-muted">Puedes seleccionar varias imágenes</small>
                <span class="text-danger"><?= $errores['imagenes'] ?? '' ?></span>
            </div>

            <!-- Botón para enviar el formulario -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Variables de razas para uso en JavaScript -->
<script>
    const razasPerro = <?= json_encode($datos['razasPerro'] ?? []) ?>;
    const razasGato = <?= json_encode($datos['razasGato'] ?? []) ?>;
</script>

<!-- Script específico para la edición de mascotas -->
<script src="<?= RUTA_URL ?>/js/mascotas/editar.js"></script>

<?php 
// Incluye el footer común de la aplicación
require RUTA_APP . '/views/inc/footer.php'; 
?>