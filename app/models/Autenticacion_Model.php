<?php

class Autenticacion_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerDuenoPorEmail($email)
    {
        $this->db->query("SELECT * FROM patitas_duenos WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->registro();
    }

    public function obtenerCuidadorPorEmail($email)
    {
        $this->db->query("SELECT * FROM patitas_cuidadores WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->registro();
    }
}
