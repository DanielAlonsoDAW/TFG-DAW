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
        $mascotas = $this->cuidadorModelo->obtenerMascotasCuidador($id);


        $this->vista('cuidadores/perfil', [
            'cuidador' => $datosCuidador,
            'servicios' => $servicios,
            'admite' => $admite,
            'resenas' => $resenas,
            'mascotas' => $mascotas
        ]);
    }
    public function perfilPriv($id = null)
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'cuidador') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id']; // ID del cuidador actual
        $datos = $this->cuidadorModelo->obtenerPerfilCuidador($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datosActualizados = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono']),
                'direccion' => trim($_POST['direccion']),
                'ciudad' => trim($_POST['ciudad']),
                'pais' => trim($_POST['pais']),
                'descripcion' => trim($_POST['descripcion']),
                'max_mascotas_dia' => (int)$_POST['max_mascotas_dia']
            ];

            $this->cuidadorModelo->actualizarDatos($datosActualizados);
            redireccionar('/cuidadores/perfilPriv');
        }

        $this->vista('cuidadores/perfilPriv', ['datos' => $datos]);
    }
}
