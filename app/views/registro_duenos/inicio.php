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
                <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Tu nombre"
                    value="<?php if (isset($datos['correct']['nombre'])) echo $datos['correct']['nombre']; ?>" required />
                <span class="text-danger" id="error-nombre"><?php if (isset($datos['errors']['nombre'])) echo $datos['errors']['nombre']; ?></span>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" id="correo" placeholder="tucorreo@ejemplo.com"
                    value="<?php if (isset($datos['correct']['email'])) echo $datos['correct']['email']; ?>" required />
                <span class="text-danger" id="error-correo"><?php if (isset($datos['errors']['email'])) echo $datos['errors']['email']; ?></span>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">
                    Contrasena
                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="La contrasena debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo."></i>
                </label>
                <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Contrasena" required />
                <span class="text-danger" id="error-contrasena"><?php if (isset($datos['errors']['contrasena'])) echo $datos['errors']['contrasena']; ?></span>
            </div>
            <div class="form group text-center">
                <input type="submit" class="btn btn-primary px-5" value="Registrarme" />
            </div>
        </form>
    </div>
</div>

<script src="<?php echo RUTA_URL; ?>/public/js/registroDuenos.js"></script>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>