<?php
class Buscador extends Controlador
{
    private $buscadorModelo;

    public function __construct()
    {
        session_start();
        $this->buscadorModelo = $this->modelo('Buscador_Model');
    }

    // Vista principal
    public function index()
    {
        $this->vista('buscador/inicio');
    }

    // API para obtener cuidadores filtrados por ciudad y ordenados por media de valoración
    public function api_filtrar()
    {
        header('Content-Type: application/json');

        $ciudadInput = $_GET['ciudad'] ?? '';
        $modelo = $this->buscadorModelo;

        // Obtener cuidadores con su media de calificaciones
        $todos = $modelo->obtenerCuidadoresTOP();

        // Si no se indica ciudad, ordena todos por valoración
        if (empty($ciudadInput)) {
            usort($todos, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);
            echo json_encode(array_slice($todos, 0, 10));
            return;
        }

        // Filtrar por ciudad indicada
        $filtrados = array_filter($todos, function ($cuidador) use ($ciudadInput) {
            return stripos($cuidador->ciudad, $ciudadInput) !== false;
        });

        // Ordenar por valoración
        usort($filtrados, fn($a, $b) => $b->media_valoracion <=> $a->media_valoracion);

        // Devolver los top 10
        echo json_encode(array_slice($filtrados, 0, 10));
    }
}
