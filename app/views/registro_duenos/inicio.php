<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Registro -->
<div class="container my-5">
    <h1 class="section-title">Registro como Propietario</h1>
    <div class="form-container">
        <form>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input
                    type="text"
                    class="form-control"
                    id="nombre"
                    placeholder="Tu nombre"
                    required />
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input
                    type="email"
                    class="form-control"
                    id="correo"
                    placeholder="tucorreo@ejemplo.com"
                    required />
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input
                    type="password"
                    class="form-control"
                    id="contrasena"
                    placeholder="Mínimo 6 caracteres"
                    required />
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-5">
                    Registrarme
                </button>
            </div>
        </form>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>