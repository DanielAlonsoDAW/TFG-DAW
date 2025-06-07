<!-- cabecera de las páginas del site -->
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menú de Opciones</title>
  <!-- Enlaces a hojas de estilo locales y externas -->
  <link href="<?php echo RUTA_URL; ?>\css\bootstrap\bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo RUTA_URL; ?>\css\bootstrap-icons\font\bootstrap-icons.min.css" rel="stylesheet">
  <link href="<?php echo RUTA_URL; ?>\css\estilos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <div class="page-wrapper d-flex flex-column min-vh-100">
    <!-- Barra de navegación principal -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
      <div class="container d-flex align-items-center justify-content-between">
        <!-- Logo de la web -->
        <a class="navbar-brand" href="<?php echo RUTA_URL; ?>/home">
          <img src="<?php echo RUTA_URL; ?>\img\logo.png" alt="Logo Guardería Patitas" class="logo-img" />
        </a>

        <!-- Botón para mostrar/ocultar menú en móviles -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenido colapsable del navbar -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <!-- Menú principal centrado -->
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo RUTA_URL; ?>\buscador">Buscador</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-primary ms-2" href="<?php echo RUTA_URL; ?>/como_funciona">Cómo funciona</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-outline-primary ms-2" href="<?php echo RUTA_URL; ?>\servicios">Servicios</a>
            </li>
            <?php
            // Si el usuario NO ha iniciado sesión
            if (!isset($_SESSION['usuario'])) {
              echo '<li class="nav-item">
                  <a class="nav-link btn btn-outline-primary ms-2" href="' . RUTA_URL . '\registro_cuidadores">Convertirse en cuidador</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link btn btn-outline-primary ms-2" href="' . RUTA_URL . '\registro_duenos">Registrarse</a>
                </li>';
            // Si el usuario es dueño
            } elseif ($_SESSION['grupo'] === "dueno") {
              echo '<a class="nav-link btn btn-outline-primary ms-2" href="' . RUTA_URL . '\registro_cuidadores">Convertirse en cuidador</a>';
            }
            ?>
          </ul>

          <!-- Zona derecha del navbar (login, perfil, etc) -->
          <div class="nav-right d-flex align-items-center">
            <?php
            // Si el usuario NO ha iniciado sesión
            if (!isset($_SESSION['usuario'])) {
              echo '<a class="nav-link btn btn-outline-primary ms-2" href="' . RUTA_URL . '\autenticacion">Iniciar sesión</a>';
            // Si el usuario es dueño
            } elseif ($_SESSION['grupo'] === "dueno") {
              $nombreUsuario = explode(' ', $_SESSION['usuario'])[0];

              // Menú desplegable con nombre y foto de usuario
              echo '
                    <div class="dropdown me-2">
                      <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="dropdownUsuario" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="' . RUTA_URL . '/' . $_SESSION['imagen_usuario'] . '" alt="Foto de perfil" class="rounded-circle user-avatar">
                          <span>' . htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8') . '</span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUsuario">
                        <li><a class="dropdown-item" href=" ' . RUTA_URL . '/duenos/perfilPriv/">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="' . RUTA_URL . '/mascotas/">Mis mascotas</a></li>
                        <li><a class="dropdown-item" href="' . RUTA_URL . '/duenos/misReservas/">Mis reservas</a></li>
                      </ul>
                    </div>';
              // Botón para cerrar sesión
              echo '<a class="nav-link btn btn-outline-danger ms-2" href="' . RUTA_URL . '/autenticacion/logout">Cerrar Sesión</a>';
            // Si el usuario es cuidador
            } elseif ($_SESSION['grupo'] === "cuidador") {
              $nombreUsuario = explode(' ', $_SESSION['usuario'])[0];

              // Menú desplegable con nombre y foto de usuario
              echo '
                    <div class="dropdown me-2">
                      <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" id="dropdownUsuario" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="' . RUTA_URL . '/' . $_SESSION['imagen_usuario'] . '" alt="Foto de perfil" class="rounded-circle user-avatar">
                          <span>' . htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8') . '</span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUsuario">
                        <li><a class="dropdown-item" href=" ' . RUTA_URL . '/cuidadores/perfilPriv/">Mi perfil</a></li>
                        <li><a class="dropdown-item" href="' . RUTA_URL . '/mascotas/">Mis mascotas</a></li>
                        <li><a class="dropdown-item" href="' . RUTA_URL . '/cuidadores/misReservas/">Mis reservas</a></li>
                      </ul>
                    </div>';
              // Botón para cerrar sesión
              echo '<a class="nav-link btn btn-outline-danger ms-2" href="' . RUTA_URL . '/autenticacion/logout">Cerrar Sesión</a>';
            }
            ?>
          </div>
        </div>
      </div>
    </nav>