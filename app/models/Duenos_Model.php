<?php

class Duenos_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerPerfilDueno($id)
    {
        $this->db->query("SELECT * FROM patitas_duenos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function actualizarDatos($datos)
    {
        $this->db->query("UPDATE patitas_duenos 
                      SET nombre = :nombre, telefono = :telefono, imagen = :imagen 
                      WHERE id = :id");

        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }

    public function obtenerContrasenaPorId($id)
    {
        $this->db->query("SELECT contrasena FROM patitas_duenos WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function guardarFactura($reserva_id, $archivo_url)
    {
        $this->db->query("INSERT INTO patitas_facturas (reserva_id, archivo_pdf_url) VALUES (:reserva_id, :archivo_url)");
        $this->db->bind(':reserva_id', $reserva_id);
        $this->db->bind(':archivo_url', $archivo_url);
        return $this->db->execute();
    }

    public function obtenerFactura($reserva_id)
    {
        $this->db->query("SELECT * FROM patitas_facturas WHERE reserva_id = :reserva_id");
        $this->db->bind(':reserva_id', $reserva_id);
        return $this->db->registro();
    }
}
