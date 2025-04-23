<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Formulario de registro -->
<div class="container my-5">
    <h1 class="section-title">¡Únete como cuidador!</h1>
    <p class="text-center">Completa el formulario para comenzar a ofrecer tus servicios como cuidador de mascotas.</p>
    <div class="form-container">
        <form action="<?php echo RUTA_URL; ?>/registro_cuidadores" method="POST" id="registroForm">
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
                    Contraseña
                    <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="La contrasena debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un símbolo."></i>
                </label>
                <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Contraseña" required />
                <span class="text-danger" id="error-contrasena"><?php if (isset($datos['errors']['contrasena'])) echo $datos['errors']['contrasena']; ?></span>
            </div>
            <div class="form group text-center">
                <input type="submit" class="btn btn-primary px-5" value="Registrarme" />
            </div>
        </form>
    </div>

    <!-- Explicación -->
    <div class="container mb-5">
        <h2 class="section-title">¿Cómo funciona ser cuidador en Guardería Patitas?</h2>

        <div class="info-section">
            <h4>🔹 Crea tu perfil</h4>
            <p>Completa tu perfil con tus experiencias, tipos de mascotas que aceptas, disponibilidad, ubicación, servicios, tarifas y fotos.</p>
        </div>

        <div class="info-section">
            <h4>🔹 Recibe solicitudes</h4>
            <p>Los dueños te encontrarán por ubicación y servicios. Puedes aceptar o rechazar solicitudes según tu disponibilidad.
            <p>
        </div>

        <div class="info-section">
            <h4>🔹 Brinda el servicio</h4>
            <p>Recibe a las mascotas, comunícate con los dueños desde la plataforma, y cuida con cariño a cada peludo que confíen en ti.
            <p>
        </div>

        <div class="info-section">
            <h4>🔹 Recibe tu pago</h4>
            <p>Una vez terminado el servicio, recibirás tu pago de forma segura. ¡Solo preocúpate de cuidar!
            <p>
        </div>

        <h2 class="section-title mt-5">Servicios que puedes ofrecer</h2>

        <div class="info-section">
            <h4>🏡 Alojamiento</h4>
            <p>Alojamiento para los perros en tu casa, incluyendo la noche. No olvides pedir su comida, chuches, cama, cartilla veterinaria y su juguete favorito.
            <p>
        </div>

        <div class="info-section">
            <h4>🚶 Paseo de perros</h4>
            <p>Un máximo de 60 minutos de paseo para los perros. La recogida y entrega será en la casa de sus dueños.
            <p>
        </div>

        <div class="info-section">
            <h4>🧸 Guardería de día</h4>
            <p>Cuida de perros durante el día en tu casa, hasta un máximo de 10 horas. Ideal para dueños que trabajan fuera durante el día.
            <p>
        </div>

        <div class="info-section">
            <h4>🏠 Visitas a domicilio</h4>
            <p>Ofrece cuidado de mascotas con visitas de 30-60 minutos en el hogar del animal. Puedes alimentar, jugar, cambiar agua o dar medicación.
            <p>
        </div>
    </div>

    <script src="<?php echo RUTA_URL; ?>/public/js/registro.js"></script>

    <?php
    // Cargamos el footer al final de la página
    require RUTA_APP . '/views/inc/footer.php';
    ?>