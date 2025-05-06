<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';

function estrellasBootstrap($valoracion)
{
    $html = '';
    $estrellas = floor($valoracion);
    $decimal = $valoracion - $estrellas;
    $media = 0;

    // Media estrella si el decimal es entre 0.25 y 0.75
    if ($decimal >= 0.25 && $decimal <= 0.75) {
        $media = 1;
    } elseif ($decimal > 0.75) {
        // Si es mayor que 0.75, redondeamos hacia arriba
        $estrellas += 1;
    }

    $vacias = 5 - $estrellas - $media;

    $html .= str_repeat('<i class="bi bi-star-fill text-warning"></i>', $estrellas);
    if ($media) {
        $html .= '<i class="bi bi-star-half text-warning"></i>';
    }
    $html .= str_repeat('<i class="bi bi-star text-warning"></i>', $vacias);

    return $html;
}


?>

<!-- HERO / INFORMACIÓN -->
<div id="buscador-home">
    <h1>¡Bienvenido a Guardería Patitas!</h1>
    <p>
        Conecta con los mejores cuidadores para tus perros y gatos. ¡Seguros,
        atentos y cerca de ti!
    </p>
    <!-- BUSCADOR (Sección anclada) -->
    <div class="container my-4" id="buscador">
        <h3 class="text-center mb-4">Busca un cuidador cerca de ti</h3>
        <form class="row g-4 justify-content-center">
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Ubicación" />
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Tipo de mascota</option>
                    <option>Perro</option>
                    <option>Gato</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Servicio</option>
                    <option>Alojamiento</option>
                    <option>Paseo de perros</option>
                    <option>Guardería de día</option>
                    <option>Visitas a domicilio</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select">
                    <option selected>Tamaño del animal</option>
                    <option>Pequeño</option>
                    <option>Mediano</option>
                    <option>Grande</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" />
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Buscar</button>
            </div>
        </form>
    </div>
</div>

<!-- Scroll mascotas -->
<h3 class="text-center mt-5 mb-5">Nuestros queridos peludos</h3>
<div class="carousel-wrapper container-fluid">
    <button class="scroll-button scroll-left" onclick="desplazarCarrusel(-1)">
        &#10094;
    </button>

    <div class="scroll-carousel" id="scrollCarrusel"></div>

    <button class="scroll-button scroll-right" onclick="desplazarCarrusel(1)">
        &#10095;
    </button>
</div>
<script>
    const URL_BASE = "<?php echo RUTA_URL; ?>";
</script>
<script src="<?php echo RUTA_URL; ?>\js\carousel.js"></script>

<div id="visorImagen" class="visor-overlay">
    <span class="visor-cerrar" onclick="cerrarVisor()">×</span>
    <button class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
    <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
    <button class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
</div>


<!-- Scroll Cuidadores Destacados -->

<div id="carouselCuidadores" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="3000">
    <h3 class="text-center mb-4">Cuidadores destacados</h3>
    <div class="carousel-inner">

        <?php
        $cuidadores = $datos['cuidadores'];
        $chunks = array_chunk($cuidadores, 3);
        $active = 'active';

        foreach ($chunks as $grupo):
        ?>
            <div class="carousel-item <?= $active ?>">
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <?php foreach ($grupo as $c): ?>
                        <div class="card card-carrusel">
                            <img src="<?= htmlspecialchars($c->imagen) ?>" class="card-img-top" alt="Imagen de <?= htmlspecialchars($c->nombre) ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($c->nombre) ?></h5>
                                <p class="card-text" text><?= htmlspecialchars($c->ciudad) ?> | <?= htmlspecialchars($c->descripcion) ?></p>
                                <p>
                                    <?= estrellasBootstrap(round($c->promedio_calificacion, 1)) ?>
                                    (<?= number_format($c->promedio_calificacion, 1) ?>)
                                </p>
                                <a href="<?php echo RUTA_URL; ?>/cuidadores/perfil/<?= $c->id ?>" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php
            $active = '';
        endforeach;
        ?>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>