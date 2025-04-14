<?php

class Autenticacion_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerPass($login)
    {
        $this->db->query("SELECT password FROM usuarios WHERE login = :login");
        $this->db->bind(':login', $login);
        return $this->db->registro();
    }

    public function obtenerGrupo($login)
    {
        $this->db->query("SELECT grupo from usuarios WHERE login = :login");
        $this->db->bind(':login', $login);
        return $this->db->registro();
    }
}
