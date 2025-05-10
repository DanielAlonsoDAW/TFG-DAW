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

    <button type="submit" class="btn btn-primary">Guardar cambios</button>
</form>