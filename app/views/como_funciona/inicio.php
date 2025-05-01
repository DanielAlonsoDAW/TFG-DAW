<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Título principal -->
<div class="container my-5">
    <h1 class="section-title">¿Cómo funciona Guardería Patitas?</h1>

    <!-- Paso 1 -->
    <div class="step">
        <h4>1. Busca un cuidador cerca de ti</h4>
        <p>
            Introduce tu ubicación, el tipo de mascota (perro o gato), el servicio
            que necesitas y las fechas. Verás una lista de cuidadores disponibles
            en tu zona.
        </p>
    </div>

    <!-- Paso 2 -->
    <div class="step">
        <h4>2. Consulta los perfiles y valoraciones</h4>
        <p>
            Haz clic en el perfil de cada cuidador para ver su experiencia,
            servicios ofrecidos, precios y valoraciones de otros dueños de
            mascotas.
        </p>
    </div>

    <!-- Paso 3 -->
    <div class="step">
        <h4>3. Regístrate e inicia sesión</h4>
        <p>
            Para hacer una reserva o contactar con un cuidador, necesitas crear
            una cuenta gratuita. ¡Es rápido y sencillo!
        </p>
    </div>

    <!-- Paso 4 -->
    <div class="step">
        <h4>4. Reserva y paga de forma segura</h4>
        <p>
            Haz tu reserva directamente desde el perfil del cuidador. Recibirás
            una confirmación por correo y una factura en tu perfil.
        </p>
    </div>

    <!-- Paso 5 -->
    <div class="step">
        <h4>5. Valora y deja tu opinión</h4>
        <p>
            Al finalizar el servicio, podrás valorar al cuidador y dejar una
            reseña para ayudar a otros usuarios.
        </p>
    </div>

    <!-- Extra: mensaje final -->
    <div class="text-center mt-5">
        <h5>¿Eres amante de los animales?</h5>
        <p>
            <a href="#convertirse-cuidador" class="btn btn-primary mt-4">¡Conviértete en cuidador!</a>
        </p>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>