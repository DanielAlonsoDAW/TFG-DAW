<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2 class="section-title">Editar Accesos</h2>
    <div class="formulario-container d-flex flex-wrap justify-content-center">
        <form method="POST">
            <div class="mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $entrada['nombre'] ?? $datos['datos']->nombre ?>">
                <div class="invalid-feedback" id="error-nombre"><?= $errores['nombre'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= $entrada['email'] ?? $datos['datos']->email ?>">
                <div class="invalid-feedback" id="error-email"><?= $errores['email'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label for="contrasena_actual">Contraseña actual:</label>
                <input type="password" id="contrasena_actual" name="contrasena_actual" class="form-control">
                <div class="invalid-feedback" id="error-contrasena_actual"><?= $errores['contrasena_actual'] ?? '' ?></div>
            </div>


            <div class="mb-3">
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control">
                <div class="invalid-feedback" id="error-contrasena"><?= $errores['contrasena'] ?? '' ?></div>
            </div>

            <div class="text-center mt-5">
                <input type="submit" class="btn btn-primary" value="Guardar cambios">
            </div>
        </form>
    </div>
</div>
<script src="<?php echo RUTA_URL; ?>/js/cuidadores/editarAccesos.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>