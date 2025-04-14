<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<!-- Buscador -->
<div class="search-section">
    <div class="container">
        <h1>Busca cuidadores cerca de ti</h1>
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

<!-- Mapa simulado + Lista -->
<div class="main-content" style="display: flex; height: 600px">
    <!-- LISTA DE CUIDADORES -->
    <div
        class="sidebar"
        style="
          width: 35%;
          background-color: #f0fdfa;
          padding: 20px;
          overflow-y: auto;
        ">
        <h5 style="color: #006d77">Cuidadores disponibles</h5>

        <div
            class="result-card"
            style="
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
          ">
            <h6>Carlos G.</h6>
            <p>Barcelona | Perros pequeños</p>
            <p style="font-size: 0.9rem">
                <em>“Muy atento y cariñoso con mi perro. ¡Repetiré sin duda!”</em>
            </p>
            <p style="color: #f4b400">★★★★★</p>
        </div>

        <div
            class="result-card"
            style="
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
          ">
            <h6>Lucía M.</h6>
            <p>Madrid | Gatos y perros medianos</p>
            <p style="font-size: 0.9rem">
                <em>“Mi gato estuvo genial, muy tranquila y profesional.”</em>
            </p>
            <p style="color: #f4b400">★★★★☆</p>
        </div>

        <div
            class="result-card"
            style="
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
          ">
            <h6>Elena R.</h6>
            <p>Valencia | Perros grandes</p>
            <p style="font-size: 0.9rem">
                <em>“Muy espaciosa su casa y gran energía con los animales.”</em>
            </p>
            <p style="color: #f4b400">★★★★★</p>
        </div>

        <div
            class="result-card"
            style="
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
          ">
            <h6>Javier L.</h6>
            <p>Sevilla | Gatos adultos</p>
            <p style="font-size: 0.9rem">
                <em>“Muy puntual en las visitas y cuidadoso con mi gata mayor.”</em>
            </p>
            <p style="color: #f4b400">★★★★☆</p>
        </div>
    </div>

    <!-- SIMULACIÓN DE MAPA CON IMAGEN -->
    <div
        class="map-placeholder"
        style="
          width: 65%;
          background: url('mapa.png') no-repeat center center;
          background-size: cover;
        "></div>
</div>

<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>