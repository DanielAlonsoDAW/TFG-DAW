<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- HERO / INFORMACIÓN -->
<div class="hero-section text-center py-5 bg-light">
    <h1 class="display-4">¡Bienvenido a Guardería Patitas!</h1>
    <p class="lead">
        Conecta con los mejores cuidadores para tus perros y gatos. ¡Seguros,
        atentos y cerca de ti!
    </p>
</div>

<!-- BUSCADOR -->
<div class="container my-5" id="buscador">
    <h3 class="text-center mb-4">Busca un cuidador cerca de ti</h3>
    <form class="row g-3 justify-content-center">
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

<!-- Scroll mascotas -->
<div class="carousel-wrapper container-fluid py-5 bg-light">
    <div class="d-flex justify-content-between align-items-center">
        <button class="btn btn-outline-secondary" onclick="scrollCarousel(-1)">
            &#10094;
        </button>

        <div class="scroll-carousel d-flex overflow-auto" id="scrollCarousel">
            <img src="https://placecats.com/300/200" alt="Gato feliz" class="img-fluid mx-2" />
            <img src="https://placedog.net/360/200" alt="Perro feliz" class="img-fluid mx-2" />
            <img src="https://placecats.com/301/200" alt="Gato feliz" class="img-fluid mx-2" />
            <img src="https://placedog.net/361/200" alt="Perro feliz" class="img-fluid mx-2" />
            <img src="https://placecats.com/302/200" alt="Gato feliz" class="img-fluid mx-2" />
            <img src="https://placedog.net/362/200" alt="Perro feliz" class="img-fluid mx-2" />
            <img src="https://placecats.com/303/200" alt="Gato feliz" class="img-fluid mx-2" />
            <img src="https://placedog.net/363/200" alt="Perro feliz" class="img-fluid mx-2" />
            <img src="https://placecats.com/304/200" alt="Gato feliz" class="img-fluid mx-2" />
        </div>

        <button class="btn btn-outline-secondary" onclick="scrollCarousel(1)">
            &#10095;
        </button>
    </div>
</div>

<script>
    function scrollCarousel(direction) {
        const container = document.getElementById("scrollCarousel");
        const scrollAmount = 320; // Aproximadamente el ancho de una imagen + margen
        container.scrollBy({
            left: direction * scrollAmount,
            behavior: "smooth",
        });
    }
</script>

<!-- Cuidadores Destacados -->
<div class="container-fluid py-5">
    <div class="container">
        <h3 class="mb-4 text-center">Cuidadores Destacados</h3>

        <div id="carouselCuidadores" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 1" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Carlos G.</h5>
                                <p class="card-text">Barcelona | Cuida perros</p>
                                <p>⭐⭐⭐⭐⭐ (5.0)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 2" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Lucía M.</h5>
                                <p class="card-text">Madrid | Gatos y perros</p>
                                <p>⭐⭐⭐⭐☆ (4.6)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 3" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Elena R.</h5>
                                <p class="card-text">Valencia | Perros pequeños</p>
                                <p>⭐⭐⭐⭐⭐ (4.9)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 4" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Javier L.</h5>
                                <p class="card-text">Sevilla | Gatos</p>
                                <p>⭐⭐⭐⭐ (4.2)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 5" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Marta F.</h5>
                                <p class="card-text">Bilbao | Perros medianos</p>
                                <p>⭐⭐⭐⭐⭐ (5.0)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
                            </div>
                        </div>
                        <div class="card" style="width: 18rem">
                            <img src="https://placehold.co/300x200" class="card-img-top" alt="Cuidador 6" />
                            <div class="card-body text-center">
                                <h5 class="card-title">Diego A.</h5>
                                <p class="card-text">Málaga | Gatos y perros</p>
                                <p>⭐⭐⭐⭐☆ (4.7)</p>
                                <a href="#" class="btn btn-primary">Ver perfil</a>
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