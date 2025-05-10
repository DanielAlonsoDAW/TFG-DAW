<?php

class Cuidadores_Model
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

    public function obtenerCuidadoresTOP()
    {
        $sql = "SELECT  c.id, c.nombre, c.ciudad, c.pais,c.descripcion, c.imagen,
                    COALESCE(AVG(r.calificacion), 0) AS promedio_calificacion,COUNT(r.id) AS total_resenas
                    FROM patitas_cuidadores c
                    LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
                    GROUP BY c.id, c.nombre, c.ciudad, c.pais, c.descripcion, c.imagen
                    ORDER BY promedio_calificacion DESC LIMIT 15";

        $this->db->query($sql);
        return $this->db->registros();
    }

    public function obtenerPerfilCuidador($id)
    {
        $sql = "SELECT c.id, c.nombre, c.email, c.telefono, c.direccion, c.ciudad, c.pais, 
               c.descripcion, c.max_mascotas_dia, c.imagen, c.fecha_registro,
               AVG(r.calificacion) AS promedio_calificacion
        FROM patitas_cuidadores c
        LEFT JOIN patitas_resenas r ON c.id = r.cuidador_id
        WHERE c.id = :id
        GROUP BY c.id, c.nombre, c.email, c.telefono, c.direccion, c.ciudad, c.pais, 
                 c.descripcion, c.max_mascotas_dia, c.imagen, c.fecha_registro";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registro();
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

    public function obtenerMascotasCuidador($id)
    {
        $sql = "SELECT m.id, m.nombre, m.tipo, m.raza, m.edad, m.tamano, m.observaciones,
                   (SELECT imagen FROM patitas_mascotas_imagenes WHERE mascota_id = m.id LIMIT 1) AS imagen
            FROM patitas_mascotas m
            WHERE m.propietario_tipo = 'cuidador' AND m.propietario_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    public function actualizarDatos($datos)
    {
        $this->db->query("UPDATE patitas_cuidadores 
                      SET nombre = :nombre, email = :email, telefono = :telefono,
                          direccion = :direccion, ciudad = :ciudad, pais = :pais,
                          descripcion = :descripcion, max_mascotas_dia = :max_mascotas_dia
                      WHERE id = :id");

        foreach ($datos as $campo => $valor) {
            $this->db->bind(":$campo", $valor);
        }

        return $this->db->execute();
    }
}
