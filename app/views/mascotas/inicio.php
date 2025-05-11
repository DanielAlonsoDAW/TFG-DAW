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
            ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm">
                        <?php if ($thumb): ?>
                            <img
                                src="<?= RUTA_URL . '/' . htmlspecialchars($thumb) ?>"
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
                        <a href="<?= RUTA_URL ?>/mascotas/editar/<?= $m->id ?>" class="btn btn-primary">
                            Editar
                        </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Visor overlay -->
<div id="visorImagen" class="visor-overlay">
    <span class="visor-cerrar" onclick="cerrarVisor()">×</span>
    <button class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
    <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
    <button class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
</div>
<script>
    window.galeriasMascotas = <?= json_encode(array_map(
                                    fn($m) =>
                                    array_map(fn($img) => RUTA_URL . '/' . ltrim($img->imagen, '/'), $m->imagenes),
                                    $datos['mascotas']
                                ), JSON_UNESCAPED_SLASHES) ?>;
</script>

<script src="<?= RUTA_URL ?>/js/mascotas/inicio.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>