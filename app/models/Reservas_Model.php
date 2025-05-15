<?php
class Reservas_Model
{
    private $db;

    public function __construct()
    {
        $this->db = new DataBase;
    }

    public function contarMascotasConfirmadas($cuidador_id, $inicio, $fin)
    {
        $this->db->query("SELECT SUM(numero_mascotas) as total FROM patitas_reservas 
                          WHERE cuidador_id = :id AND estado = 'confirmada' 
                          AND fecha_inicio <= :fin AND fecha_fin >= :inicio");
        $this->db->bind(':id', $cuidador_id);
        $this->db->bind(':inicio', $inicio);
        $this->db->bind(':fin', $fin);
        return $this->db->registro()->total ?? 0;
    }

    public function obtenerMaxMascotasCuidador($cuidador_id)
    {
        $this->db->query("SELECT max_mascotas_dia FROM patitas_cuidadores WHERE id = :id");
        $this->db->bind(':id', $cuidador_id);
        return $this->db->registro()->max_mascotas_dia;
    }

    public function obtenerPrecioPorServicio($cuidador_id, $servicio)
    {
        // Aquí puedes consultar una tabla con precios si la tienes
        // O retornar un precio fijo de ejemplo
        return 10.00; // Ejemplo
    }

    public function crearReserva($datos, $total)
    {
        $this->db->query("INSERT INTO patitas_reservas (duenio_id, cuidador_id, fecha_inicio, fecha_fin, servicio, total, numero_mascotas) 
                          VALUES (:duenio_id, :cuidador_id, :inicio, :fin, :servicio, :total, :num)");
        $this->db->bind(':duenio_id', $datos['duenio_id']);
        $this->db->bind(':cuidador_id', $datos['cuidador_id']);
        $this->db->bind(':inicio', $datos['fecha_inicio']);
        $this->db->bind(':fin', $datos['fecha_fin']);
        $this->db->bind(':servicio', $datos['servicio']);
        $this->db->bind(':total', $total);
        $this->db->bind(':num', count($datos['mascotas']));
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function asociarMascota($reservaId, $mascotaId)
    {
        $this->db->query("INSERT INTO patitas_reserva_mascotas (reserva_id, mascota_id) VALUES (:res, :mas)");
        $this->db->bind(':res', $reservaId);
        $this->db->bind(':mas', $mascotaId);
        $this->db->execute();
    }

    public function obtenerMascotas($duenio_id)
    {
        $this->db->query("SELECT * FROM patitas_mascotas WHERE propietario_id = :id and propietario_tipo = 'dueno'");
        $this->db->bind(':id', $duenio_id);
        return $this->db->registros();
    }
}
