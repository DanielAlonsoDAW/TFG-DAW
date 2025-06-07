<?php
// Incluimos el header común a todas las vistas
require RUTA_APP . '/views/inc/header.php';
?>

<div class="container mt-5 mb-5 perfil-contenedor">
    <!-- Título de la sección de perfil -->
    <h2 class="section-title">Mi Perfil</h2>

    <div class="row align-items-start flex-column flex-md-row">
        <!-- Columna de la imagen de perfil del usuario -->
        <div class="col-md-4 text-center">
            <!-- Mostramos la imagen de perfil del usuario -->
            <img src="<?= RUTA_URL . '/' . $datos->imagen ?>" alt="Imagen de perfil" class="perfil-imagen shadow-sm mb-3">
        </div>
        <!-- Columna de la información del perfil -->
        <div class="col-md-8 perfil-info">
            <ul class="list-unstyled">
                <!-- Nombre del usuario -->
                <li>
                    <i class="bi bi-person-fill text-dark"></i>
                    <strong> Nombre:</strong>
                    <?= htmlspecialchars($datos->nombre) ?>
                </li>

                <!-- Correo electrónico del usuario -->
                <li>
                    <?= getIcono('email') ?>
                    <strong> Email:</strong>
                    <?= htmlspecialchars($datos->email) ?>
                </li>

                <!-- Fecha de registro del usuario -->
                <li>
                    <i class="bi bi-calendar-check text-muted"></i>
                    <strong> Registrado el:</strong>
                    <?= date('d/m/Y', strtotime($datos->fecha_registro)) ?>
                </li>
            </ul>

            <div class="mt-3 perfil-botones">
                <!-- Botón para modificar credenciales de acceso -->
                <a href="<?= RUTA_URL ?>/duenos/editarAccesos"
                    class="btn btn-outline-primary mt-2 me-2">
                    <?= getIcono('key') ?> Editar Accesos
                </a>

                <!-- Botón para editar información personal -->
                <a href="<?= RUTA_URL ?>/duenos/editarDatos"
                    class="btn btn-outline-primary mt-2">
                    <?= getIcono('pencil') ?> Editar Datos
                </a>
            </div>
        </div>
    </div>
</div>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>