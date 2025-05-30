<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <h2 class="section-title">Mis Reservas</h2>

    <?php if (empty($datos['reservas'])): ?>
        <div class="alert alert-info">No tienes reservas registradas.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Cuidador</th>
                        <th>Servicio</th>
                        <th>Fechas</th>
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
                            <td><?= ucfirst($reserva->estado) ?></td>
                            <td><?= number_format($reserva->total, 2) ?>€</td>
                            <td class="text-center"><a href="<?= RUTA_URL ?>/reservas/factura/<?= $reserva->id ?>" class="btn btn-primary reserva-primary btn-sm" target="_blank">Ver Factura</a></td>
                            <td class="text-center">
                                <?php if ($reserva->estado === 'confirmada'): ?>
                                    <!-- Botón para cancelar la reserva -->
                                    <button type="button" class="btn btn-danger reserva-danger" data-bs-toggle="modal" data-bs-target="#confirmarEliminacionModal" data-id="<?= $reserva->id ?>">
                                        Cancelar Reserva
                                    </button>
                                <?php endif; ?>
                                <!-- TODO : Añadir Botón de añadir/editar reseña cuando el estado sea Finalizada -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirmarEliminacionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="modalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que quieres cancelar la reserva?<br><br>

                Unicamente podrás cancelar reservas con al menos 2 días de antelación.

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Volver</button>
                <!-- Enlace para confirmar la eliminación (se actualiza dinámicamente con JS) -->
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">Cancelar Reserva</a>
            </div>
        </div>
    </div>
</div>

<!-- Variable global con la ruta base de la aplicación -->
<script>
    const RUTA_URL = "<?= RUTA_URL ?>";
</script>

<!-- Script para galería de imagenes de mascotas -->
<script src="<?= RUTA_URL ?>/js/duenos/reservas.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>