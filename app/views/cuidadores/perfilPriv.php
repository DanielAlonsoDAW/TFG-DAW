<?php
// Incluye el header común de la aplicación
require RUTA_APP . '/views/inc/header.php';
?>

<div class="container mt-5 mb-5 perfil-contenedor">
    <!-- Título principal de la sección de perfil -->
    <h2 class="section-title">Mi Perfil</h2>

    <div class="row align-items-start flex-column flex-md-row">
        <!-- Columna de la imagen de perfil -->
        <div class="col-md-4 text-center">
            <!-- Muestra la imagen de perfil del cuidador -->
            <img src="<?= RUTA_URL . '/' . $datos['datos']->imagen ?>" alt="Imagen de perfil"
                class="perfil-imagen shadow-sm mb-3">
        </div>
        <!-- Columna de la información del perfil -->
        <div class="col-md-8 perfil-info">
            <ul class="list-unstyled">
                <!-- Nombre del cuidador -->
                <li>
                    <strong><i class="bi bi-person-fill text-dark"></i> Nombre:</strong>
                    <?= htmlspecialchars($datos['datos']->nombre) ?>
                </li>
                <!-- Email del cuidador -->
                <li>
                    <strong><?= getIcono('email') ?> Email:</strong>
                    <?= htmlspecialchars($datos['datos']->email) ?>
                </li>
                <!-- Dirección completa del cuidador -->
                <li>
                    <strong><?= getIcono('location') ?> Dirección:</strong>
                    <?= htmlspecialchars($datos['datos']->direccion) ?>,
                    <?= htmlspecialchars($datos['datos']->ciudad) ?>,
                    <?= htmlspecialchars($datos['datos']->pais) ?>
                </li>
                <!-- Máximo de mascotas que puede cuidar al día -->
                <li>
                    <strong><i class="bi bi-people-fill text-success"></i> Máx. Mascotas al día:</strong>
                    <?= htmlspecialchars($datos['datos']->max_mascotas_dia) ?>
                </li>
                <!-- Fecha de registro del cuidador -->
                <li>
                    <strong><i class="bi bi-calendar-check text-muted"></i> Fecha de registro:</strong>
                    <?= date('d/m/Y', strtotime($datos['datos']->fecha_registro)) ?>
                </li>
            </ul>
            <!-- Botones para editar información del perfil -->
            <div class="mt-3 perfil-botones">
                <!-- Enlace para editar accesos (email/contraseña) -->
                <a href="<?= RUTA_URL ?>/cuidadores/editarAccesos" class="btn btn-outline-primary mt-2 me-2">Editar Accesos</a>
                <!-- Enlace para editar datos personales -->
                <a href="<?= RUTA_URL ?>/cuidadores/editarDatos" class="btn btn-outline-primary mt-2 me-2">Editar Datos</a>
                <!-- Enlace para editar servicios ofrecidos -->
                <a href="<?= RUTA_URL ?>/cuidadores/editarServicios" class="btn btn-outline-primary mt-2">Editar Servicios</a>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Sección de servicios ofrecidos por el cuidador -->
    <h5 class="seccion-subtitulo">Servicios ofrecidos</h5>
    <?php if (!empty($datos['servicios'])): ?>
        <ul class="list-group list-group-flush">
            <?php foreach ($datos['servicios'] as $s): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <!-- Nombre del servicio -->
                    <?= htmlspecialchars($s->servicio) ?>
                    <!-- Precio del servicio -->
                    <span class="servicio-precio"><?= $s->precio ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <!-- Mensaje si no hay servicios configurados -->
        <p class="text-muted">No se han configurado servicios.</p>
    <?php endif; ?>

    <!-- Sección de mascotas admitidas por el cuidador -->
    <h5 class="seccion-subtitulo">Mascotas admitidas</h5>
    <?php if (!empty($datos['admite'])): ?>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($datos['admite'] as $a): ?>
                <span class="badge-acepta">
                    <!-- Icono y tamaño de la mascota admitida -->
                    <?= getIcono($a->tipo_mascota) ?> <?= ucfirst($a->tamano) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Mensaje si no hay mascotas admitidas -->
        <p class="text-muted">No se han definido mascotas admitidas.</p>
    <?php endif; ?>
</div>

<?php
// Incluye el footer común de la aplicación
require RUTA_APP . '/views/inc/footer.php';
?>