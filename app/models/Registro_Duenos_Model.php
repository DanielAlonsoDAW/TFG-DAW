<?php

class Registro_Duenos_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }
    function compronarEmailBBDD($email)
    {
        $this->db->query("SELECT * from patitas_duenos where email = :email");
        $this->db->bind(':email', $email);

        return $this->db->registro();
    }

    public function agregarDueno($datos)
    {
        $this->db->query("INSERT INTO clientes (nombre, email, contraseña) VALUES (:nombre, :email, :contraseña)");

        // Vinculamos los valores
        $this->db->bind(":nombre", $datos["nombre"]);
        $this->db->bind(":email", $datos["email"]);
        $this->db->bind(":contraseña", $datos["contraseña"]);

        // Ejecutar la consulta
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
