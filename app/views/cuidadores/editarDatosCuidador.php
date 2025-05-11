<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
  <h2 class="section-title">Editar Datos de Cuidador</h2>
  <div class="formulario-container col-12 col-md-8 col-lg-6">
    <form method="POST" enctype="multipart/form-data">

      <div class="mb-3">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" class="form-control" value="<?= $entrada['telefono'] ?? $datos['datos']->telefono ?>">
        <div class="text-danger" id="error-telefono"><?= $errores['telefono'] ?? '' ?></div>
      </div>

      <div class="mb-3">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" class="form-control" value="<?= $entrada['direccion'] ?? $datos['datos']->direccion ?>">
        <div class="text-danger" id="error-direccion"><?= $errores['direccion'] ?? '' ?></div>
      </div>

      <div class="mb-3">
        <label for="ciudad">Ciudad:</label>
        <input type="text" id="ciudad" name="ciudad" class="form-control" value="<?= $entrada['ciudad'] ?? $datos['datos']->ciudad ?>">
        <div class="text-danger" id="error-ciudad"><?= $errores['ciudad'] ?? '' ?></div>
      </div>

      <div class="mb-3">
        <label for="pais">País:</label>
        <input type="text" id="pais" name="pais" class="form-control" value="<?= $entrada['pais'] ?? $datos['datos']->pais ?>">
        <div class="text-danger" id="error-pais"><?= $errores['pais'] ?? '' ?></div>
      </div>

      <div class="mb-3">
        <label>Imagen de perfil actual:</label><br>
        <img src="<?= RUTA_URL . '/' . $datos['datos']->imagen ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
      </div>

      <div class="mb-3">
        <label for="imagen">Subir nueva imagen:</label>
        <input type="file" id="imagen" name="imagen" class="form-control">
        <div class="text-danger" id="error-imagen"><?= $errores['imagen'] ?? '' ?></div>
      </div>

      <div class="text-center mt-5">
        <input type="submit" class="btn btn-primary" value="Guardar cambios">
      </div>
    </form>
  </div>
</div>
<script src="<?php echo RUTA_URL; ?>/js/cuidadores/editarDatosCuidador.js"></script>
<?php require RUTA_APP . '/views/inc/footer.php'; ?>