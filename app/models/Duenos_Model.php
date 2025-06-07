<?php

class Duenos_Model
{
    private $db;

    // Constructor: inicializa la conexión a la base de datos
    public function __construct()
    {
        $this->db = new DataBase();
    }

    // Obtiene el perfil de un dueño por su ID
    public function obtenerPerfilDueno($id)
    {
        $this->db->query("SELECT * FROM patitas_duenos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Actualiza los datos personales del dueño (nombre, imagen)
    public function actualizarDatos($datos)
    {
        $this->db->query("UPDATE patitas_duenos 
                      SET nombre = :nombre, imagen = :imagen 
                      WHERE id = :id");

        // Asocia los valores a los parámetros de la consulta
        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    // Actualiza los datos de acceso del dueño (email, contraseña)
    public function actualizarAccesos($datos)
    {
        $this->db->query("UPDATE patitas_duenos 
        SET email = :email, contrasena = :contrasena
        WHERE id = :id");

        // Asocia los valores a los parámetros de la consulta
        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    // Obtiene la contraseña de un dueño por su ID
    public function obtenerContrasenaPorId($id)
    {
        $this->db->query("SELECT contrasena FROM patitas_duenos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Guarda una factura asociada a una reserva
    public function guardarFactura($reserva_id, $archivo_url)
    {
        $this->db->query("INSERT INTO patitas_facturas (reserva_id, archivo_pdf_url) VALUES (:reserva_id, :archivo_url)");
        $this->db->bind(':reserva_id', $reserva_id);
        $this->db->bind(':archivo_url', $archivo_url);
        return $this->db->execute();
    }

    // Obtiene una factura por el ID de la reserva
    public function obtenerFactura($reserva_id)
    {
        $this->db->query("SELECT * FROM patitas_facturas WHERE reserva_id = :reserva_id");
        $this->db->bind(':reserva_id', $reserva_id);
        return $this->db->registro();
    }
}
