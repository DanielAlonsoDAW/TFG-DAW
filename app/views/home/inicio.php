<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
$idioma = $datos['idioma'];
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-3">
                <div class="card-header bg-primary text-white text-center">
                    <h2><?php echo $datos[0][15]->$idioma; ?></h2>
                </div>
                <div class="card-body text-center">
                    <form id="languageForm" method="POST" action="<?php echo RUTA_URL; ?>/home/language">
                        <div class="form-group">
                            <select class="form-control" id="language" name="language" onchange="this.form.submit()">
                                <option value="espanol" <?php if ($idioma == 'espanol') echo 'selected' ?>><?php echo $datos[0][9]->$idioma; ?></option>
                                <option value="ingles" <?php if ($idioma == 'ingles') echo 'selected' ?>><?php echo $datos[0][17]->$idioma; ?></option>
                                <option value="catalan" <?php if ($idioma == 'catalan') echo 'selected' ?>><?php echo $datos[0][1]->$idioma; ?></option>
                                <option value="euskera" <?php if ($idioma == 'euskera') echo 'selected' ?>><?php echo $datos[0][10]->$idioma; ?></option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header bg-primary text-white text-center">
                    <h2><?php echo $datos[0][2]->$idioma; ?></h2>
                </div>
                <div class="card-body">
                    <form action="<?php echo RUTA_URL; ?>/alquilar/inicio" method="POST">
                        <div class="form-group">
                            <label for="fecha_recogida"><?php echo $datos[0][13]->$idioma; ?>:</label>
                            <input type="date" class="form-control" id="fecha_recogida" name="fecha_recogida" value="<?php if (isset($datos['correct']['fecha_recogida']) && !empty($datos['correct']['fecha_recogida'])) echo date('Y-m-d', strtotime($datos['correct']['fecha_recogida'])); ?>" required>
                            <span class="text-danger"><?php if (isset($datos['errors']['fecha_recogida'])) echo $datos['errors']['fecha_recogida']; ?></span>
                        </div>
                        <div class="form-group">
                            <label for="fecha_devolucion"><?php echo $datos[0][12]->$idioma; ?>:</label>
                            <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" value="<?php if (isset($datos['correct']['fecha_devolucion']) && !empty($datos['correct']['fecha_devolucion'])) echo date('Y-m-d', strtotime($datos['correct']['fecha_devolucion'])); ?>" required>
                            <span class="text-danger"><?php if (isset($datos['errors']['fecha_devolucion'])) echo $datos['errors']['fecha_devolucion']; ?></span>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary mt-3"><?php echo $datos[0][7]->$idioma; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        // Cargamos el footer al final de la página
        require RUTA_APP . '/views/inc/footer.php';
        ?>