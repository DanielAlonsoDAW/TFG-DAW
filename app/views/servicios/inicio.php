<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Título -->
<div class="container my-5">
    <h1 class="section-title">Nuestros Servicios</h1>
    <p class="text-center mb-5">
        Encuentra el cuidado perfecto para tu mascota, adaptado a tus necesidades y horarios.
    </p>

    <!-- Servicios -->
    <div class="row g-4">
        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🏡 Alojamiento</h4>
                <p>
                    Alojamiento para los perros en casa del cuidador, incluyendo la
                    noche. Se recomienda que el dueño proporcione comida, chuches,
                    cama, cartilla veterinaria y su juguete favorito para mayor comodidad.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🏠 Cuidado a domicilio</h4>
                <p>
                    Tu cuidador cuida tanto de tus mascotas como de tu casa.
                    Tus animales reciben atención individualizada en su entorno habitual,
                    mientras tu hogar permanece seguro y atendido durante tu ausencia.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🧸 Guardería de día</h4>
                <p>
                    Tu perro disfruta de un día completo en la casa de su cuidador, rodeado de atención y cariño.
                    Déjalo por la mañana y recógelo por la tarde, sabiendo que ha estado en buenas manos todo el día.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🏘️ Visitas a domicilio</h4>
                <p>
                    Atención personalizada para tu mascota sin que tenga que salir de casa. Durante visitas de 30 a 60 minutos, el cuidador se encarga de alimentarlo,
                    jugar con él, limpiar si es necesario y administrar medicación, brindándole cariño y cuidados adaptados a sus rutinas.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🚶 Paseo de perros</h4>
                <p>
                    Paseos personalizados de hasta 60 minutos para que tu perro disfrute, explore y se ejercite.
                    El cuidador lo recoge y lo entrega directamente en tu domicilio, garantizando una experiencia segura, estimulante y adaptada a sus necesidades.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🚕 Taxi para mascotas</h4>
                <p>
                    Reserva un servicio de transporte especializado para tu mascota, conducido por un socio de confianza que está habituado a viajar con animales.
                    Perfecto para llevar a tu compañero peludo al veterinario, la peluquería o cualquier cita, con total seguridad, comodidad y cuidado.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5">
        <h5>¿Listo para encontrar el mejor cuidador?</h5>
        <a href="<?php echo RUTA_URL; ?>\buscador" class="btn btn-primary mt-4">Buscar ahora</a>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>