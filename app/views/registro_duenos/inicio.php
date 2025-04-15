<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Registro -->
<div class="container my-5">
    <h1 class="section-title">Registro como Propietario</h1>
    <div class="form-container">
        <form action="<?php echo RUTA_URL; ?>/registro_duenos" method="POST" id="registroForm">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" placeholder="Tu nombre"
                    value="<?php if (isset($datos['correct']['nombre'])) echo $datos['correct']['nombre']; ?>" required />
                <span class="text-danger" id="error-nombre"><?php if (isset($datos['errors']['nombre'])) echo $datos['errors']['nombre']; ?></span>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="correo" placeholder="tucorreo@ejemplo.com"
                    value="<?php if (isset($datos['correct']['email'])) echo $datos['correct']['email']; ?>" required />
                <span class="text-danger" id="error-correo"><?php if (isset($datos['errors']['email'])) echo $datos['errors']['email']; ?></span>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">
                    Contraseña
                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo."></i>
                </label>
                <input type="password" class="form-control" id="contraseña" placeholder="Contraseña" required />
                <span class="text-danger" id="error-contraseña"><?php if (isset($datos['errors']['contraseña'])) echo $datos['errors']['contraseña']; ?></span>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">
                    Registrarme
                </button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo RUTA_URL; ?>/public/js/registroDuenos.js"></script>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>