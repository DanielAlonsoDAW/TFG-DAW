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

    public function crearReserva($datos, $total)
    {
        $this->db->query("INSERT INTO patitas_reservas 
        (duenio_id, cuidador_id, fecha_inicio, fecha_fin, servicio, estado, total, numero_mascotas) 
        VALUES (:duenio_id, :cuidador_id, :inicio, :fin, :servicio, :estado, :total, :num)");

        $this->db->bind(':duenio_id', $datos['duenio_id']);
        $this->db->bind(':cuidador_id', $datos['cuidador_id']);
        $this->db->bind(':inicio', $datos['fecha_inicio']);
        $this->db->bind(':fin', $datos['fecha_fin']);
        $this->db->bind(':servicio', $datos['servicio']);
        $this->db->bind(':total', $total);
        $this->db->bind(':estado', 'confirmada');
        $this->db->bind(':num', count($datos['mascotas']));
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function asociarMascota($reserva_id, $mascota_id)
    {
        $this->db->query("INSERT INTO patitas_reserva_mascotas (reserva_id, mascota_id) VALUES (:res, :mas)");
        $this->db->bind(':res', $reserva_id);
        $this->db->bind(':mas', $mascota_id);
        $this->db->execute();
    }

    public function obtenerMascotas($duenio_id)
    {
        $this->db->query("SELECT * FROM patitas_mascotas WHERE propietario_id = :id and propietario_tipo = 'dueno'");
        $this->db->bind(':id', $duenio_id);
        return $this->db->registros();
    }

    public function obtenerReservasConfirmadas($cuidador_id)
    {
        $this->db->query("
        SELECT fecha_inicio, fecha_fin, SUM(numero_mascotas) as total_mascotas
        FROM patitas_reservas
        WHERE cuidador_id = :id AND estado = 'confirmada'
        GROUP BY fecha_inicio, fecha_fin
    ");
        $this->db->bind(':id', $cuidador_id);
        return $this->db->registros();
    }

    public function cuidadorOcupadoPorDomicilio($cuidador_id, $inicio, $fin)
    {
        $this->db->query("SELECT COUNT(*) as total FROM patitas_reservas 
                      WHERE cuidador_id = :id 
                      AND servicio = 'Cuidado a domicilio' 
                      AND fecha_inicio <= :fin AND fecha_fin >= :inicio
                      AND estado IN ('reservada', 'confirmada')");
        $this->db->bind(':id', $cuidador_id);
        $this->db->bind(':inicio', $inicio);
        $this->db->bind(':fin', $fin);
        return $this->db->registro()->total > 0;
    }

    public function obtenerReservasPorDueno($dueno_id)
    {
        $this->db->query("
        SELECT r.*, c.nombre AS cuidador_nombre
        FROM patitas_reservas r
        JOIN patitas_cuidadores c ON r.cuidador_id = c.id
        WHERE r.duenio_id = :id
        ORDER BY r.fecha_inicio DESC
    ");
        $this->db->bind(':id', $dueno_id);
        return $this->db->registros();
    }

    public function obtenerReservasPorCuidador($cuidador_id)
    {
        $this->db->query("
        SELECT r.*, c.nombre AS cuidador_nombre, r.numero_mascotas
        FROM patitas_reservas r
        JOIN patitas_duenos c ON r.duenio_id = c.id
        WHERE r.cuidador_id = :id
        ORDER BY r.fecha_inicio DESC
    ");
        $this->db->bind(':id', $cuidador_id);
        return $this->db->registros();
    }

    public function obtenerMascotasDeReserva($reserva_id)
    {
        $this->db->query("SELECT reserva_id, mascota_id FROM patitas_reserva_mascotas WHERE reserva_id = :reserva_id");
        $this->db->bind(':reserva_id', $reserva_id);
        return $this->db->registros();
    }

    public function obtenerPrecioPorServicio($cuidador_id, $servicio)
    {
        $this->db->query("SELECT precio FROM patitas_cuidador_servicios 
                          WHERE cuidador_id = :id AND servicio = :servicio");
        $this->db->bind(':id', $cuidador_id);
        $this->db->bind(':servicio', $servicio);
        return $this->db->registro()->precio ?? 0;
    }

    public function obtenerReservaPorId($id_reserva)
    {
        $this->db->query("SELECT * FROM patitas_reservas WHERE id = :id");
        $this->db->bind(':id', $id_reserva);
        return $this->db->registro();
    }

    public function cancelarReserva($reserva_id)
    {
        $this->db->query("UPDATE patitas_reservas SET estado = 'cancelada' WHERE id = :id");
        $this->db->bind(':id', $reserva_id);
        return $this->db->execute();
    }

    public function rechazarReserva($reserva_id)
    {
        $this->db->query("UPDATE patitas_reservas SET estado = 'rechazada' WHERE id = :id");
        $this->db->bind(':id', $reserva_id);
        return $this->db->execute();
    }

    public function completarReserva($reserva_id)
    {
        $this->db->query("UPDATE patitas_reservas SET estado = 'completada' WHERE id = :id");
        $this->db->bind(':id', $reserva_id);
        return $this->db->execute();
    }
}
