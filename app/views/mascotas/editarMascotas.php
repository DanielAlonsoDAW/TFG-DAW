<?php require RUTA_APP . '/views/inc/header.php'; ?>
<div class="container my-5">
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <h3 class="section-title">Editar Mascota</h3>
        <form action="<?= RUTA_URL ?>/mascotas/editarMascotas/<?= $datos['mascota']->id ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $datos['mascota']->id ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($datos['mascota']->nombre) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label><br>
                <input type="radio" class="btn-check" name="tipo" id="btn-perro" value="perro" <?= $datos['mascota']->tipo == 'perro' ? 'checked' : '' ?>>
                <label class="btn btn-perro me-2" for="btn-perro"><i class="fa-solid fa-dog"></i> Perro</label>

                <input type="radio" class="btn-check" name="tipo" id="btn-gato" value="gato" <?= $datos['mascota']->tipo == 'gato' ? 'checked' : '' ?>>
                <label class="btn btn-gato" for="btn-gato"><i class="fa-solid fa-cat"></i> Gato</label>
            </div>

            <div class="mb-3">
                <label for="raza" class="form-label">Raza</label>
                <input type="text" class="form-control" id="raza" name="raza" value="<?= htmlspecialchars($datos['mascota']->raza) ?>" required>
            </div>

            <div class="mb-3">
                <label for="edad" class="form-label">Edad (años)</label>
                <input type="number" class="form-control" id="edad" name="edad" value="<?= $datos['mascota']->edad ?>" min="0" required>
            </div>

            <div class="mb-3">
                <label for="tamano" class="form-label">Tamaño</label>
                <select class="form-select" id="tamano" name="tamano" required>
                    <option value="pequeño" <?= $datos['mascota']->tamano == 'pequeño' ? 'selected' : '' ?>>Pequeño</option>
                    <option value="mediano" <?= $datos['mascota']->tamano == 'mediano' ? 'selected' : '' ?>>Mediano</option>
                    <option value="grande" <?= $datos['mascota']->tamano == 'grande' ? 'selected' : '' ?>>Grande</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= htmlspecialchars($datos['mascota']->observaciones) ?></textarea>
            </div>

            <!-- Galería de imágenes -->
            <div class="mb-3">
                <label class="form-label">Imágenes actuales</label>
                <div class="scroll-carousel">
                    <?php foreach ($datos['imagenes'] as $imagen): ?>
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
                <button class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
                <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
                <button class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
            </div>

            <!-- Opción para subir nuevas imágenes -->
            <div class="mb-3">
                <label for="nuevas_imagenes" class="form-label">Subir nuevas imágenes</label>
                <input type="file" class="form-control" name="nuevas_imagenes[]" multiple accept="image/*">
                <small class="form-text text-muted">Puedes seleccionar varias imágenes</small>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= RUTA_URL ?>/js/mascotas/editar.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>