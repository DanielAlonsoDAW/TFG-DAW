<?php
class Cuidadores extends Controlador
{
    private $cuidadorModelo;
    public function __construct()
    {
        session_start();
        $this->cuidadorModelo = $this->modelo('Cuidadores_Model');
    }
    public function perfil($id)
    {
        $datosCuidador = $this->cuidadorModelo->obtenerPerfilCuidador($id);
        $servicios = $this->cuidadorModelo->obtenerServicios($id);
        $admite = $this->cuidadorModelo->obtenerTiposMascotas($id);
        $resenas = $this->cuidadorModelo->obtenerResenas($id);

        $this->vista('cuidadores/perfil', [
            'cuidador' => $datosCuidador,
            'servicios' => $servicios,
            'admite' => $admite,
            'resenas' => $resenas
        ]);
    }
}
