<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2 class="section-title">Editar Accesos</h2>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <!-- Formulario para editar los accesos del cuidador -->
        <form method="POST" class="row justify-content-center">

            <!-- Campo para el email -->
            <div class=" mb-3">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= $datos['entrada']['email'] ?? $datos['datos']->email ?>">
                <div class="text-danger" id="error-email"><?= $datos['errores']['email'] ?? '' ?></div>
            </div>

            <!-- Campo para la contraseña actual -->
            <div class="mb-3">
                <label for="contrasena_actual">Contraseña actual:</label>
                <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control">
                <div class="text-danger" id="error-contrasena_actual"><?= $datos['errores']['contrasena_actual']  ?? '' ?></div>
            </div>

            <!-- Campo para la nueva contraseña -->
            <div class="mb-3">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control">
                <div class="text-danger" id="error-contrasena"></div>
            </div>

            <!-- Botón para guardar los cambios -->
            <div class="text-center mt-4">
                <input type="submit" class="btn btn-primary" value="Guardar cambios">
            </div>
        </form>
    </div>
</div>
<!-- Inclusión del archivo JavaScript para validaciones o funcionalidades adicionales -->
<script src="<?php echo RUTA_URL; ?>/js/cuidadores/editarAccesos.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>