<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Título -->
<div class="container my-5">
    <h1 class="section-title">Nuestros Servicios</h1>
    <p class="text-center mb-5">
        Encuentra el cuidado perfecto para tu mascota, adaptado a tus
        necesidades y horarios.
    </p>

    <!-- Servicios -->
    <div class="row g-4">
        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🏡 Alojamiento</h4>
                <p>
                    Alojamiento para los perros en casa del cuidador, incluyendo la
                    noche. Se recomienda que el dueño proporcione comida, chuches,
                    cama, cartilla veterinaria y su juguete favorito para mayor
                    comodidad.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🚶 Paseo de perros</h4>
                <p>
                    Paseos de hasta 60 minutos. El cuidador se encarga de recoger y
                    entregar al perro directamente en el domicilio del dueño,
                    asegurando un paseo seguro y agradable.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🧸 Guardería de día</h4>
                <p>
                    Cuida a los perros durante el día en tu casa, con una duración
                    máxima de 10 horas. Ideal para dueños que trabajan o necesitan
                    dejar a su mascota temporalmente.
                </p>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="service-card h-100">
                <h4>🏠 Visitas a domicilio</h4>
                <p>
                    Ofrece cuidado personalizado con visitas al hogar del animal. Cada
                    visita tiene una duración de 30 a 60 minutos, donde podrás
                    alimentar, jugar o dar medicación si es necesario.
                </p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5">
        <h5>¿Listo para encontrar el mejor cuidador?</h5>
        <a href="buscador.html" class="btn btn-primary mt-4">Buscar ahora</a>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>