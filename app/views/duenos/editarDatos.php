<?php require RUTA_APP . '/views/inc/header.php'; ?>

<div class="container mt-5 mb-5">
    <h2 class="section-title">Editar Datos</h2>
    <div class="formulario-container col-12 col-md-8 col-lg-6">
        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="nombre">Nombre: </label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= $datos['entrada']['nombre'] ?? $datos['dueno']->nombre ?>" placeholder="Nombre*">
                <div class="text-danger" id="error-nombre"><?= $datos['errores']['nombre'] ?? '' ?></div>
            </div>

            <div class="mb-3">
                <label>Imagen de perfil actual:</label><br>
                <img src="<?= RUTA_URL . '/' . $datos['dueno']->imagen ?>" alt="Imagen actual" class="img-thumbnail" style="max-height: 150px;">
            </div>

            <div class="mb-3">
                <label for="imagen">Subir nueva imagen:</label>
                <input type="file" id="imagen" name="imagen" class="form-control">
                <div class="text-danger" id="error-imagen"><?= $datos['errores']['imagen'] ?? '' ?></div>
            </div>

            <div class="text-center mt-5">
                <input type="submit" class="btn btn-primary" value="Guardar cambios">
            </div>
        </form>
    </div>
</div>
<script src="<?php echo RUTA_URL; ?>/js/duenos/editarDatos.js"></script>
<?php require RUTA_APP . '/views/inc/footer.php'; ?>