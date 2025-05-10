<form method="POST" class="container mt-5 mb-5">
    <h3>Mi Perfil (Cuidador)</h3>

    <div class="mb-3">
        <label>Nombre:</label>
        <input type="text" name="nombre" class="form-control" value="<?= $datos['datos']->nombre ?>">
    </div>

    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" value="<?= $datos['datos']->email ?>">
    </div>

    <div class="mb-3">
        <label>Teléfono:</label>
        <input type="text" name="telefono" class="form-control" value="<?= $datos['datos']->telefono ?>">
    </div>

    <div class="mb-3">
        <label>Dirección:</label>
        <input type="text" name="direccion" class="form-control" value="<?= $datos['datos']->direccion ?>">
    </div>

    <div class="mb-3">
        <label>Ciudad:</label>
        <input type="text" name="ciudad" class="form-control" value="<?= $datos['datos']->ciudad ?>">
    </div>

    <div class="mb-3">
        <label>País:</label>
        <input type="text" name="pais" class="form-control" value="<?= $datos['datos']->pais ?>">
    </div>

    <div class="mb-3">
        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control"><?= $datos['datos']->descripcion ?></textarea>
    </div>

    <div class="mb-3">
        <label>Máx. Mascotas al día:</label>
        <input type="number" name="max_mascotas_dia" class="form-control" value="<?= $datos['datos']->max_mascotas_dia ?>">
    </div>

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>