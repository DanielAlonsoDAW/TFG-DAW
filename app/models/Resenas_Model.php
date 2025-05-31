<?php

class Resenas_Model
{
    private $db;

    /**
     * Constructor de la clase. Inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->db = new DataBase();
    }

    /**
     * Obtiene una reseña por su ID.
     * @param int $id
     * @return object|null
     */
    public function obtenerResenaPorId($id)
    {
        $this->db->query("SELECT * FROM patitas_resenas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    /**
     * Obtiene una reseña por el ID de la reserva.
     * @param int $reservaId
     * @return object|null
     */
    public function obtenerResenaPorReservaId($reservaId)
    {
        $this->db->query("SELECT * FROM patitas_resenas WHERE reserva_id = :reserva_id");
        $this->db->bind(':reserva_id', $reservaId);
        return $this->db->registro();
    }

    /**
     * Obtiene las reseñas recibidas por un cuidador.
     * @param int $id
     * @return array
     */
    public function obtenerResenasCuidador($id)
    {
        $sql = "SELECT r.id, r.reserva_id, r.comentario, r.calificacion, r.fecha_resena, d.nombre as duenio
            FROM patitas_resenas r
            JOIN patitas_cuidadores d ON r.duenio_id = d.id
            WHERE r.cuidador_id = :id
            ORDER BY r.fecha_resena DESC";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    /**
     * Obtiene las reseñas creadas por el cuidador.
     * @param int $id
     * @return array
     */
    public function obtenerResenasDueno($id)
    {
        $sql = "SELECT r.id, r.reserva_id, r.comentario, r.calificacion, r.fecha_resena, c.nombre as cuidador
            FROM patitas_resenas r
            JOIN patitas_cuidadores c ON r.cuidador_id = c.id
            WHERE r.duenio_id = :id
            ORDER BY r.fecha_resena DESC";
        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->registros();
    }

    /**
     * Crea una nueva reseña.
     * @param array $datos
     * @return bool
     */
    public function crearResena($datos)
    {
        $sql = "INSERT INTO patitas_resenas (reserva_id, duenio_id, cuidador_id, calificacion, comentario, fecha_resena)
                VALUES (:reserva_id, :duenio_id, :cuidador_id, :calificacion, :comentario, NOW())";
        $this->db->query($sql);
        $this->db->bind(':reserva_id', $datos['reserva_id']);
        $this->db->bind(':duenio_id', $datos['duenio_id']);
        $this->db->bind(':cuidador_id', $datos['cuidador_id']);
        $this->db->bind(':calificacion', $datos['calificacion']);
        $this->db->bind(':comentario', $datos['comentario']);

        return $this->db->execute();
    }

    /**
     * Actualiza una reseña existente.
     * @param array $datos
     * @return bool
     */
    public function actualizarResena($datos)
    {
        $this->db->query("UPDATE patitas_resenas SET calificacion = :calificacion, comentario = :comentario WHERE id = :id");
        $this->db->bind(':calificacion', $datos['calificacion']);
        $this->db->bind(':comentario', $datos['comentario']);
        $this->db->bind(':id', $datos['id']);
        return $this->db->execute();
    }
}
