<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
require RUTA_APP . "/librerias/Funciones.php";
?>

<!-- HERO / INFORMACIÓN -->
<div id="buscador-home">
    <h1>¡Bienvenido a Guardería Patitas!</h1>
    <p>
        Conecta con los mejores cuidadores para tus perros y gatos. ¡Seguros,
        atentos y cerca de ti!
    </p>

    <!-- BUSCADOR (Sección anclada) -->
    <div id="cabecera-buscador">
        <div class="container my-4">
            <h2 class="text-center mb-4">Busca cuidadores cerca de ti</h2>
            <div class="formulario-container col-12 col-md-10">
                <form id="form-filtros" class="row gy-4 gx-3 justify-content-center">

                    <!-- Ciudad -->
                    <div class="col-12 col-md-6">
                        <input id="input-ciudad" name="ciudad" type="text" class="form-control" placeholder="Ubicación" />
                    </div>

                    <!-- Fecha -->
                    <div class="col-12 col-md-6">
                        <input type="date" name="fecha" class="form-control" />
                    </div>

                    <!-- Tipo de mascota -->
                    <div class="col-12 col-md-6 text-center">
                        <label class="form-label mb-3">Tipo de mascota</label>
                        <div class="d-flex justify-content-center flex-wrap gap-3">
                            <input type="checkbox" class="btn-check" id="mascota-perro" name="tipo_mascota[]" value="Perro" autocomplete="off">
                            <label class="btn btn-perro" for="mascota-perro">
                                <i class="fa-solid fa-dog"></i> Perro
                            </label>

                            <input type="checkbox" class="btn-check" id="mascota-gato" name="tipo_mascota[]" value="Gato" autocomplete="off">
                            <label class="btn btn-gato" for="mascota-gato">
                                <i class="fa-solid fa-cat"></i> Gato
                            </label>
                        </div>
                    </div>

                    <!-- Servicio -->
                    <div class="col-12 col-md-6">
                        <select name="servicio" class="form-select">
                            <option selected disabled value="">Servicio</option>
                            <option value="Alojamiento">Alojamiento</option>
                            <option value="Paseos">Paseo de perros</option>
                            <option value="Guardería de día">Guardería de día</option>
                            <option value="Visitas a domicilio">Visitas a domicilio</option>
                            <option value="Cuidado a domicilio">Cuidado a domicilio</option>
                            <option value="Taxi">Taxi</option>
                        </select>
                    </div>
                    <!-- Tamaño del animal para Perro -->
                    <div class="col-8 col-md-6 d-none" id="bloque-tamano-perro">
                        <label class="form-label d-block mb-2">Tamaño del perro</label>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="perro-pequeno" name="tamano_perro[]" value="Pequeño" autocomplete="off">
                            <label class="btn btn-perro mt-2" for="perro-pequeno">
                                <i class="fa-solid fa-dog me-1"></i> Pequeño (&lt; 35 cm)
                            </label>

                            <input type="checkbox" class="btn-check" id="perro-mediano" name="tamano_perro[]" value="Mediano" autocomplete="off">
                            <label class="btn btn-perro mt-2" for="perro-mediano">
                                <i class="fa-solid fa-dog me-1"></i> Mediano (35–49 cm)
                            </label>

                            <input type="checkbox" class="btn-check" id="perro-grande" name="tamano_perro[]" value="Grande" autocomplete="off">
                            <label class="btn btn-perro mt-2" for="perro-grande">
                                <i class="fa-solid fa-dog me-1"></i> Grande (≥ 50 cm)
                            </label>
                        </div>
                    </div>

                    <!-- Tamaño del animal para Gato -->
                    <div class="col-8 col-md-6 d-none" id="bloque-tamano-gato">
                        <label class="form-label d-block mb-2">Tamaño del gato</label>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <input type="checkbox" class="btn-check" id="gato-pequeno" name="tamano_gato[]" value="Pequeño" autocomplete="off">
                            <label class="btn btn-gato mt-2" for="gato-pequeno">
                                <i class="fa-solid fa-cat me-1"></i> Pequeño (&lt; 3 kg)
                            </label>

                            <input type="checkbox" class="btn-check" id="gato-mediano" name="tamano_gato[]" value="Mediano" autocomplete="off">
                            <label class="btn btn-gato mt-2" for="gato-mediano">
                                <i class="fa-solid fa-cat me-1"></i> Mediano (3–5 kg)
                            </label>

                            <input type="checkbox" class="btn-check" id="gato-grande" name="tamano_gato[]" value="Grande" autocomplete="off">
                            <label class="btn btn-gato mt-2" for="gato-grande">
                                <i class="fa-solid fa-cat me-1"></i> Grande (&gt; 5 kg)
                            </label>
                        </div>
                    </div>

                    <!-- Botón -->
                    <div class="col-12 col-md-6 text-center text-start">
                        <input type="submit" class="btn btn-primary px-4 py-2" value="Buscar" />
                    </div>
                </form>
            </div>
        </div>
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
    <button type="button" class="visor-nav visor-prev" onclick="imagenAnterior()">&#10094;</button>
    <img id="imagenAmpliada" class="visor-img" src="" alt="Imagen ampliada" />
    <button type="button" class="visor-nav visor-next" onclick="imagenSiguiente()">&#10095;</button>
</div>


<!-- Scroll Cuidadores Destacados -->

<div id="carouselCuidadores" class="carousel slide my-5" data-bs-ride="carousel" data-bs-interval="3000">
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
<script src="<?php echo RUTA_URL; ?>\js\home.js"></script>
<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>