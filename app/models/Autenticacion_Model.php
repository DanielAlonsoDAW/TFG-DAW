<?php

// Modelo de autenticación para manejar usuarios dueños y cuidadores
class Autenticacion_Model
{
    private $db;

    // Constructor: inicializa la conexión a la base de datos
    public function __construct()
    {
        $this->db = new DataBase();
    }

    // Obtiene los datos de un dueño por su email
    public function obtenerDuenoPorEmail($email)
    {
        $this->db->query("SELECT * FROM patitas_duenos WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->registro();
    }

    // Obtiene los datos de un cuidador por su email
    public function obtenerCuidadorPorEmail($email)
    {
        $this->db->query("SELECT * FROM patitas_cuidadores WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->registro();
    }
}
