<?php

class Buscador_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }
    public function obtenerCuidadores()
    {
        $this->db->query("SELECT nombre, ciudad FROM patitas_cuidadores");
        return $this->db->registros();
    }

    public function obtenerCuidadoresConMedia()
    {
        $sql = "SELECT c.id, c.nombre, c.ciudad, c.direccion,
                       COALESCE(AVG(r.calificacion), 0) AS media_valoracion
                FROM patitas_cuidadores c
                LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
                GROUP BY c.id, c.nombre, c.ciudad, c.direccion";

        $this->db->query($sql);
        return $this->db->registros();
    }

    public function obtenerServicios($id)
    {
        $sql = "SELECT servicio, precio
            FROM patitas_cuidador_servicios
            WHERE cuidador_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    public function obtenerTiposMascotas($id)
    {
        $sql = "SELECT tipo_mascota, tamano
            FROM patitas_cuidador_admite
            WHERE cuidador_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    public function obtenerResenas($id)
    {
        $sql = "SELECT r.comentario, r.calificacion, r.fecha_resena, d.nombre as duenio
            FROM patitas_resenas r
            JOIN patitas_cuidadores d ON r.duenio_id = d.id
            WHERE r.cuidador_id = :id
            ORDER BY r.fecha_resena DESC";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }
}
