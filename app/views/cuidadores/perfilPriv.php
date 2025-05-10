<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5 perfil-cuidador-contenedor">
    <h2 class="section-title">Mi Perfil</h2>

    <div class="row align-items-start flex-column flex-md-row">
        <div class="col-md-4 text-center">
            <img src="<?= RUTA_URL . '/' . $datos['datos']->imagen ?>" alt="Imagen de perfil"
                class="perfil-cuidador-imagen shadow-sm mb-3">
        </div>
        <div class="col-md-8 perfil-cuidador-info">
            <ul class="list-unstyled">
                <li><strong><?= getIcono('star') ?> Nombre:</strong> <?= htmlspecialchars($datos['datos']->nombre) ?></li>
                <li><strong><?= getIcono('email') ?> Email:</strong> <?= htmlspecialchars($datos['datos']->email) ?></li>
                <li><strong><?= getIcono('phone') ?> Teléfono:</strong> <?= htmlspecialchars($datos['datos']->telefono) ?></li>
                <li><strong><?= getIcono('location') ?> Dirección:</strong>
                    <?= htmlspecialchars($datos['datos']->direccion) ?>,
                    <?= htmlspecialchars($datos['datos']->ciudad) ?>,
                    <?= htmlspecialchars($datos['datos']->pais) ?>
                </li>
                <li><strong><i class="bi bi-people-fill text-success"></i> Máx. Mascotas al día:</strong>
                    <?= htmlspecialchars($datos['datos']->max_mascotas_dia) ?>
                </li>
                <li><strong><i class="bi bi-calendar-check text-muted"></i> Fecha de registro:</strong>
                    <?= date('d/m/Y', strtotime($datos['datos']->fecha_registro)) ?>
                </li>
            </ul>
            <div class="mt-3 perfil-cuidador-botones">
                <a href="<?= RUTA_URL ?>/cuidadores/editarAccesos" class="btn btn-outline-primary mt-2 me-2">Editar Accesos</a>
                <a href="<?= RUTA_URL ?>/cuidadores/editarDatosCuidador" class="btn btn-outline-primary mt-2 me-2">Editar Datos Cuidador</a>
                <a href="<?= RUTA_URL ?>/cuidadores/editarServicios" class="btn btn-outline-primary mt-2">Editar Servicios</a>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <h5 class="seccion-subtitulo">Servicios ofrecidos</h5>
    <?php if (!empty($datos['servicios'])): ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($datos['servicios'] as $s): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($s->servicio) ?>
                    <span class="servicio-precio"><?= number_format($s->precio, 2) ?> €</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted">No se han configurado servicios.</p>
    <?php endif; ?>

    <h5 class="seccion-subtitulo">Mascotas admitidas</h5>
    <?php if (!empty($datos['admite'])): ?>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($datos['admite'] as $a): ?>
                <span class="badge-acepta">
                    <?= getIcono($a->tipo_mascota) ?> <?= ucfirst($a->tipo_mascota) ?> - <?= ucfirst($a->tamano) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">No se han definido mascotas admitidas.</p>
    <?php endif; ?>
</div>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>