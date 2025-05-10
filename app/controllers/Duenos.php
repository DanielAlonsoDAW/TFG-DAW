<?php
class Duenos extends Controlador
{
    private $duenoModelo;
    public function __construct()
    {
        session_start();
        $this->duenoModelo = $this->modelo('Duenos_Model');
    }

    public function perfilPriv($id = null)
    {
        if (!isset($_SESSION['usuario']) || $_SESSION['grupo'] !== 'dueno') {
            redireccionar('/autenticacion');
        }

        $id = $_SESSION['usuario_id'];
        $datos = $this->duenoModelo->obtenerPerfilDueno($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datosActualizados = [
                'id' => $id,
                'nombre' => trim($_POST['nombre']),
                'email' => trim($_POST['email']),
                'telefono' => trim($_POST['telefono'])
            ];

            $this->duenoModelo->actualizarDatos($datosActualizados);
            redireccionar('/duenos/perfilPriv');
        }

        $this->vista('duenos/perfilPriv', ['datos' => $datos]);
    }
}
