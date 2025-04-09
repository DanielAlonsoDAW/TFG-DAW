<!-- cabecera de las páginas del site -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Opciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>/home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>/clientes">Buscar Cuidadores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>/como_funciona">Cómo Funciona</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>/servicios">Servicios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo RUTA_URL; ?>/ser_cuidador">Convertirse en cuidador</a>
                </li>
                <?php
                if (!isset($_SESSION['usuario'])) {
                    echo '<li class="nav-item">
                          <a class="nav-link" href="' . RUTA_URL . '/registro">Registro</a>
                          </li>';
                    echo '<li class="nav-item">
                          <a class="nav-link" href="' . RUTA_URL . '/login">Iniciar Sesión</a>
                          </li>';
                } else {
                    echo '<li class="nav-item">
                          <a class="nav-link" href="' . RUTA_URL . '/usuario">Usuario</a>
                          </li>';
                    echo '<li class="nav-item">
                          <a class="nav-link" href="' . RUTA_URL . '/login/logout">Cerrar Sesión</a>
                          </li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>