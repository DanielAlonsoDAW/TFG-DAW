<?php
class Mascotas_Model
{
    private $db;
    public function __construct()
    {
        $this->db = new DataBase();
    }

    public function obtenerMascotas($propietario_tipo, $propietario_id)
    {
        $sql = "SELECT id, nombre, tipo, raza, edad, tamano, observaciones
            FROM patitas_mascotas
            WHERE propietario_tipo = :tipo
              AND propietario_id   = :id";
        $this->db->query($sql);
        $this->db->bind(':tipo', $propietario_tipo);
        $this->db->bind(':id', $propietario_id);
        return $this->db->registros();
    }

    public function obtenerMascotaPorId($id)
    {
        $this->db->query("SELECT id, nombre, tipo, raza, edad, tamano, observaciones FROM patitas_mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    public function obtenerImagenes($mascota_id)
    {
        $sql = "SELECT id, imagen 
            FROM patitas_mascotas_imagenes
            WHERE mascota_id = :id";
        $this->db->query($sql);
        $this->db->bind(':id', $mascota_id);
        return $this->db->registros();
    }

    public function insertarMascota($datos)
    {
        $this->db->query("
            INSERT INTO patitas_mascotas
              (nombre, tipo, raza, edad, tamano, observaciones, propietario_tipo, propietario_id)
            VALUES
              (:nombre, :tipo, :raza, :edad, :tamano, :observaciones, :ptipo, :pid)
        ");
        $this->db->bind(':nombre',         $datos['nombre']);
        $this->db->bind(':tipo',           $datos['tipo']);
        $this->db->bind(':raza',           $datos['raza']);
        $this->db->bind(':edad',           $datos['edad']);
        $this->db->bind(':tamano',         $datos['tamano']);
        $this->db->bind(':observaciones',  $datos['observaciones']);
        $this->db->bind(':ptipo',          $datos['propietario_tipo']);
        $this->db->bind(':pid',            $datos['propietario_id']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function insertarImagen($mascota_id, $ruta)
    {
        $this->db->query("
            INSERT INTO patitas_mascotas_imagenes (mascota_id, imagen)
            VALUES (:mid, :img)
        ");
        $this->db->bind(':mid', $mascota_id);
        $this->db->bind(':img', $ruta);
        return $this->db->execute();
    }

    public function actualizarMascota($datos)
    {
        $this->db->query("UPDATE patitas_mascotas 
        SET nombre = :nombre, tipo = :tipo, raza = :raza, edad = :edad, 
            tamano = :tamano, observaciones = :observaciones
        WHERE id = :id");

        $this->db->bind(':id', $datos['id']);
        $this->db->bind(':nombre', $datos['nombre']);
        $this->db->bind(':tipo', $datos['tipo']);
        $this->db->bind(':raza', $datos['raza']);
        $this->db->bind(':edad', $datos['edad']);
        $this->db->bind(':tamano', $datos['tamano']);
        $this->db->bind(':observaciones', $datos['observaciones']);

        return $this->db->execute();
    }

    public function eliminarMascota($id)
    {
        $this->db->query("DELETE FROM patitas_mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function mascotasValidas($mascotas, $propietario_id)
    {
        if (empty($mascotas)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($mascotas), '?'));
        $sql = "SELECT id FROM patitas_mascotas WHERE propietario_id = ? AND id IN ($placeholders)";
        $this->db->query($sql);

        $this->db->bind(1, $propietario_id);

        foreach ($mascotas as $index => $mascota) {
            $this->db->bind($index + 2, $mascota);
        }

        return count($this->db->registros()) === count($mascotas);
    }
}
