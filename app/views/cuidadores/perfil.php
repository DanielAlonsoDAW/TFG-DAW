<?php
require RUTA_APP . '/views/inc/header.php';
?>

<div class="container my-5">
    <div class="row g-5">
        <!-- Columna izquierda: Imagen y datos básicos del cuidador -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <!-- Imagen del cuidador -->
                <img src="<?= RUTA_URL . '/' . $datos['cuidador']->imagen ?>" class="img-fluid rounded shadow-sm cuidador-img-responsive mb-3" alt="Imagen cuidador">
                <div class="card-body">
                    <!-- Nombre del cuidador -->
                    <h4 class="text-primary-custom"><?= $datos['cuidador']->nombre ?></h4>
                    <!-- Ciudad y país -->
                    <p><strong><?= getIcono("location") ?> Ciudad:</strong> <?= $datos['cuidador']->ciudad ?> (<?= $datos['cuidador']->pais ?>)</p>
                    <!-- Valoración promedio -->
                    <p><strong><?= getIcono("star") ?> Valoración:</strong> <?= estrellasBootstrap($datos['cuidador']->promedio_calificacion) ?> (<?= number_format($datos['cuidador']->promedio_calificacion, 1) ?>/5)</p>
                    <!-- Botón para reservar -->
                    <div class="text-center mt-4">
                        <a href="<?= RUTA_URL ?>/reservas/crear/<?= $datos['cuidador']->id ?>" class="btn btn-primary mt-4">Reservar ahora</a>
                    </div>

                    <!-- Calendario de disponibilidad -->
                    <h4 class="text-primary-custom text-center mt-4">Disponibilidad</h4>
                    <div id="calendarioDisponibilidad" class="my-2" data-reservas='<?= json_encode($datos["reservas"]) ?>'
                        data-max-mascotas='<?= $datos["cuidador"]->max_mascotas_dia ?>'></div>
                    <!-- Leyenda del calendario -->
                    <div class="m-3 d-flex gap-3 align-items-center flex-wrap">
                        <span class="badge disponible">Disponible</span>
                        <span class="badge no-disponible">No disponible</span>
                        <span class="badge parcialmente-ocupado">Parcialmente ocupado</span>
                        <span class="badge lleno">Lleno</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna derecha: información detallada -->
        <div class="col-12 col-lg-8">
            <div class="info-section mb-4">
                <!-- Descripción personal del cuidador -->
                <h4>Sobre mí</h4>
                <p><?= nl2br($datos['cuidador']->descripcion) ?></p>
                <!-- Mascotas propias del cuidador -->
                <?php if (!empty($datos['mascotas'])): ?>
                    <h4>Mis Mascotas</h4>
                    <div class="row">
                        <?php foreach ($datos['mascotas'] as $mascota): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card custom-card shadow-sm h-100">
                                    <?php if ($mascota->imagen): ?>
                                        <img src="<?= $mascota->imagen ?>" class="card-img-top img-fluid" alt="Imagen de mascota">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= ucfirst($mascota->nombre) ?></h5>
                                        <p class="card-text overflow-auto mb-2">
                                            <strong>Raza:</strong> <?= $mascota->raza ?><br>
                                            <strong>Edad:</strong> <?= $mascota->edad ?> años<br>
                                            <strong>Tamaño:</strong> <?= ucfirst($mascota->tamano) ?> <?= getClasificacionTamano($mascota->tipo, $mascota->tamano) ?><br>
                                            <?php if ($mascota->observaciones): ?>
                                                <strong>Observaciones:</strong> <?= nl2br($mascota->observaciones) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Servicios ofrecidos por el cuidador -->
            <div class="info-section mb-4">
                <h4>Servicios ofrecidos</h4>
                <ul class="list-group list-group-flush">
                    <?php foreach ($datos['servicios'] as $s): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($s->servicio) ?>
                            <span class="servicio-precio"><?= $s->precio ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Tipos de mascotas aceptadas y cantidad máxima -->
            <div class="info-section mb-4">
                <h4>Acepta</h4>
                <ul class="list-inline">
                    <li class="my-2">Máximo <?= $datos["cuidador"]->max_mascotas_dia ?> mascotas al día</li>
                    <?php foreach ($datos['admite'] as $a): ?>
                        <li class="list-inline-item badge badge-acepta m-1">
                            <?= getIcono($a->tipo_mascota) ?>
                            <?= ucfirst($a->tipo_mascota) ?>
                            <?= $a->tamano ?> <?= getClasificacionTamano($a->tipo_mascota, $a->tamano) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Sección de reseñas de clientes -->
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
        </div>
    </div>
</div>

<!-- Scripts para el calendario de disponibilidad -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
<script src="<?= RUTA_URL ?>/js/cuidadores/calendario.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>