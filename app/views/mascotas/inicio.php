<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <h2 class="section-title">Mis mascotas</h2>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= RUTA_URL ?>/mascotas/agregarMascota" class="btn btn-success">Añadir mascota</a>
    </div>

    <?php if (empty($datos['mascotas'])): ?>
        <p>No tienes mascotas registradas.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($datos['mascotas'] as $idx => $m):
                // Extraemos sólo la URL de cada imagen
                $urls = array_map(fn($img) => $img->imagen, $m->imagenes);
                $thumb = $urls[0] ?? '';
                $thumbSrc = (str_starts_with($thumb, 'http') || str_starts_with($thumb, '//'))
                    ? $thumb
                    : RUTA_URL . '/' . ltrim($thumb, '/');
            ?>
                <div class="col-8 col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <?php if ($thumb): ?>
                            <img
                                src="<?= htmlspecialchars($thumbSrc) ?>"
                                class="card-img-top mascota-thumb"
                                style="cursor:pointer;"
                                data-mascota-index="<?= $idx ?>"
                                data-img-index="0"
                                alt="Foto de <?= htmlspecialchars($m->nombre) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($m->nombre) ?></h5>
                            <p class="card-text overflow-auto"><strong>Tipo:</strong> <?= $m->tipo ?><br>
                                <strong>Raza:</strong> <?= $m->raza ?><br>
                                <strong>Edad:</strong> <?= $m->edad ?> años<br>
                                <strong>Tamaño:</strong> <?= $m->tamano ?><br>
                                <?php if ($m->observaciones): ?>
                                    <strong>Obs.:</strong> <?= htmlspecialchars($m->observaciones) ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?= RUTA_URL ?>/mascotas/editarMascotas/<?= $m->id ?>" class="btn btn-primary">
                            Editar
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminacionModal" data-id="<?= $m->id ?>">
                            Eliminar
                        </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if (isset($errores)): ?>
    <p class="text-danger"><?php echo $errores; ?></p>
<?php endif; ?>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres eliminar esta mascota?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<!-- Visor overlay -->
<div id="visorImagen" class="visor-overlay">
    <span class="visor-cerrar" onclick="cerrarVisor()">×</span>
    <button class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
    <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
    <button class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
</div>
<script>
    window.RUTA_URL = "<?= RUTA_URL ?>";
    window.galeriasMascotas = <?= json_encode(array_map(
                                    fn($m) => array_map(function ($img) {
                                        return (str_starts_with($img->imagen, 'http') || str_starts_with($img->imagen, '//'))
                                            ? $img->imagen
                                            : RUTA_URL . '/' . ltrim($img->imagen, '/');
                                    }, $m->imagenes),
                                    $datos['mascotas']
                                ), JSON_UNESCAPED_SLASHES) ?>;
</script>
<script src="<?= RUTA_URL ?>/js/mascotas/inicio.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>