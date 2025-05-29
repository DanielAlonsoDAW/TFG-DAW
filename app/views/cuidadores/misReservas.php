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
                        <th>Facturas</th>
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
                                    class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#mascotasModal<?= $reserva->id ?>">
                                    Ver Mascotas
                                </button>
                                <!-- Modal -->
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
                            <td><span class="badge bg-success"><?= ucfirst($reserva->estado) ?></span></td>
                            <td><?= number_format($reserva->total, 2) ?>€</td>
                            <td class="text-center"><a href="<?= RUTA_URL ?>/reservas/factura/<?= $reserva->id ?>" class="btn btn-primary btn-sm" target="_blank">Ver Factura</a></td>
                            <td class="text-center">
                                <?php if ($reserva->estado === 'confirmada'): ?>
                                    <form action="<?= RUTA_URL ?>/reservas/cancelar/<?= $reserva->id ?>" method="post">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres cancelar esta reserva?');">Cancelar</button>
                                    </form>
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

<!-- Script para galería de imagenes de mascotas -->
<script src="<?= RUTA_URL ?>/js/cuidadores/reservas.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>