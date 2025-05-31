<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <h2 class="section-title">Mis Reservas</h2>

    <?php if (empty($datos['reservas'])): ?>
        <div class="alert alert-info">No tienes reservas registradas.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover custom-card">
                <thead class="table-light">
                    <tr>
                        <th>Dueño</th>
                        <th>Servicio</th>
                        <th>Fechas</th>
                        <th>Nº Mascotas</th>
                        <th>Ver Mascotas</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['reservas'] as $reserva): ?>
                        <tr>
                            <td><?= htmlspecialchars($reserva->cuidador_nombre) ?></td>
                            <td><?= htmlspecialchars($reserva->servicio) ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva->fecha_inicio)) ?> - <?= date('d/m/Y', strtotime($reserva->fecha_fin)) ?></td>
                            <td class="text-center"><?= isset($reserva->numero_mascotas) ? (int)$reserva->numero_mascotas : 0 ?></td>
                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn btn-secondary-custom btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#mascotasModal<?= $reserva->id ?>">
                                    Ver Mascotas
                                </button>
                                <!-- Modal ver mascotas -->
                                <div class="modal fade" id="mascotasModal<?= $reserva->id ?>" tabindex="-1" aria-labelledby="mascotasModalLabel<?= $reserva->id ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-xl modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title w-100 text-center text-primary-custom" id="mascotasModalLabel<?= $reserva->id ?>">Mascotas de la Reserva</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php if (!empty($reserva->mascotas) && is_array($reserva->mascotas)): ?>
                                                    <div class="row">
                                                        <?php foreach ($reserva->mascotas as $idx => $mascota): ?>
                                                            <?php
                                                            // Armar rutas completas de las imágenes
                                                            $imagenesMascota = array_map(function ($img) {
                                                                return (str_starts_with($img->imagen, 'http') || str_starts_with($img->imagen, '//'))
                                                                    ? $img->imagen
                                                                    : RUTA_URL . '/' . ltrim($img->imagen, '/');
                                                            }, $mascota->imagenes);
                                                            $thumb = $imagenesMascota[0] ?? null;
                                                            ?>
                                                            <div class="col-8 col-md-6 col-lg-4 mb-4">
                                                                <div class="card shadow-sm custom-card">
                                                                    <?php if ($thumb): ?>
                                                                        <img
                                                                            src="<?= htmlspecialchars($thumb) ?>"
                                                                            class="card-img-top mascota-thumb imagen-miniatura"
                                                                            data-id-mascota="<?= $mascota->id ?>"
                                                                            alt="Foto de <?= htmlspecialchars($mascota->nombre) ?>">
                                                                        <?php foreach ($imagenesMascota as $i => $src): ?>
                                                                            <?php if ($i === 0) continue; // Oculta el resto, solo los usará el visor 
                                                                            ?>
                                                                            <img
                                                                                src="<?= htmlspecialchars($src) ?>"
                                                                                class="imagen-miniatura d-none"
                                                                                data-id-mascota="<?= $mascota->id ?>"
                                                                                alt="Imagen de <?= htmlspecialchars($mascota->nombre) ?>">
                                                                        <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                        <div class="bg-secondary text-white rounded-top d-flex align-items-center justify-content-center" style="height:150px;">
                                                                            Sin foto
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <div class="card-body text-start">
                                                                        <h5 class="card-title text-primary-custom"><?= htmlspecialchars($mascota->nombre) ?></h5>
                                                                        <p class="card-text overflow-auto">
                                                                            <strong>Tipo:</strong> <?= htmlspecialchars($mascota->tipo) ?><br>
                                                                            <strong>Raza:</strong> <?= htmlspecialchars($mascota->raza ?? 'No especificada') ?><br>
                                                                            <strong>Edad:</strong> <?= htmlspecialchars($mascota->edad ?? '-') ?> años<br>
                                                                            <strong>Tamaño:</strong> <?= htmlspecialchars($mascota->tamano ?? '-') ?><br>
                                                                            <?php if (!empty($mascota->observaciones)): ?>
                                                                                <strong>Observaciones:</strong> <?= htmlspecialchars($mascota->observaciones) ?>
                                                                            <?php endif; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="alert alert-warning">No hay mascotas asociadas a esta reserva.</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?= ucfirst($reserva->estado) ?></td>
                            <td><?= number_format($reserva->total, 2) ?>€</td>
                            <td class="text-center">
                                <?php
                                $hoy = date('Y-m-d');
                                $fechaInicio = $reserva->fecha_inicio;
                                $fechaFin = $reserva->fecha_fin;
                                $diffFechas = calcularDiferenciaFechas($hoy, $fechaInicio);
                                ?>
                                <?php if ($reserva->estado === 'confirmada'): ?>
                                    <?php if ($diffFechas >= 2): ?>
                                        <!-- Botón para cancelar la reserva -->
                                        <button type="button" class="btn btn-danger reserva-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminacionModal" data-id="<?= $reserva->id ?>">
                                            Rechazar Reserva
                                        </button>
                                    <?php elseif ($diffFechas == 0 || $diffFechas == -1 && $hoy < $fechaFin): ?>
                                        <!-- Celda vacía: no mostrar nada -->
                                    <?php elseif ($hoy >= $fechaFin): ?>
                                        <!-- Botón para completar la reserva -->
                                        <form action="<?= RUTA_URL ?>/reservas/completar/<?= $reserva->id ?>" method="post">
                                            <button type="submit" class="btn btn-primary reserva-primary btn-sm">Completar Reserva</button>
                                        </form>
                                    <?php endif; ?>
                                <?php elseif ($reserva->estado === 'completada' && !empty($datos['resenas'])): ?>
                                    <?php
                                    // Buscar si existe reseña para esta reserva
                                    $resena_existente = null;
                                    foreach ($datos['resenas'] as $resena) {
                                        if (isset($resena->id) && $resena->reserva_id === $reserva->id) {
                                            $resena_existente = $resena;
                                            break;
                                        }
                                    }
                                    ?>
                                    <?php if ($resena_existente): ?>
                                        <!-- Botón para mostrar reseña -->
                                        <button type="button" class="btn btn-resena-modal btn-sm" data-bs-toggle="modal" data-bs-target="#resenaModal<?= $resena_existente->id ?>">
                                            Mostrar Reseña
                                        </button>
                                        <!-- Modal mostrar reseña -->
                                        <div class="modal fade" id="resenaModal<?= $resena_existente->id ?>" tabindex="-1" aria-labelledby="resenaModalLabel<?= $resena_existente->id ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title w-100 text-center" id="resenaModalLabel<?= $resena_existente->id ?>">Reseña del Dueño</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <strong>Dueño:</strong> <?= htmlspecialchars($resena_existente->duenio) ?><br>
                                                        <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($resena_existente->fecha_resena)) ?><br>
                                                        <strong>Valoración:</strong>
                                                        <?php for ($i = 0; $i < (int)$resena_existente->calificacion; $i++): ?>
                                                            <span class="text-warning">&#9733;</span>
                                                        <?php endfor; ?>
                                                        <?php for ($i = (int)$resena_existente->calificacion; $i < 5; $i++): ?>
                                                            <span class="text-secondary">&#9733;</span>
                                                        <?php endfor; ?>
                                                        <br>
                                                        <strong>Comentario:</strong>
                                                        <p><?= nl2br(htmlspecialchars($resena_existente->comentario)) ?></p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Visor de imágenes (galería ampliada) -->
<div id="visorImagen" class="visor-overlay" style="display: none;">
    <span class="visor-cerrar" onclick="cerrarVisor()">&times;</span>
    <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada">
    <button class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
    <button class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
</div>

<!-- Modal confirmación rechazo -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="modalLabel">Confirmar Rechazo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres rechazar la reserva?<br>

                Unicamente podrás rechazar reservas con al menos 2 días de antelación.

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Volver</button>
                <!-- Enlace para confirmar la eliminación (se actualiza dinámicamente con JS) -->
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Rechazar Reserva</a>
            </div>
        </div>
    </div>
</div>

<!-- Variable global con la ruta base de la aplicación -->
<script>
    const RUTA_URL = "<?= RUTA_URL ?>";
</script>

<!-- Script para galería de imagenes de mascotas -->
<script src="<?= RUTA_URL ?>/js/cuidadores/reservas.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>