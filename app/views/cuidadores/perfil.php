<?php
require RUTA_APP . '/views/inc/header.php';
require RUTA_APP . "/librerias/FuncionesFormulario.php";
?>

<div class="container my-5">
    <div class="row g-5">
        <!-- Columna izquierda: Imagen y datos básicos -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="<?= RUTA_URL . '/' . $datos['cuidador']->imagen ?>" class="img-fluid rounded shadow-sm cuidador-img-responsive mb-3" alt="Imagen cuidador">
                <div class="card-body">
                    <h4 class="text-primary-custom"><?= $datos['cuidador']->nombre ?></h4>
                    <p><strong>📍 Ciudad:</strong> <?= $datos['cuidador']->ciudad ?> (<?= $datos['cuidador']->pais ?>)</p>
                    <p><strong>📞 Teléfono:</strong> <?= $datos['cuidador']->telefono ?></p>
                    <p><strong>✉️ Email:</strong> <?= $datos['cuidador']->email ?></p>
                    <p><strong>⭐ Valoración:</strong> <?= estrellasBootstrap($datos['cuidador']->promedio_calificacion) ?> (<?= number_format($datos['cuidador']->promedio_calificacion, 1) ?>/5)</p>
                </div>
            </div>
        </div>

        <!-- Columna derecha: info completa -->
        <div class="col-md-8">
            <div class="info-section mb-4">
                <h4>Sobre mí</h4>
                <p><?= nl2br($datos['cuidador']->descripcion) ?></p>
            </div>

            <div class="info-section mb-4">
                <h4>Servicios ofrecidos</h4>
                <ul class="list-group list-group-flush">
                    <?php foreach ($datos['servicios'] as $serv): ?>
                        <?php if (strtolower($serv->servicio) === 'taxi'): ?>
                            <li class="list-group-item"><?= $serv->servicio ?> – 10€ + <?= number_format($serv->precio, 2) ?>€/km</li>
                        <?php else: ?>
                            <li class="list-group-item"><?= $serv->servicio ?> – <?= number_format($serv->precio, 2) ?>€</li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="info-section mb-4">
                <h4>Acepta</h4>
                <ul class="list-inline">
                    <?php foreach ($datos['admite'] as $a): ?>
                        <li class="list-inline-item badge bg-info text-dark m-1"><?= ucfirst($a->tipo_mascota) ?> (<?= $a->tamano ?>)</li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="info-section mb-4">
                <h4>Reseñas</h4>
                <?php if (!empty($datos['resenas'])): ?>
                    <?php foreach ($datos['resenas'] as $res): ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <strong><?= $res->duenio ?></strong> – <?= date('d/m/Y', strtotime($res->fecha_resena)) ?><br>
                            <?= estrellasBootstrap($res->calificacion) ?>
                            <p class="mt-2"><?= nl2br($res->comentario) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Aún no hay reseñas disponibles.</p>
                <?php endif; ?>
            </div>

            <div class="text-end mt-4">
                <a href="<?= RUTA_URL ?>/reservas/crear/<?= $datos['cuidador']->id ?>" class="btn btn-perfil px-4 py-2">Reservar ahora</a>
            </div>
        </div>
    </div>
</div>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>