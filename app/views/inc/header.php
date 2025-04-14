<!-- cabecera de las páginas del site -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Opciones</title>
    <link href="<?php echo RUTA_URL; ?>\css\bootstrap\bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo RUTA_URL; ?>\css\estilos.css" rel="stylesheet">
</head>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand" href="<?php echo RUTA_URL; ?>/home">
            <img src="<?php echo RUTA_URL; ?>\img\logo.jpg" alt="Logo Guardería Patitas" class="logo-img" />
        </a>

        <div class="collapse navbar-collapse text-center w-100" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>\buscador">Buscador</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link btn btn-outline-primary ms-2"
                        href="<?php echo RUTA_URL; ?>/como_funciona">Cómo funciona</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link btn btn-outline-primary ms-2"
                        href="<?php echo RUTA_URL; ?>\servicios">Servicios</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link btn btn-outline-primary ms-2"
                        href="<?php echo RUTA_URL; ?>\registro_cuidadores">Convertirse en cuidador</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link btn btn-outline-primary ms-2"
                        href="<?php echo RUTA_URL; ?>\registro_duenos">Registrarse</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary ms-2" href="<?php echo RUTA_URL; ?>\autenticacion">Iniciar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>