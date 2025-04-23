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
        $sql = "SELECT c.id, c.nombre, c.ciudad,
                       COALESCE(AVG(r.calificacion), 0) AS media_valoracion
                FROM patitas_cuidadores c
                LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
                GROUP BY c.id, c.nombre, c.ciudad";

        $this->db->query($sql);
        return $this->db->registros();
    }
}
