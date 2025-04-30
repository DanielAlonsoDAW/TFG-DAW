<?php
// Cargamos librerias
require_once 'config/configurar.php';
require_once 'helpers/url_helper.php';

// Usamos la función sp_autoload_register par cargar todos los ficheros que se encuentren en la carpeta librerias sin tener que indicarlos uno a uno
spl_autoload_register(function($nombreClase){
    require_once 'librerias/' . $nombreClase. '.php';
});

$dotenvPath = __DIR__ . '/../.env';
if (file_exists($dotenvPath)) {
    foreach (file($dotenvPath) as $line) {
        if (trim($line) !== "") {
            putenv(trim($line));
        }
    }
}