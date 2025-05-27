<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-primary-custom">Mis Reservas</h2>

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
                        <th>Total (€)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos['reservas'] as $reserva): ?>
                        <tr>
                            <td><?= htmlspecialchars($reserva->cuidador_nombre) ?></td>
                            <td><?= htmlspecialchars($reserva->servicio) ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva->fecha_inicio)) ?> - <?= date('d/m/Y', strtotime($reserva->fecha_fin)) ?></td>
                            <td><span class="badge bg-success"><?= ucfirst($reserva->estado) ?></span></td>
                            <td><?= number_format($reserva->total, 2) ?>€</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>