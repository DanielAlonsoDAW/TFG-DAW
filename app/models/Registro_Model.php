<?php

class Registro_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase();
    }
    function comprobarEmailBBDD($email, $bbdd)
    {
        $this->db->query("SELECT * from $bbdd where email = :email");
        $this->db->bind(':email', $email);

        return $this->db->registro();
    }

    public function agregarUsuario($datos, $bbdd)

    {
        // Obtener la inicial del nombre en mayúscula
        $inicial = strtoupper(substr(trim($datos["nombre"]), 0, 1));
        $rutaImagen = "public/img/avatar/" . $inicial . ".png";

        // Consulta con imagen incluida
        $this->db->query("INSERT INTO $bbdd (nombre, email, contrasena, imagen) 
                          VALUES (:nombre, :email, :contrasena, :imagen)");

        // Vincular los valores
        $this->db->bind(":nombre", $datos["nombre"]);
        $this->db->bind(":email", $datos["email"]);
        $this->db->bind(":contrasena", $datos["contrasena"]);
        $this->db->bind(":imagen", $rutaImagen);

        // Ejecutar la consulta
        return $this->db->execute();
    }
}
