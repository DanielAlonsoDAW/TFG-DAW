<?php
// Cargamos el header previamente
require RUTA_APP . '/views/inc/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <!-- Imagen y datos principales -->
        <div class="col-md-4">
            <img src="<?= RUTA_URL ?>/public/img/<?= $datos['cuidador']->imagen ?>" class="img-fluid rounded mb-3" alt="Imagen cuidador">
            <h2><?= $datos['cuidador']->nombre ?></h2>
            <p><strong>Ciudad:</strong> <?= $datos['cuidador']->ciudad ?> (<?= $datos['cuidador']->pais ?>)</p>
            <p><strong>Teléfono:</strong> <?= $datos['cuidador']->telefono ?></p>
            <p><strong>Email:</strong> <?= $datos['cuidador']->email ?></p>
        </div>

        <!-- Descripción, servicios, reseñas -->
        <div class="col-md-8">
            <h4>Sobre mí</h4>
            <p class="truncar-2-lineas"><?= nl2br($datos['cuidador']->descripcion) ?></p>

            <h4 class="mt-4">Servicios ofrecidos</h4>
            <ul>
                <?php foreach ($datos['servicios'] as $serv): ?>
                    <li><?= $serv->servicio ?> - <?= number_format($serv->precio, 2) ?>€</li>
                <?php endforeach; ?>
            </ul>

            <h4 class="mt-4">Acepta:</h4>
            <ul>
                <?php foreach ($datos['admite'] as $a): ?>
                    <li><?= ucfirst($a->tipo_mascota) ?> de tamaño <?= $a->tamano ?></li>
                <?php endforeach; ?>
            </ul>

            <h4 class="mt-4">Valoración media:</h4>
            <p>
                <?= estrellasBootstrap($datos['cuidador']->promedio_calificacion) ?>
                (<?= number_format($datos['cuidador']->promedio_calificacion, 1) ?>/5)
            </p>

            <h4 class="mt-4">Reseñas</h4>
            <?php foreach ($datos['resenas'] as $res): ?>
                <div class="border rounded p-2 mb-2">
                    <strong><?= $res->duenio ?></strong> – <?= date('d/m/Y', strtotime($res->fecha_resena)) ?><br>
                    <?= estrellasBootstrap($res->calificacion) ?>
                    <p><?= nl2br($res->comentario) ?></p>
                </div>
            <?php endforeach; ?>

            <!-- Botón reservar -->
            <div class="mt-4">
                <a href="<?= RUTA_URL ?>/reservas/crear/<?= $datos['cuidador']->id ?>" class="btn btn-primary">Reservar ahora</a>
            </div>
        </div>
    </div>
</div>
<?php
// Cargamos el footer al final de la página
require RUTA_APP . '/views/inc/footer.php';
?>