<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
  <h2 class="section-title">Editar Datos</h2>
  <div class="formulario-container col-12 col-md-8 col-lg-6">
    <!-- Formulario para editar los datos del cuidador -->
    <form method="POST" enctype="multipart/form-data">

      <!-- Campo para el nombre -->
      <div class="mb-3">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $datos['entrada']['nombre'] ?? $datos['datos']->nombre ?>">
        <div class="text-danger" id="error-nombre"><?= $datos['errores']['nombre'] ?? '' ?></div>
      </div>

      <!-- Campo para la dirección -->
      <div class="mb-3">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" value="<?= $datos['entrada']['direccion'] ?? $datos['datos']->direccion ?>">
        <div class="text-danger" id="error-direccion"><?= $datos['errores']['direccion'] ?? '' ?></div>
      </div>

      <!-- Campo para la ciudad -->
      <div class="mb-3">
        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" class="form-control" value="<?= $datos['entrada']['ciudad'] ?? $datos['datos']->ciudad ?>">
        <div class="text-danger" id="error-ciudad"><?= $datos['errores']['ciudad'] ?? '' ?></div>
      </div>

      <!-- Campo para el país -->
      <div class="mb-3">
        <label for="pais">País:</label>
        <input type="text" id="pais" name="pais" class="form-control" value="<?= $datos['entrada']['pais'] ?? $datos['datos']->pais ?>">
        <div class="text-danger" id="error-pais"><?= $datos['errores']['pais'] ?? '' ?></div>
      </div>

      <!-- Muestra la imagen de perfil actual -->
      <div class="mb-3">
        <label>Imagen de perfil actual:</label><br>
        <img src="<?= RUTA_URL . '/' . $datos['datos']->imagen ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
      </div>

      <!-- Campo para subir una nueva imagen de perfil -->
      <div class="mb-3">
        <label for="imagen">Subir nueva imagen:</label>
        <input type="file" id="imagen" name="imagen" class="form-control">
        <div class="text-danger" id="error-imagen"><?= $datos['errores']['imagen'] ?? '' ?></div>
      </div>

      <!-- Botón para guardar los cambios -->
      <div class="text-center mt-5">
        <input type="submit" class="btn btn-primary" value="Guardar cambios">
      </div>
    </form>
  </div>
</div>
<!-- Script para validaciones o funcionalidades adicionales en el formulario -->
<script src="<?php echo RUTA_URL; ?>/js/cuidadores/editarDatos.js"></script>
<?php require RUTA_APP . '/views/inc/footer.php'; ?>