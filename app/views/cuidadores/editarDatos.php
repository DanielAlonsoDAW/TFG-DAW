<?php require RUTA_APP . '/views/inc/header.php';
$entrada = $datos['entrada'] ?? [];
$errores = $datos['errores'] ?? [];
?>

<div class="container mt-5 mb-5">
  <h2 class="section-title">Editar Datos</h2>
  <div class="formulario-container col-12 col-md-8 col-lg-6">
    <form method="POST" enctype="multipart/form-data">

      <div class="mb-3">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $entrada['nombre'] ?? $datos['datos']->nombre ?>">
        <div class="text-danger" id="error-nombre"><?= $errores['nombre'] ?? '' ?></div>
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
<script src="<?php echo RUTA_URL; ?>/js/cuidadores/editarDatos.js"></script>
<?php require RUTA_APP . '/views/inc/footer.php'; ?>