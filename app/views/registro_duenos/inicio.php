<?php require RUTA_APP . '/views/inc/header.php'; ?>

<!-- Registro -->
<div class="container my-5">
    <h1 class="section-title">Registro como Propietario</h1>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <!-- Formulario de registro de propietarios -->
        <form action="<?php echo RUTA_URL; ?>/registro_duenos" method="POST" id="registroForm">
            <!-- Campo para el nombre completo -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Tu nombre"
                    value="<?php if (isset($datos['correct']['nombre'])) echo $datos['correct']['nombre']; ?>" required />
                <!-- Mensaje de error para el nombre -->
                <span class="text-danger" id="error-nombre"><?php if (isset($datos['errors']['nombre'])) echo $datos['errors']['nombre']; ?></span>
            </div>
            <!-- Campo para el correo electrónico -->
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" id="correo" placeholder="tucorreo@ejemplo.com"
                    value="<?php if (isset($datos['correct']['email'])) echo $datos['correct']['email']; ?>" required />
                <!-- Mensaje de error para el correo -->
                <span class="text-danger" id="error-correo"><?php if (isset($datos['errors']['email'])) echo $datos['errors']['email']; ?></span>
            </div>
            <!-- Campo para la contraseña -->
            <div class="mb-5">
                <label for="contrasena" class="form-label">
                    Contraseña
                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo."></i>
                </label>
                <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Contraseña" required />
                <!-- Mensaje de error para la contraseña -->
                <span class="text-danger" id="error-contrasena"><?php if (isset($datos['errors']['contrasena'])) echo $datos['errors']['contrasena']; ?></span>
            </div>
            <!-- Botón para enviar el formulario -->
            <div class="form group text-center">
                <input type="submit" class="btn btn-primary px-5" value="Registrarme" />
            </div>
        </form>
    </div>
</div>

<!-- Script para validaciones adicionales del formulario -->
<script src="<?php echo RUTA_URL; ?>/public/js/registro.js"></script>

<?php require RUTA_APP . '/views/inc/footer.php'; ?>