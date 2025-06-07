<?php require RUTA_APP . '/views/inc/header.php'; ?>
<!-- Contenedor principal -->
<div class="container py-5">
    <div class="formulario-container col-md-8 col-lg-6">
        <h3 class="text-center mb-4">Crear Reseña</h3>

        <!-- Resumen contextual de la reserva -->
        <div class="mb-4 p-3 bg-white rounded shadow-sm">
            <!-- Nombre del cuidador -->
            <p class="mb-1">
                <strong>Cuidador:</strong> <?= htmlspecialchars($datos['reserva']->cuidador_nombre) ?>
            </p>
            <!-- Tipo de servicio -->
            <p class="mb-1">
                <strong>Servicio:</strong> <?= htmlspecialchars($datos['reserva']->servicio) ?>
            </p>
            <!-- Fechas de la reserva -->
            <p class="mb-0">
                <strong>Fechas:</strong>
                <?= date('d/m/Y', strtotime($datos['reserva']->fecha_inicio)) ?>
                a
                <?= date('d/m/Y', strtotime($datos['reserva']->fecha_fin)) ?>
            </p>
        </div>

        <!-- Formulario para crear la reseña -->
        <form method="POST" action="" novalidate>
            <!-- Calificación con estrellas -->
            <div class="mb-4">
                <label class="form-label d-block">Valoración</label>
                <div id="rating-stars" class="d-flex gap-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <!-- Estrella activa o inactiva según la calificación -->
                        <i class="bi <?= ($datos['calificacion'] >= $i) ? 'bi-star-fill' : 'bi-star' ?> text-warning"
                            data-value="<?= $i ?>"></i>
                    <?php endfor; ?>
                </div>
                <!-- Input oculto para almacenar la calificación seleccionada -->
                <input type="hidden" name="calificacion" id="calificacion" value="<?= htmlspecialchars($datos['calificacion']) ?>">

                <!-- Mensaje de error para la calificación -->
                <?php if (!empty($datos['errores']['calificacion'])): ?>
                    <div class="text-danger mt-2" style="font-size: 0.95rem;">
                        <?= $datos['errores']['calificacion'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Campo para el comentario -->
            <div class="mb-4">
                <label for="comentario" class="form-label">Comentario</label>
                <textarea class="form-control <?= !empty($datos['errores']['comentario']) ? 'is-invalid' : '' ?>" id="comentario" name="comentario" rows="4" placeholder="Escribe tu opinión sobre el cuidador..."><?= htmlspecialchars($datos['comentario']) ?></textarea>
                <!-- Mensaje de error para el comentario -->
                <div class="invalid-feedback">
                    <?= $datos['errores']['comentario'] ?>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="col-12 d-flex justify-content-center gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="<?= RUTA_URL ?>/duenos/misReservas" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<!-- Script para la funcionalidad de las reseñas -->
<script src="<?= RUTA_URL ?>/js/duenos/resenas.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>