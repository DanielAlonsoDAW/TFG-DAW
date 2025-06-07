<?php

class Registro_Model
{
    private $db;

    // Constructor: inicializa la conexión a la base de datos
    public function __construct()
    {
        $this->db = new DataBase();
    }

    // Comprueba si un email existe en la base de datos especificada
    function comprobarEmailBBDD($email, $bbdd)
    {
        // Prepara la consulta SQL para buscar el email
        $this->db->query("SELECT * from $bbdd where email = :email");
        // Asocia el valor del email al parámetro de la consulta
        $this->db->bind(':email', $email);

        // Ejecuta la consulta y devuelve el resultado
        return $this->db->registro();
    }

    // Agrega un nuevo usuario a la base de datos especificada
    public function agregarUsuario($datos, $bbdd)
    {
        // Obtener la inicial del nombre en mayúscula
        $inicial = strtoupper(substr(trim($datos["nombre"]), 0, 1));
        // Construye la ruta de la imagen de avatar según la inicial
        $rutaImagen = "public/img/avatar/" . $inicial . ".png";

        // Prepara la consulta SQL para insertar el usuario con la imagen
        $this->db->query("INSERT INTO $bbdd (nombre, email, contrasena, imagen) 
                          VALUES (:nombre, :email, :contrasena, :imagen)");

        // Asocia los valores a los parámetros de la consulta
        $this->db->bind(":nombre", $datos["nombre"]);
        $this->db->bind(":email", $datos["email"]);
        $this->db->bind(":contrasena", $datos["contrasena"]);
        $this->db->bind(":imagen", $rutaImagen);

        // Ejecuta la consulta y devuelve el resultado
        return $this->db->execute();
    }
}
