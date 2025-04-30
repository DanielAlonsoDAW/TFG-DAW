<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
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
<div class="container-fluid carousel-bg">
    <div class="container carousel-container">
        <h3 class="mb-4 text-center">Cuidadores Destacados</h3>

        <div
            id="carouselCuidadores"
            class="carousel slide"
            data-bs-ride="carousel"
            data-bs-interval="4000">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 1" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Carlos G.</h5>
                                <p class="card-text">Barcelona | Cuida perros</p>
                                <p>⭐⭐⭐⭐⭐ (5.0)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 2" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Lucía M.</h5>
                                <p class="card-text">Madrid | Gatos y perros</p>
                                <p>⭐⭐⭐⭐☆ (4.6)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 3" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Elena R.</h5>
                                <p class="card-text">Valencia | Perros pequeños</p>
                                <p>⭐⭐⭐⭐⭐ (4.9)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 4" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Javier L.</h5>
                                <p class="card-text">Sevilla | Gatos</p>
                                <p>⭐⭐⭐⭐ (4.2)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 5" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Marta F.</h5>
                                <p class="card-text">Bilbao | Perros medianos</p>
                                <p>⭐⭐⭐⭐⭐ (5.0)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 6" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Diego A.</h5>
                                <p class="card-text">Málaga | Gatos y perros</p>
                                <p>⭐⭐⭐⭐☆ (4.7)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 7" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Andrea T.</h5>
                                <p class="card-text">Zaragoza | Gatos</p>
                                <p>⭐⭐⭐⭐⭐ (5.0)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 8" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Sergio P.</h5>
                                <p class="card-text">Granada | Perros grandes</p>
                                <p>⭐⭐⭐⭐☆ (4.5)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img
                                src="https://placehold.co/300x200"
                                class="card-img-top"
                                alt="Cuidador 9" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Laura V.</h5>
                                <p class="card-text">A Coruña | Gatos y perros</p>
                                <p>⭐⭐⭐⭐⭐ (4.9)</p>
                                <a href="#" class="btn btn-primary btn-perfil">Ver perfil</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>