<?php require RUTA_APP . '/views/inc/header.php'; ?>

<?php
// Mostrar el mensaje de error si está presente
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
if ($error) {
    // Borrar el mensaje después de mostrarlo
    unset($_SESSION['error']);
}
?>

<!-- Login -->
<div class="container my-5">
    <h1 class="section-title">Iniciar sesión</h1>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <div class="card-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo RUTA_URL; ?>/autenticacion" method="POST" id="loginForm" novalidate>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" class="form-control" id="correo" placeholder="tucorreo@ejemplo.com" required />
                    <span class="text-danger" id="error-correo"></span>
                </div>
                <div class="mb-5">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Tu contraseña" required />
                    <span class="text-danger" id="error-contrasena"></span>
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-primary px-5" value="Entrar" />
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo RUTA_URL; ?>/public/js/inicioSesion.js"></script>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>